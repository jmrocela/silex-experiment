<?php

namespace Solar\Widgets;

use Solar\Widget;
use Solar\View;
use Solar\Controller;

class IndexWidget extends Widget
{

    public function __construct(ViewInterface $view, $callback)
    {
		parent::__construct($view, $callback);
    }
	
}