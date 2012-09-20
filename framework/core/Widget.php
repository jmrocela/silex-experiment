<?php

namespace VGMdb;

/**
 * @brief       Widgets are mini-controllers with templating capabilities.
 * @author      Gigablah <gigablah@vgmdb.net>
 */
class Widget extends \ArrayObject implements ViewInterface
{
    private $view;
    private $callback;

    /**
     * Create a new widget instance.
     *
     * @param ViewInterface $view
     * @param \Closure      $callback
     * @return void
     */
    public function __construct(ViewInterface $view, $callback = null)
    {
        $this->view = $view;
        $this->callback = $callback;
    }

    /**
     * Initialize widget data.
     *
     * @param array $data
     * @return Widget
     */
    public function with($data = array())
    {
        foreach ($data as $key => $value) {
            $this[$key] = $value;
        }

        return $this;
    }

    /**
     * Insert another view as a data element.
     *
     * @param string $key
     * @param mixed  $view
     * @return Widget
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
     * Get the evaluated string content of the widget.
     *
     * @param array $data
     * @return string
     */
    public function render($data = array())
    {
        if ($callback = $this->callback) {
            $this->with($callback($this));
        }

        return $this->view->render($this);
    }

    /**
     * Get the evaluated string content of the widget, magically.
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