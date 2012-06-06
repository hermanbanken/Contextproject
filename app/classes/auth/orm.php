<?php

class Auth_ORM extends Kohana_Auth_ORM
{

	/*
	 * Complete the login for the $user.
	 * Also update the tracker to include this user.
	 *
	 * @param $user
	 */
	protected function complete_login( $user )
	{
		parent::complete_login($user);

		// Couple user to tracker
		$logger = new Logger();
		$logger->bind_user($user);
	}

}