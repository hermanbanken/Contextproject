<?php defined('SYSPATH') or die('No direct access allowed.');

class Message {
	
	const KEY = 'stored_messages';
	
	/**
	 * Add a message to display at next view
	 */
	public static function add($type, $message){
		$messages = self::all();
		$messages[] = array('type'=>$type, 'message'=>$message);
		Session::instance()->set(self::KEY, $messages);
	}
	
	/**
	 * Get all messages that are currently in queue
	 */
	private static function all(){
		return Session::instance()->get(self::KEY, array());
	}
	
	/**
	 * Pull all messages and clear the queue
	 */
	public static function pull(){
		$messages = self::all();
		Session::instance()->delete(self::KEY);
		return $messages;
	}
}