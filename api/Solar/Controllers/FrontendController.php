<?php

namespace Solar\Controllers;

use Silex\Application;
use Solar\Controller;
use Solar\View;
use Solar\Widget;

class FrontendController extends Controller
{

	public function __construct(Application $app)
	{
		parent::__construct($app);
	}

	public function index()
	{
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