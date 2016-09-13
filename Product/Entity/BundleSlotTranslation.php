<?php

namespace Ekyna\Component\Commerce\Product\Entity;

use Ekyna\Component\Commerce\Product\Model\BundleSlotTranslationInterface;
use Ekyna\Component\Resource\Model\AbstractTranslation;

/**
 * Class BundleSlotTranslation
 * @package Ekyna\Component\Commerce\Product\Entity
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class BundleSlotTranslation extends AbstractTranslation implements BundleSlotTranslationInterface
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $description;


    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @inheritdoc
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @inheritdoc
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }
}
