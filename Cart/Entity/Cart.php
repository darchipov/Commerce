<?php

namespace Ekyna\Component\Commerce\Cart\Entity;

use Ekyna\Component\Commerce\Common\Entity\AbstractSale;
use Ekyna\Component\Commerce\Common\Model as Common;
use Ekyna\Component\Commerce\Exception\InvalidArgumentException;
use Ekyna\Component\Commerce\Cart\Model;
use Ekyna\Component\Commerce\Payment\Model as Payment;

/**
 * Class Cart
 * @package Ekyna\Component\Commerce\Cart\Entity
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class Cart extends AbstractSale implements Model\CartInterface
{
    /**
     * @var \DateTime
     */
    protected $expiresAt;


    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->state = Model\CartStates::STATE_NEW;

        parent::__construct();
    }

    /**
     * @inheritdoc
     */
    public function hasItem(Common\SaleItemInterface $item)
    {
        if (!$item instanceof Model\CartItemInterface) {
            throw new InvalidArgumentException("Expected instance of CartItemInterface.");
        }

        return $this->items->contains($item);
    }

    /**
     * @inheritdoc
     */
    public function addItem(Common\SaleItemInterface $item)
    {
        if (!$item instanceof Model\CartItemInterface) {
            throw new InvalidArgumentException("Expected instance of CartItemInterface.");
        }

        if (!$this->hasItem($item)) {
            $item->setCart($this);
            $this->items->add($item);
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function removeItem(Common\SaleItemInterface $item)
    {
        if (!$item instanceof Model\CartItemInterface) {
            throw new InvalidArgumentException("Expected instance of CartItemInterface.");
        }

        if ($this->hasItem($item)) {
            $item->setCart(null);
            $this->items->removeElement($item);
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function hasAdjustment(Common\AdjustmentInterface $adjustment)
    {
        if (!$adjustment instanceof Model\CartAdjustmentInterface) {
            throw new InvalidArgumentException("Expected instance of CartAdjustmentInterface.");
        }

        return $this->adjustments->contains($adjustment);
    }

    /**
     * @inheritdoc
     */
    public function addAdjustment(Common\AdjustmentInterface $adjustment)
    {
        if (!$adjustment instanceof Model\CartAdjustmentInterface) {
            throw new InvalidArgumentException("Expected instance of CartAdjustmentInterface.");
        }

        if (!$this->hasAdjustment($adjustment)) {
            $adjustment->setCart($this);
            $this->adjustments->add($adjustment);
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function removeAdjustment(Common\AdjustmentInterface $adjustment)
    {
        if (!$adjustment instanceof Model\CartAdjustmentInterface) {
            throw new InvalidArgumentException("Expected instance of CartAdjustmentInterface.");
        }

        if ($this->hasAdjustment($adjustment)) {
            $adjustment->setCart(null);
            $this->adjustments->removeElement($adjustment);
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function hasPayment(Payment\PaymentInterface $payment)
    {
        if (!$payment instanceof Model\CartPaymentInterface) {
            throw new InvalidArgumentException("Expected instance of CartPaymentInterface.");
        }

        return $this->payments->contains($payment);
    }

    /**
     * @inheritdoc
     */
    public function addPayment(Payment\PaymentInterface $payment)
    {
        if (!$payment instanceof Model\CartPaymentInterface) {
            throw new InvalidArgumentException("Expected instance of CartPaymentInterface.");
        }

        if (!$this->hasPayment($payment)) {
            $payment->setCart($this);
            $this->payments->add($payment);
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function removePayment(Payment\PaymentInterface $payment)
    {
        if (!$payment instanceof Model\CartPaymentInterface) {
            throw new InvalidArgumentException("Expected instance of CartPaymentInterface.");
        }

        if ($this->hasPayment($payment)) {
            $payment->setCart(null);
            $this->payments->removeElement($payment);
        }

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getExpiresAt()
    {
        return $this->expiresAt;
    }

    /**
     * @inheritdoc
     */
    public function setExpiresAt(\DateTime $expiresAt = null)
    {
        $this->expiresAt = $expiresAt;

        return $this;
    }

    /**
     * @inheritdoc
     */
    protected function validateAddressClass(Common\AddressInterface $address)
    {
        if (!$address instanceof Model\CartAddressInterface) {
            throw new InvalidArgumentException('Expected instance of CartAddressInterface.');
        }
    }
}