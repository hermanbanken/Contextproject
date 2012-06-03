<?php defined('SYSPATH') or die('No direct access allowed.');

class Controller_4sq extends Controller_Template {

	public $template = "layout";

	public function action_index(){
		$this->template->body = "<h1>FourSquare</h1>";
		$this->token();
	}

	/**
	 * Get the user token for FourSquare access
	 * if not set, login at FourSquare
	 * @param $redirect
	 * @return mixed
	 */
	public function token($redirect = false)
	{
		$authcode = Session::instance()->get("4sq.token", false);

		if( $authcode !== false )
		{
			return $authcode;
		}
		else
		{
			Session::instance()->set("4sq.redirect", $redirect);
			$this->request->redirect(
				"https://foursquare.com/oauth2/authenticate".
				URL::query( array(
				  "client_id" => Kohana::$config->load('4sq.client.id'),
				  "response_type" => "code",
				  "redirect_uri" => URL::site('4sq/cb', true)
				))
			);
		}
	}
	
	public function action_cb(){
		if( $code = $this->request->query('code') )
		{
			Session::instance()->set( "4sq.code", $code );
			if($this->verify())
      		{
      			if($red = Session::instance()->get("4sq.redirect"))
        			$this->request->redirect($red);
        		else
          			$this->request->redirect('');
      		}
      		else
      		{
      			$this->template->body = "<h1>Oops</h1><p>Something went wrong :(</p>";
      		}
		} 
		else if( $error = $this->request->query('error') )
		{
			echo "The url's didn't match :( <br>";
			echo URL::site('4sq/cb', true) . "<br>";
		}
	}

	public function action_create(){
		$id = $this->request->param('id');

		if(!isset($id))
		  $this->response->status(404);

		// Get the monument
		$monument = ORM::factory("monument", $this->request->param('id'));

		// Create form
		$view = View::factory('foursquare/form');
		$this->template->body = $view;
		// Suggest view for when duplicates are found
		$suggest = View::factory('foursquare/suggest');

		// Try to authorize with FourSquare
		Session::instance()->set("4sq.redirect", "4sq/create/$id");
		$token = $this->token("4sq/create/$id");

		// Link monument
		if('link' == $this->request->post('action'))
		{
			$v = $this->fetchVenue($this->request->post('id'));
			if($v !== null){
				$venue = ORM::factory('venue', $this->request->post('id'))
						->fromFourSquare($v)
						->set('monument', $monument)
						->save();
				Message::add("info", __("foursquare.venue.added"));
			}
			// Success return to monument single view
			$this->request->redirect("monument/id/$id");
			return;
		}

		// Suggest cached venue
		$venue = $monument->venue();
		if($venue->loaded())
		{
			// Venue is already cached, check to see if user didn't know this
			$suggest->set("venues", array($venue));
			$view->bind("suggest", $suggest);
			$view->bind("venue", $venue);
			return;
		}

		// Make new venue
		$venue = ORM::factory('venue');
		$view->bind("venue", $venue);

		if(HTTP_Request::POST == $this->request->method())
		{
			// Fill venue
			$this->request
					->post("id_monument", $id)
					->post("ll", $monument->lng . ',' . $monument->lat);
			$venue->values($this->request->post());

			// Valid, go try to add it to FourSquare
			if($venue->validation()->check())
			{
				$response = Request::factory("https://api.foursquare.com/v2/venues/add".URL::query(array(
					"oauth_token" => $token,
					"v" => "20120601"
				)))
					->method("post")
					->post( $venue->as_array() )
					->execute();

				$j = @json_decode($response->body());

				if($response->status() == 200)
				{
					$v = $j->response->venue;
					$venue->set("id", $v->id)->save();
					Message::add("info", __("foursquare.venue.added"));
					// Success return to monument single view
					$this->request->redirect("monument/id/$id");
				}
				elseif($response->status() == 409)
				{
					// Duplicates, fill suggest
					$venues = array();
					foreach($j->response->candidateDuplicateVenues as $d){
						$venues[] = ORM::factory('venue')->fromFourSquare($d, $monument);
					}
					$suggest->bind('venues', $venues);
					$view->bind('force', $j->response->ignoreDuplicatesKey);
					$view->bind('suggest', $suggest);
				} else {
					$view->bind("meta", $j->meta);
	        	}
			}
			// Not valid
			else {
				$view->set('errors', $venue->validation()->errors('models'));
			}
    	} else {
			$venue->values(array(
				"name" => $monument->name,
				"address" => $monument->street->name.' '.$monument->streetNumber,
				"city" => $monument->town->name,
				"id_monument" => $monument->id_monument,
				"ll" => $monument->lat . ',' . $monument->lng,
			));
    	}
	}

	public function action_categories()
	{
		//https://api.foursquare.com/v2/events/categories
	}

	public function fetchVenue($id){
		if(!preg_match("~^[a-z0-9A-Z]+$~", $id))
			return;

		$response = Request::factory("https://api.foursquare.com/v2/venues/$id".URL::query(array(
			"oauth_token" => $this->token(),
			"v" => "20120601"
		)))->execute();

		if($response->status() == 200){
			$obj = @json_decode($response->body());
			return $obj->response->venue;
		}
	}

	public function verify(){
		$response = 
			Request::factory(
				"https://foursquare.com/oauth2/access_token".URL::query( array(
					"client_id" => Kohana::$config->load('4sq.client.id'),
					"client_secret" => Kohana::$config->load('4sq.client.secret'),
					"redirect_uri" => URL::site('4sq/cb', true),
					"grant_type" => "authorization_code",
					"code" => Session::instance()->get("4sq.code", ""),
				))
			)->execute();
		$obj = @json_decode($response->body());
		
		if( $obj && isset($obj->access_token) )
		{
			Session::instance()->set("4sq.token", $obj->access_token);
			return true;
		} else {
			return false;
		}
	}

}