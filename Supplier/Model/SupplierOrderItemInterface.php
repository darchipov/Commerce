<?php

namespace Ekyna\Component\Commerce\Supplier\Model;

use Ekyna\Component\Resource\Model\ResourceInterface;

/**
 * Class SupplierOrderItemInterface
 * @package Ekyna\Component\Commerce\Supplier\Entity
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
interface SupplierOrderItemInterface extends ResourceInterface
{
    /**
     * Returns the supplier order.
     *
     * @return SupplierOrderInterface
     */
    public function getOrder();

    /**
     * Sets the supplier order.
     *
     * @param SupplierOrderInterface $order
     *
     * @return $this|SupplierOrderItemInterface
     */
    public function setOrder(SupplierOrderInterface $order = null);

    /**
     * Returns the designation.
     *
     * @return string
     */
    public function getDesignation();

    /**
     * Sets the designation.
     *
     * @param string $designation
     *
     * @return $this|SupplierOrderItemInterface
     */
    public function setDesignation($designation);

    /**
     * Returns the reference.
     *
     * @return string
     */
    public function getReference();

    /**
     * Sets the reference.
     *
     * @param string $reference
     *
     * @return $this|SupplierOrderItemInterface
     */
    public function setReference($reference);

    /**
     * Returns the quantity.
     *
     * @return float
     */
    public function getQuantity();

    /**
     * Sets the quantity.
     *
     * @param float $quantity
     *
     * @return $this|SupplierOrderItemInterface
     */
    public function setQuantity($quantity);

    /**
     * Returns the net price.
     *
     * @return float
     */
    public function getNetPrice();

    /**
     * Sets the net price.
     *
     * @param float $netPrice
     *
     * @return $this|SupplierOrderItemInterface
     */
    public function setNetPrice($netPrice);

    /**
     * Returns the subjectData.
     *
     * @return array
     */
    public function getSubjectData();

    /**
     * Sets the subjectData.
     *
     * @param array $subjectData
     *
     * @return $this|SupplierOrderItemInterface
     */
    public function setSubjectData(array $subjectData);

    /**
     * Returns the subject.
     *
     * @return array
     */
    public function getSubject();

    /**
     * Sets the subject.
     *
     * @param array $subject
     *
     * @return $this|SupplierOrderItemInterface
     */
    public function setSubject($subject);
}
