<?php

namespace Solar\Helpers;

use Symfony;

class MustacheHelper extends \ArrayObject {

    private $generator;

    public function __construct(Symfony\Component\Routing\Generator\UrlGenerator $generator)
    {
        // register our URL Generator to the class
        $this->generator = $generator;

        // might switch to a more explicit way because it will match all methods, even privates
        $methods = get_class_methods($this);

        foreach ($methods as $method) {
            $context = $this;
            $this[$method] = function($text) use ($context, $method) { return call_user_func(array($context, $method), $text); };
        }

    }

    public function link($text) 
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        if (strpos('/', $text) == 0) {
            $host = ($_SERVER['SERVER_NAME'] == API_HOST) ? API_HOST: WWW_HOST;
            $text = $protocol . $host . $text;
        }

        return $text;
    }

    public function link_to($text) 
    {
        if ($generated = $this->generator->generate($text)) {
            return $this->link($generated);
        }

        return $text;
    }
    
}