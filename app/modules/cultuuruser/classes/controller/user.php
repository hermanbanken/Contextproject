<?php defined('SYSPATH') or die('No direct script access.');

class Controller_User extends Controller_Template {
 
    public function action_index()
    {
        $this->template->body = View::factory('user/profile')
            ->bind('user', $user);
         
        // Load the user information
        $user = Auth::instance()->get_user();
         
        // if a user is not logged in, redirect to login page
        if (!$user)
        {
            $this->request->redirect('user/login');
        }
    }
    
    public function action_menu(){
    	$this->template = View::factory('user/menu')->bind('user', $user);
    	$user = Auth::instance()->get_user();
    }

	public function action_profile(){
		$v = View::factory('user/profile');
		$v->bind('user', $user);
		
		$this->template->body = $v;
		$user = Auth::instance()->get_user();
	}
 
    public function action_register() 
    {
        $this->template->body = View::factory('user/register')
            ->bind('errors', $errors)
            ->bind('message', $message)
			->set('post', $this->request->post());
             
        if (HTTP_Request::POST == $this->request->method()) 
        {           
            try {
         
                // Create the user using form values
                $user = ORM::factory('user')->create_user($this->request->post(), array(
                    'username',
                    'password',
                    'email'            
                ));
                 
                // Grant user login role
                $user->add('roles', ORM::factory('role', array('name' => 'login')));
                 
                // Reset values so form is not sticky
                $_POST = array();
                 
                // Set success message
                $message = "You have added user '{$user->username}' to the database";
                 
            } catch (ORM_Validation_Exception $e) {
                 
                // Set failure message
                $message = 'There were errors, please see form below.';
                 
                // Set errors using custom messages
				$etree = $e->errors('models');
				if(isset($etree['_external'])){
					$etree = array_merge($etree, $etree['_external']);
					unset($etree['_external']);
				}
                $errors = $etree;
            }
        }
    }
     
    public function action_login() 
    {
        $this->template->body = View::factory('user/login')
            ->bind('message', $message);
             
        if (HTTP_Request::POST == $this->request->method()) 
        {
            // Attempt to login user
            $remember = array_key_exists('remember', $this->request->post()) ? (bool) $this->request->post('remember') : FALSE;
            $user = Auth::instance()->login($this->request->post('username'), $this->request->post('password'), $remember);
             
            // If successful, redirect user
            if ($user) 
            {
                Request::current()->redirect('user/index');
            } 
            else
            {
                $message = 'Login failed';
            }
        }
    }
     
    public function action_logout() 
    {
        // Log user out
        Auth::instance()->logout();
         
        // Redirect to login page
        Request::current()->redirect('user/login');
    }

	/**
	 * Redirect to the provider's auth URL
	 * @param string $provider
	 */
	function action_provider ($provider_name = null)
	{
		// Controller(param) is deprecated in Kohana 3.2
		$provider_name = $this->request->param('provider', $provider_name);
		
		if (Auth::instance()->logged_in())
		{
			Message::add('success', 'Already logged in.');
			// redirect to the user account
			$this->request->redirect('user/profile');
		}
		
		$provider = Provider::factory($provider_name);
		if ($this->request->param('code') && $this->request->param('state'))
		{
			$this->action_provider_return($provider_name);
			return;
		}
		if (is_object($provider))
		{
			$this->request->redirect(
			$provider->redirect_url('/user/provider_return/' . $provider_name));
			return;
		}
		Message::add('error', 'Provider is not enabled; please select another provider or log in normally.');
		
		$this->request->redirect('user/login');
		return;
	}

	function action_associate($provider_name = null){
		
		// Controller(param) is deprecated in Kohana 3.2
		$provider_name = $this->request->param('provider', $provider_name);
		
		if ($this->request->param('code') && $this->request->param('state')){
			$this->action_associate_return($provider_name);
			return;
		}
		if (Auth::instance()->logged_in())
		{
			if (isset($_POST['confirmation']) && $_POST['confirmation'] == 'Y')
			{
				$provider = Provider::factory($provider_name);
				if (is_object($provider))
				{
					$this->request->redirect($provider->redirect_url('/user/associate_return/' . $provider_name));
					return;
				}
				else
				{
					Message::add('error', 'Provider is not enabled; please select another provider or log in normally.');
					$this->request->redirect('user/login');
					return;
				}
			}
			else 
				if (isset($_POST['confirmation']))
				{
					Message::add('error', 'Please click Yes to confirm associating the account.');
					$this->request->redirect('user/profile');
					return;
				}
		}
		else
		{
			Message::add('error', 'You are not logged in.');
			$this->request->redirect('user/login');
			return;
		}
		$this->template->content = View::factory('user/associate')->set('provider_name', $provider_name);
	}

	/**
	 * Associate a logged in user with an account.
	 *
	 * Note that you should not trust the OAuth/OpenID provider-supplied email
	 * addresses. Yes, for Facebook, Twitter, Google and Yahoo the user is actually
	 * required to ensure that the email is in fact one that they control.
	 *
	 * However, with generic OpenID (and non-trusted OAuth providers) one can setup a
	 * rogue provider that claims the user owns a particular email address without
	 * actually owning it. So if you trust the email information, then you open yourself to
	 * a vulnerability since someone might setup a provider that claims to own your
	 * admin account email address and if you don't require the user to log in to
	 * associate their account they gain access to any account.
	 *
	 * TL;DR - the only information you can trust is that the identity string is
	 * associated with that user on that openID provider, you need the user to also
	 * prove that they want to trust that identity provider on your application.
	 *
	 */
	function action_associate_return($provider_name = null){
		
		// Controller(param) is deprecated in Kohana 3.2
		$provider_name = $this->request->param('provider', $provider_name);
		
		if (Auth::instance()->logged_in())
		{
			$provider = Provider::factory($provider_name);
			// verify the request
			if (is_object($provider) && $provider->verify())
			{
				$user = Auth::instance()->get_user();
				if ($user->loaded() && is_numeric($user->id))
				{
					if (Auth::instance()->logged_in() && Auth::instance()->get_user()->id == $user->id)
					{
						// found: "merge" with the existing user
						$user_identity = ORM::factory('user_identity');
						$user_identity->user_id = $user->id;
						$user_identity->provider = $provider_name;
						$user_identity->identity = $provider->user_id();
						if ($user_identity->check())
						{
							Message::add('success', __('Your user account has been associated with this provider.'));
							$user_identity->save();
							// redirect to the user account
							$this->request->redirect('user/profile');
							return;
						}
						else
						{
							Message::add('error', 'We were unable to associate this account with the provider. Please make sure that there are no other accounts using this provider identity, as each 3rd party provider identity can only be associated with one user account.');
							$this->request->redirect('user/login');
							return;
						}
					}
				}
			}
		}
		Message::add('error', 'There was an error associating your account with this provider.');
		$this->request->redirect('user/login');
		return;
	}

	/**
	 * Allow the user to login and register using a 3rd party provider.
	 */
	function action_provider_return($provider_name = null)
	{
		// Controller(param) is deprecated in Kohana 3.2
		$provider_name = $this->request->param('provider', $provider_name);

		$provider = Provider::factory($provider_name);
		if (! is_object($provider))
		{
			Message::add('error', 'Provider is not enabled; please select another provider or log in normally.');
			$this->request->redirect('user/login');
			return;
		}
		// verify the request
		if ($provider->verify())
		{
			// check for previously connected user
			$uid = $provider->user_id();
			$user_identity = ORM::factory('user_identity')
				->where('provider', '=', $provider_name)
				->and_where('identity', '=', $uid)
				->find();
			if ($user_identity->loaded())
			{
				$user = $user_identity->user;
				if ($user->loaded() && $user->id == $user_identity->user_id && is_numeric($user->id))
				{
					// found, log user in
					Auth::instance()->force_login($user);
					// redirect to the user account
					$this->request->redirect('user/profile');
					return;
				}
			}
			// create new account
			if (! Auth::instance()->logged_in())
			{
				// Instantiate a new user
				$user = ORM::factory('user');
				// fill in values
				// generate long random password (maximum that passes validation is 42 characters)
				$password = $user->generate_password(42);
				$values = array(
					// get a unused username like firstname.surname or firstname.surname2 ...
					'username' => $user->generate_username(
						str_replace(' ', '.', $provider->name())
					), 
					'password' => $password, 
					'password_confirm' => $password
				);
				if (Valid::email($provider->email(), TRUE))
				{
					$values['email'] = $provider->email();
				}
				try
				{
					// If the post data validates using the rules setup in the user model
					$user->create_user($values, array(
						'username', 
						'password', 
						'email'
					));
					// Add the login role to the user (add a row to the db)
					$login_role = new Model_Role(array(
						'name' => 'login'
					));
					$user->add('roles', $login_role);
					// create user identity after we have the user id
					$user_identity = ORM::factory('user_identity');
					$user_identity->user_id  = $user->id;
					$user_identity->provider = $provider_name;
					$user_identity->identity = $provider->user_id();
					$user_identity->save();
					// sign the user in
					Auth::instance()->login($values['username'], $password);
					// redirect to the user account
					$this->request->redirect('user/profile');
				}
				catch (ORM_Validation_Exception $e)
				{
					if ($provider_name == 'twitter')
					{
						Message::add('error', 'The Twitter API does not support retrieving your email address; you will have to enter it manually.');
					}
					else
					{
						Message::add('error', 'We have successfully retrieved some of the data from your other account, but we were unable to get all the required fields. Please complete form below to register an account.');
					}
					
					// in case the data for some reason fails, the user will still see something sensible:
					// the normal registration form.
					$view = View::factory('user/register');
					$errors = $e->errors('register');
					// Move external errors to main array, for post helper compatibility
					$errors = array_merge($errors, ( isset($errors['_external']) ? $errors['_external'] : array() ));
					$view->set('errors', $errors);
					// Pass on the old form values
					$values['password'] = $values['password_confirm'] = '';
					$view->set('defaults', $values);
					
					$this->template->body = $view;
				}
			}
			else
			{
				Message::add('error', 'You are logged in, but the email received from the provider does not match the email associated with your account.');
				$this->request->redirect('user/profile');
			}
		}
		else
		{
			Message::add('error', 'Retrieving information from the provider failed. Please register below.');
			$this->request->redirect('user/register');
		}
	}
 
}