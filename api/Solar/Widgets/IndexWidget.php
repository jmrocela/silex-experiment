<?php

namespace Solar\Widgets;

use Solar\Widget;
use Solar\View;
use Solar\Controller;

class IndexWidget extends Widget
{

    public function __construct($data = array())
    {
		parent::__construct($data);
    }
	
	public function create()
	{
		$view = $this->view;
		
		// we attach everything to the widget object
		$this->attach($view('widgets' . DS . 'index'), function ($widget) {
			return (array) $widget;
		});
		
		return $this;
	}
	
}