<?php

namespace Solar;

use \Solar\ViewInterface;

/**
 * @brief       Nestable view container with rendering callback.
 * @author      Gigablah <gigablah@vgmdb.net>
 */
class View extends \ArrayObject implements ViewInterface
{
    public $template;
    private $render_callback;

    /**
     * Create a new view instance, with optional callback for render().
     *
     * @param string   $template
     * @param array    $data
     * @param \Closure $callback
     * @return void
     */
    public function __construct($template, array $data = array(), \Closure $callback = null)
    {
        $this->template = $template;
        if (!$callback) {
            $callback = function ($view) {
                return vsprintf($view->template, $view);
            };
        }
        $this->render_callback = $callback;
        parent::__construct($data);
    }

    /**
     * Convenience static method for creating a new view instance.
     *
     * @param mixed    $template
     * @param array    $data
     * @param \Closure $callback
     * @return View
     */
    static public function create($template, array $data = array(), \Closure $callback = null)
    {
        if ($template instanceof ViewInterface) {
            return $template;
        }

        return new static($template, $data, $callback);
    }

    /**
     * Initialize template data.
     *
     * @param array $data
     * @return View
     */
    public function with($data = array())
    {
        foreach ($data as $key => $value) {
            $this[$key] = $value;
        }

        return $this;
    }

    /**
     * Insert a view object as a data element.
     *
     * @param string $key
     * @param mixed  $view
     * @return View
     */
    public function nest($key, $view)
    {
        if (!($view instanceof ViewInterface)) {
            $view = new View((string) $view);
        }

        $this[$key] = $view;

        return $this;
    }

    /**
     * Get the evaluated string content of the view.
     *
     * @param array $data
     * @return string
     */
    public function render($data = array())
    {
        $render = $this->with($data)->render_callback;

        return $render($this);
    }

    /**
     * Get the evaluated string content of the view, magically.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }

   /**
     * Gets a parameter or an object.
     *
     * @param  string $id The unique identifier for the parameter or object
     * @return mixed The value of the parameter or an object
     * @throws \InvalidArgumentException if the identifier is not defined
     */
    function offsetGet($id)
    {
        if (!$this->offsetExists($id)) {
            throw new \InvalidArgumentException(sprintf('Identifier "%s" is not defined.', $id));
        }

        $value = parent::offsetGet($id);

        return $value instanceof \Closure ? $value($this) : $value;
    }
}