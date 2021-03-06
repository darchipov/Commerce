<?php

namespace Ekyna\Component\Commerce\Shipment\Entity;

use Ekyna\Component\Commerce\Shipment\Model;

/**
 * Class ShipmentItem
 * @package Ekyna\Component\Commerce\Shipment\Entity
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
abstract class AbstractShipmentItem implements Model\ShipmentItemInterface
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var Model\ShipmentInterface
     */
    protected $shipment;

    /**
     * @var float
     */
    protected $quantity = 0;


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
    public function getShipment()
    {
        return $this->shipment;
    }

    /**
     * @inheritdoc
     */
    public function setShipment(Model\ShipmentInterface $shipment = null)
    {
        if ($this->shipment !== $shipment) {
            $previous = $this->shipment;
            $this->shipment = $shipment;

            if ($previous) {
                $previous->removeItem($this);
            }

            if ($this->shipment) {
                $this->shipment->addItem($this);
            }
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @inheritdoc
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }
}
