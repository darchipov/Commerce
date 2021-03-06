<?php

namespace Ekyna\Component\Commerce\Order\Entity;

use Ekyna\Component\Commerce\Common\Entity\AbstractAdjustment;
use Ekyna\Component\Commerce\Order\Model\OrderItemAdjustmentInterface;
use Ekyna\Component\Commerce\Order\Model\OrderItemInterface;

/**
 * Class OrderItemAdjustment
 * @package Ekyna\Component\Commerce\Order\Entity
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class OrderItemAdjustment extends AbstractAdjustment implements OrderItemAdjustmentInterface
{
    /**
     * @var OrderItemInterface
     */
    protected $item;


    /**
     * @inheritdoc
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * @inheritdoc
     */
    public function setItem(OrderItemInterface $item = null)
    {
        if ($item !== $this->item) {
            $previous = $this->item;
            $this->item = $item;

            if ($previous) {
                $previous->removeAdjustment($this);
            }

            if ($this->item) {
                $this->item->addAdjustment($this);
            }
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getAdjustable()
    {
        return $this->item;
    }
}
