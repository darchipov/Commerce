<?php

namespace Ekyna\Component\Commerce\Common\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Ekyna\Component\Commerce\Common\Model;
use Ekyna\Component\Commerce\Customer\Model\CustomerGroupInterface;
use Ekyna\Component\Commerce\Customer\Model\CustomerInterface;
use Ekyna\Component\Commerce\Exception\RuntimeException;
use Ekyna\Component\Commerce\Payment\Model as Payment;
use Ekyna\Component\Commerce\Shipment\Model as Shipment;
use Ekyna\Component\Resource\Model\TimestampableTrait;

/**
 * Class AbstractSale
 * @package Ekyna\Component\Commerce\Common\Entity
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
abstract class AbstractSale extends AbstractAdjustable implements Model\SaleInterface
{
    use Model\IdentityTrait,
        TimestampableTrait;

    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $key;

    /**
     * @var string
     */
    protected $number;

    /**
     * @var CustomerInterface
     */
    protected $customer;

    /**
     * @var CustomerGroupInterface
     */
    protected $customerGroup;

    /**
     * @var string
     */
    protected $company;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var Model\AddressInterface
     */
    protected $invoiceAddress;

    /**
     * @var Model\AddressInterface
     */
    protected $deliveryAddress;

    /**
     * @var bool
     */
    protected $sameAddress;

    /**
     * @var Shipment\ShipmentMethodInterface
     * TODO rename to shipmentMethod
     */
    protected $preferredShipmentMethod;

    /**
     * @var bool
     */
    protected $taxExempt;

    /**
     * @var Model\CurrencyInterface
     */
    protected $currency;

    /**
     * @var float
     */
    protected $weightTotal;

    /**
     * @var float
     */
    protected $netTotal;

    /**
     * @var float
     */
    protected $adjustmentTotal;

    /**
     * @var float
     */
    protected $shipmentAmount;

    /**
     * @var float
     */
    protected $grandTotal;

    /**
     * @var float
     */
    protected $paidTotal;

    /**
     * @var string
     */
    protected $state;

    /**
     * @var string
     */
    protected $paymentState;

    /**
     * @var ArrayCollection|Model\SaleAttachmentInterface[]
     */
    protected $attachments;

    /**
     * @var ArrayCollection|Model\SaleItemInterface[]
     */
    protected $items;

    /**
     * @var ArrayCollection|Payment\PaymentInterface[]
     */
    protected $payments;


    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();

        if (null === $this->state) {
            throw new RuntimeException("Initial state must be defined.");
        }

        $this->sameAddress = true;
        $this->taxExempt = false;

        $this->weightTotal = 0;
        $this->netTotal = 0;
        $this->adjustmentTotal = 0;
        $this->grandTotal = 0;
        $this->paidTotal = 0;

        $this->paymentState = Payment\PaymentStates::STATE_NEW;

        $this->attachments = new ArrayCollection();
        $this->items = new ArrayCollection();
        $this->payments = new ArrayCollection();
    }

    /**
     * Returns the string representation.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getNumber();
    }

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
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @inheritdoc
     */
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @inheritdoc
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @inheritdoc
     */
    public function setCustomer(CustomerInterface $customer = null)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getCustomerGroup()
    {
        return $this->customerGroup;
    }

    /**
     * @inheritdoc
     */
    public function setCustomerGroup(CustomerGroupInterface $customerGroup = null)
    {
        $this->customerGroup = $customerGroup;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @inheritdoc
     */
    public function setCompany($company)
    {
        $this->company = $company;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @inheritdoc
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function isSameAddress()
    {
        return $this->sameAddress;
    }

    /**
     * @inheritdoc
     */
    public function setSameAddress($sameAddress)
    {
        $this->sameAddress = (bool)$sameAddress;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getPreferredShipmentMethod()
    {
        return $this->preferredShipmentMethod;
    }

    /**
     * @inheritdoc
     */
    public function setPreferredShipmentMethod(Shipment\ShipmentMethodInterface $method = null)
    {
        $this->preferredShipmentMethod = $method;

        return $this;
    }

    /**
     * Returns whether the sale is tax exempt.
     *
     * @return boolean
     */
    public function isTaxExempt()
    {
        return $this->taxExempt;
    }

    /**
     * Sets the tax exempt.
     *
     * @param boolean $exempt
     *
     * @return AbstractSale
     */
    public function setTaxExempt($exempt)
    {
        $this->taxExempt = $exempt;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @inheritdoc
     */
    public function setCurrency(Model\CurrencyInterface $currency)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getWeightTotal()
    {
        return $this->weightTotal;
    }

    /**
     * @inheritdoc
     */
    public function setWeightTotal($total)
    {
        $this->weightTotal = $total;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getNetTotal()
    {
        return $this->netTotal;
    }

    /**
     * @inheritdoc
     */
    public function setNetTotal($total)
    {
        $this->netTotal = $total;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getAdjustmentTotal()
    {
        return $this->adjustmentTotal;
    }

    /**
     * @inheritdoc
     */
    public function setAdjustmentTotal($total)
    {
        $this->adjustmentTotal = $total;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getShipmentAmount()
    {
        return $this->shipmentAmount;
    }

    /**
     * @inheritdoc
     */
    public function setShipmentAmount($amount)
    {
        $this->shipmentAmount = $amount;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getGrandTotal()
    {
        return $this->grandTotal;
    }

    /**
     * @inheritdoc
     */
    public function setGrandTotal($total)
    {
        $this->grandTotal = $total;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getPaidTotal()
    {
        return $this->paidTotal;
    }

    /**
     * @inheritdoc
     */
    public function setPaidTotal($total)
    {
        $this->paidTotal = $total;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * {@inheritdoc}
     */
    public function setPaymentState($state)
    {
        $this->paymentState = $state;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getPaymentState()
    {
        return $this->paymentState;
    }

    /**
     * @inheritdoc
     */
    public function hasAttachments()
    {
        return 0 < $this->attachments->count();
    }

    /**
     * @inheritdoc
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * @inheritdoc
     */
    public function hasItems()
    {
        return 0 < $this->items->count();
    }

    /**
     * @inheritdoc
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @inheritdoc
     */
    public function hasPayments()
    {
        return 0 < $this->payments->count();
    }

    /**
     * @inheritdoc
     */
    public function getPayments()
    {
        return $this->payments;
    }

    /**
     * @inheritdoc
     */
    public function requiresShipment()
    {
        foreach ($this->items as $item) {
            if (0 < $item->getWeight()) {
                return true;
            }

            foreach ($item->getChildren() as $child) {
                if (0 < $child->getWeight()) {
                    return true;
                }
            }
        }

        return false;
    }
}
