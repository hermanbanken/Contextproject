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
		// Get tracker id from Session
		$id = Session::instance()->get("KEEEEEEY", false); // second param is default

		// This regenerates the Session (in class Auth, called by Kohana_Auth_ORM)
		// note to Sjoerd from Herman:
		// please check the comment above. Store something,
		// call statement below and die the contents of just stored session var
		parent::complete_login($user);

		// Store tracker id in Session again
		Session::instance()->set("KEEEEEY", $id);

		// Couple user to tracker
		//TrackerThingy::instanceOrSomething()->linkOrSomething($user);
	}

}