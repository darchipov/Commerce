<?php

namespace Ekyna\Component\Commerce\Common\View;

/**
 * Class Action
 * @package Ekyna\Component\Commerce\Common\View
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class Button
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $label;

    /**
     * @var string
     */
    private $icon;

    /**
     * @var array
     */
    private $attributes;


    /**
     * Constructor.
     *
     * @param string $path
     * @param string $label
     * @param string $icon
     * @param array  $attributes
     */
    public function __construct($path, $label, $icon, array $attributes)
    {
        $this->path = $path;
        $this->label = $label;
        $this->icon = $icon;
        $this->attributes = $attributes;
    }

    /**
     * Returns the path.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Returns the label.
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Returns the icon.
     *
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * Returns the attributes.
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }
}
