<?php

namespace Den\BootstrapElements;

abstract class AbstractWidget
{
    public $options = null;
    public function __construct($options = null)
    {
        $this->options = is_array($options) ? $options : array();
    }
    abstract public function toHtml();
    /**
     * @return static
     */
    public static function newObj($options = null)
    {
        return (new static($options));
    }
    public static function render($options)
    {
        return (new static($options))->toHtml();
    }
}

?>