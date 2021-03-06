<?php

namespace Ekyna\Component\Commerce\Stock\Model;

use Ekyna\Component\Commerce\Common\Model\SaleItemInterface;
use Ekyna\Component\Resource\Model\ResourceInterface;

/**
 * Interface StockAssignmentInterface
 * @package Ekyna\Component\Commerce\Stock\Model
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
interface StockAssignmentInterface extends ResourceInterface
{
    /**
     * Returns the stock unit.
     *
     * @return StockUnitInterface
     */
    public function getStockUnit();

    /**
     * Sets the stock unit.
     *
     * @param StockUnitInterface $stockUnit
     *
     * @return $this|StockAssignmentInterface
     */
    public function setStockUnit(StockUnitInterface $stockUnit = null);

    /**
     * Returns the sale item.
     *
     * @return SaleItemInterface
     */
    public function getSaleItem();

    /**
     * Sets the sale item.
     *
     * @param SaleItemInterface $saleItem
     *
     * @return $this|StockAssignmentInterface
     */
    public function setSaleItem(SaleItemInterface $saleItem = null);

    /**
     * Returns the reserved quantity.
     *
     * @return float
     */
    public function getReservedQuantity();

    /**
     * Sets the reserved quantity.
     *
     * @param float $quantity
     *
     * @return $this|StockAssignmentInterface
     */
    public function setReservedQuantity($quantity);

    /**
     * Returns the shipped quantity.
     *
     * @return float
     */
    public function getShippedQuantity();

    /**
     * Sets the shipped quantity.
     *
     * @param float $quantity
     *
     * @return $this|StockAssignmentInterface
     */
    public function setShippedQuantity($quantity);

    /**
     * Returns the shippable quantity.
     *
     * @return float
     */
    public function getShippableQuantity();
}
