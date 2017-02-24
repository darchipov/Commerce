<?php

namespace Ekyna\Component\Commerce\Stock\Model;

use Ekyna\Component\Resource\Model\ResourceInterface;

/**
 * Interface StockSubjectInterface
 * @package Ekyna\Component\Commerce\Stock\Model
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
interface StockSubjectInterface extends ResourceInterface
{
    /**
     * Returns the stock mode.
     *
     * @return string
     */
    public function getStockMode();

    /**
     * Sets the stock mode.
     *
     * @param string $mode
     *
     * @return $this|StockSubjectInterface
     */
    public function setStockMode($mode);

    /**
     * Returns the stock state.
     *
     * @return string
     */
    public function getStockState();

    /**
     * Sets the stock state.
     *
     * @param string $state
     *
     * @return $this|StockSubjectInterface
     */
    public function setStockState($state);

    /**
     * Returns the in stock quantity.
     *
     * @return float
     */
    public function getInStock();

    /**
     * Sets the in stock quantity.
     *
     * @param float $quantity
     *
     * @return $this|StockSubjectInterface
     */
    public function setInStock($quantity);

    /**
     * Returns the ordered stock quantity.
     *
     * @return float
     */
    public function getOrderedStock();

    /**
     * Sets the ordered stock quantity.
     *
     * @param float $quantity
     *
     * @return $this|StockSubjectInterface
     */
    public function setOrderedStock($quantity);

    /**
     * Returns the shipped stock quantity.
     *
     * @return float
     */
    public function getShippedStock();

    /**
     * Sets the shipped stock quantity.
     *
     * @param float $quantity
     *
     * @return $this|StockSubjectInterface
     */
    public function setShippedStock($quantity);

    /**
     * Returns the estimated date of arrival.
     *
     * @return \DateTime
     */
    public function getEstimatedDateOfArrival();

    /**
     * Sets the estimated date of arrival.
     *
     * @param \DateTime $eta
     *
     * @return $this|StockSubjectInterface
     */
    public function setEstimatedDateOfArrival(\DateTime $eta);

    /**
     * Returns the stock unit class.
     *
     * @return string
     */
    public static function getStockUnitClass();
}
