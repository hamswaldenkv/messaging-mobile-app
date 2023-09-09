<?php
/**
 * Fuel is a fast, lightweight, community driven PHP 5.4+ framework.
 *
 * @package    Fuel
 * @version    1.8.2
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2019 Fuel Development Team
 * @link       https://fuelphp.com
 */

return array(
	/**
	 * -------------------------------------------------------------------------
	 *  Default route
	 * -------------------------------------------------------------------------
	 *
	 */

	'_root_' => 'welcome/index',

	/**
	 * -------------------------------------------------------------------------
	 *  Page not found
	 * -------------------------------------------------------------------------
	 *
	 */

	'_404_' => 'welcome/404',

	/**
	 * -------------------------------------------------------------------------
	 *  Example for Presenter
	 * -------------------------------------------------------------------------
	 *
	 *  A route for showing page using Presenter
	 *
	 */

	'hello(/:name)?' => array('welcome/hello', 'name' => 'hello'),

	'api/chat' 					=> 'welcome/index',

	'api/chat/auth/login' 		=> 'auth/login',
	'api/chat/auth/register' 	=> 'auth/register',
	'api/chat/message/filter' 	=> 'message/filter',
	'api/chat/message/send' 	=> 'message/send',
	'api/chat/chat/thread' 		=> 'chat/thread',
	'api/chat/chat/threads' 	=> 'chat/threads',
	'api/chat/uploads/upload' 	=> 'uploads/upload',
	'api/chat/event/list' 		=> 'event/list',
	'api/chat/event/single' 	=> 'event/single',
	'api/chat/event/join/:id' 	=> 'event/join/$1',
	'api/chat/event/join' 		=> 'event/join',
);
