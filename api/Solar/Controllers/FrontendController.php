<?php

namespace Solar\Controllers;

use Silex\Application;
use Solar\Controller;
use Solar\View;
use Solar\Widgets;

class FrontendController extends Controller
{

	public function __construct(Application $app)
	{
		parent::__construct($app);
	}

	public function index()
	{
		$this->addWidget('testbox', new Widgets\IndexWidget(array('test' => 1)));
	
		return array();
	}

	public function about()
	{
		return array();
	}

	public function features()
	{
		return array();
	}

	public function developer()
	{
		return array();
	}

	public function press()
	{
		return array();
	}

	public function privacy()
	{
		return array();
	}

	public function terms()
	{
		return array();
	}

	public function fourohfour()
	{

	}

}