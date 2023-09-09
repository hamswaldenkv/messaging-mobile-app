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

use Fuel\Core\Controller;
use Fuel\Core\Presenter;
use Fuel\Core\Response;
use Fuel\Core\Str;
use Fuel\Core\Uri;

/**
 * The Welcome Controller.
 *
 * A basic controller example.  Has examples of how to set the
 * response body and status.
 *
 * @package  app
 * @extends  Controller
 */
class Controller_Welcome extends Controller
{
	/**
	 * The basic welcome message
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_index()
	{
		Printer::printResult([
			'server' => $_SERVER,
			'uri'	=> Uri::base(true) . '/' . $_SERVER['PHP_SELF']
		]);
		return Response::forge(View::forge('welcome/index'));
	}

	/**
	 * A typical "Hello, Bob!" type example.  This uses a Presenter to
	 * show how to use them.
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_hello()
	{
		return Response::forge(Presenter::forge('welcome/hello'));
	}

	/**
	 * A typical "Hello, Bob!" type example.  This uses a Presenter to
	 * show how to use them.
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_admin()
	{
		$event = $this->createEvent('My Fucking event 4');
		Printer::printResult($event);
	}

	/**
	 * The 404 action for the application.
	 *
	 * @access  public
	 * @return  Response
	 */
	public function action_404()
	{
		return Response::forge(Presenter::forge('welcome/404'), 404);
	}

	private function createEvent($title)
	{
		$startAt = time() + 7200;
		$finishAt = $startAt + 7200;

		$event = Model_Event::forge();
		$event->event_id = Str::random('uuid');
		$event->title = $title;
		$event->description = 'this is a fucking event description';
		$event->organisator_name = 'Fabrice Mandeke';
		$event->venue_place = 'My Place';
		$event->venue_address = '123, avenue, suite, immeuble, Commune';
		$event->is_live = false;
		$event->is_free = true;
		$event->status = 1;
		$event->start_at = $startAt;
		$event->finish_at = $finishAt;
		// $event->save();

		return $event;
	}
}
