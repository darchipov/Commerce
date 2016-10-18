<?php

namespace Ekyna\Component\Commerce\Common\Factory;

use Ekyna\Component\Commerce\Common\Model;
use Ekyna\Component\Commerce\Payment\Model as Payment;
use Ekyna\Component\Commerce\Shipment\Model as Shipment;

/**
 * Interface SaleFactoryInterface
 * @package Ekyna\Component\Commerce\Common
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
interface SaleFactoryInterface
{
    /**
     * Creates an address regarding to the sale type.
     *
     * @param Model\SaleInterface $sale
     *
     * @return Model\AddressInterface
     */
    public function createAddressForSale(Model\SaleInterface $sale);

    /**
     * Creates a sale item regarding to the sale type.
     *
     * @param Model\SaleInterface $sale
     *
     * @return Model\SaleItemInterface
     */
    public function createItemForSale(Model\SaleInterface $sale);

    /**
     * Creates an adjustment regarding to the sale type.
     *
     * @param Model\SaleInterface $sale
     *
     * @return Model\AdjustmentInterface
     */
    public function createAdjustmentForSale(Model\SaleInterface $sale);

    /**
     * Creates an adjustment regarding to the sale item type.
     *
     * @param Model\SaleItemInterface $item
     *
     * @return Model\AdjustmentInterface
     */
    public function createAdjustmentForItem(Model\SaleItemInterface $item);

    /**
     * Creates an address regarding to the sale type.
     *
     * @param Model\SaleInterface $sale
     *
     * @return Payment\PaymentInterface
     */
    public function createPaymentForSale(Model\SaleInterface $sale);

    /**
     * Creates a shipment regarding to the sale type.
     *
     * @param Model\SaleInterface $sale
     *
     * @return Shipment\ShipmentInterface
     */
    public function createShipmentForSale(Model\SaleInterface $sale);

    /**
     * Creates a shipment regarding to the sale type.
     *
     * @param Shipment\ShipmentInterface $shipment
     *
     * @return Shipment\ShipmentItemInterface
     */
    public function createItemForShipment(Shipment\ShipmentInterface $shipment);
}