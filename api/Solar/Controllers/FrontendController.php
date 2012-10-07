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
		$this->addWidget('testbox', new Widgets\IndexWidget(array('test' => 1)));
	}

	public function index()
	{
		return array();
	}

	public function company()
	{
		return array();
	}

	public function brag()
	{
		return array();
	}

	public function developer()
	{
		return array();
	}

	public function help()
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

	public function careers()
	{
		return array();
	}

	public function error($code)
	{

	}

}