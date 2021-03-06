<?php

namespace Ekyna\Component\Commerce\Stock\Util;

use Ekyna\Component\Commerce\Stock\Model\StockUnitInterface;

/**
 * Class StockUtil
 * @package Ekyna\Component\Commerce\Stock\Util
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
final class StockUtil
{
    /**
     * Calculates the "in" stock quantity.
     *
     * (delivered - reserved, 0 or greater)
     *
     * @param float $deliveredQty
     * @param float $reservedQty
     *
     * @return float
     */
    static public function calculateInStock($deliveredQty, $reservedQty)
    {
        $result = $deliveredQty - $reservedQty;

        return 0 < $result ? $result : 0;
    }

    /**
     * Calculates the "virtual" stock quantity.
     *
     * (ordered - max(delivered or reserved), 0 or greater)
     *
     * @param float $orderedQty
     * @param float $deliveredQty
     * @param float $reservedQty
     *
     * @return float
     */
    static public function calculateVirtualStock($orderedQty, $deliveredQty, $reservedQty)
    {
        $result = $orderedQty - max($deliveredQty, $reservedQty);

        return 0 < $result ? $result : 0;
    }

    /**
     * Calculates the "reservable" stock quantity.
     *
     * (ordered - reserved, 0 or greater)
     *
     * @param float $orderedQty
     * @param float $reservedQty
     *
     * @return float
     */
    static public function calculateReservable($orderedQty, $reservedQty)
    {
        if (0 == $orderedQty) {
            return INF;
        }

        $result = $orderedQty - $reservedQty;

        return 0 < $result ? $result : 0;
    }

    /**
     * Calculates the "shippable" stock quantity.
     *
     *  (min(delivered or reserved) - shipped, 0 or greater)
     *
     * @param float $deliveredQty
     * @param float $reservedQty
     * @param float $shippedQty
     *
     * @return float
     */
    static public function calculateShippable($deliveredQty, $reservedQty, $shippedQty)
    {
        $result = min($deliveredQty, $reservedQty) - $shippedQty;

        return 0 < $result ? $result : 0;
    }

    /**
     * Returns whether or not the given stock unit can be safely deleted.
     *
     * @param StockUnitInterface $stockUnit
     *
     * @return bool
     */
    static public function isDeletableStockUnit(StockUnitInterface $stockUnit)
    {
        if (
            0 < $stockUnit->getDeliveredQuantity() ||
            0 < $stockUnit->getReservedQuantity() ||
            0 < $stockUnit->getShippedQuantity()
        ) {
            return false;
        }

        return true;
    }
}
