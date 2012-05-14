<?php defined('SYSPATH') or die('No direct access allowed.');

class Controller_4sq extends Controller {

	public function action_index(){
		
		$authcode = Session::instance()->get("4sq.token", false);
		if( $authcode )
		{
			// Do cool stuff
		}
		else 
		{
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
			$this->verify();	
		} 
		else if( $error = $this->request->query('error') )
		{
			echo "The url's didn't match :( <br>";
			echo URL::site('4sq/cb', true) . "<br>";
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
		$obj = @json_decode($response->body);
		
		if( $obj && isset($obj->access_token) )
		{
			Session::instance()->set("4sq.token", $obj->access_token);			
		} else {
			return false;
		}
	}

}