<?php

namespace Ekyna\Component\Commerce\Supplier\EventListener;

use Ekyna\Component\Commerce\Exception\IllegalOperationException;
use Ekyna\Component\Commerce\Exception\InvalidArgumentException;
use Ekyna\Component\Commerce\Exception\RuntimeException;
use Ekyna\Component\Commerce\Supplier\Model\SupplierDeliveryItemInterface;
use Ekyna\Component\Resource\Event\ResourceEventInterface;

/**
 * Class SupplierDeliveryItemListener
 * @package Ekyna\Component\Commerce\Supplier\EventListener
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class SupplierDeliveryItemListener extends AbstractListener
{
    // TODO refactor assertions : if (null === ...
    // TODO assert that order is at least at ordered state (in delivery listener too ...)

    /**
     * Insert event handler.
     *
     * @param ResourceEventInterface $event
     */
    public function onInsert(ResourceEventInterface $event)
    {
        $item = $this->getSupplierDeliveryItemFromEvent($event);

        // Credit stock unit delivered quantity
        if (null === $orderItem = $item->getOrderItem()) {
            throw new RuntimeException("OrderItem must be set.");
        }
        if (null === $stockUnit = $orderItem->getStockUnit()) {
            throw new RuntimeException("StockUnit must be set.");
        }
        $this->stockUnitUpdater->updateDelivered($stockUnit, $item->getQuantity(), true);

        // Dispatch supplier order content change event
        if (null === $order = $orderItem->getOrder()) {
            throw new RuntimeException("Order must be set.");
        }
        $this->scheduleSupplierOrderContentChangeEvent($order);
    }

    /**
     * Update event handler.
     *
     * @param ResourceEventInterface $event
     */
    public function onUpdate(ResourceEventInterface $event)
    {
        $item = $this->getSupplierDeliveryItemFromEvent($event);

        if ($this->persistenceHelper->isChanged($item, ['quantity'])) {
            $this->handleQuantityChange($item);

            // Dispatch supplier order content change event
            if (null === $orderItem = $item->getOrderItem()) {
                throw new RuntimeException("OrderItem must be set.");
            }
            if (null === $order = $orderItem->getOrder()) {
                throw new RuntimeException("Order must be set.");
            }
            $this->scheduleSupplierOrderContentChangeEvent($order);

            // Remove item with zero quantity without event schedule
            if (0 == $item->getQuantity()) {
                $this->persistenceHelper->remove($item, false);
            }
        }
    }

    /**
     * Delete event handler.
     *
     * @param ResourceEventInterface $event
     */
    public function onDelete(ResourceEventInterface $event)
    {
        $item = $this->getSupplierDeliveryItemFromEvent($event);

        $this->assertDeletable($item);

        if ($this->persistenceHelper->isChanged($item, ['quantity'])) {
            $this->handleQuantityChange($item);
        } else {
            // Debit stock unit delivered quantity
            if (null === $orderItem = $item->getOrderItem()) {
                throw new RuntimeException("OrderItem must be set.");
            }
            if (null === $stockUnit = $orderItem->getStockUnit()) {
                throw new RuntimeException("StockUnit must be set.");
            }
            $this->stockUnitUpdater->updateDelivered($stockUnit, -$item->getQuantity(), true);

            // Trigger the supplier order update
            if (null === $order = $orderItem->getOrder()) {
                throw new RuntimeException("Order must be set.");
            }
            $this->scheduleSupplierOrderContentChangeEvent($order);
        }

        // Clear association
        $item->setDelivery(null);
    }

    /**
     * Handle the quantity change.
     *
     * @param SupplierDeliveryItemInterface $item
     */
    protected function handleQuantityChange(SupplierDeliveryItemInterface $item)
    {
        $changeSet = $this->persistenceHelper->getChangeSet($item);

        // Delta quantity (difference between new and old)
        if (null === $orderItem = $item->getOrderItem()) {
            throw new RuntimeException("OrderItem must be set.");
        }
        if (null === $stockUnit = $orderItem->getStockUnit()) {
            throw new RuntimeException("StockUnit must be set.");
        }
        if (0 != $deltaQuantity = floatval($changeSet['quantity'][1]) - floatval($changeSet['quantity'][0])) {
            // Update stock unit delivered quantity
            $this->stockUnitUpdater->updateDelivered($stockUnit, $deltaQuantity, true);
        }

        // Trigger the supplier order update
        if (null === $order = $orderItem->getOrder()) {
            throw new RuntimeException("Order must be set.");
        }
        $this->scheduleSupplierOrderContentChangeEvent($order);
    }

    /**
     * Pre delete event handler.
     *
     * @param ResourceEventInterface $event
     *
     * @throws IllegalOperationException
     */
    public function onPreDelete(ResourceEventInterface $event)
    {
        $item = $this->getSupplierDeliveryItemFromEvent($event);

        $this->assertDeletable($item);

        // Initialize the supplier deliveries's items collection before the item removal.
        if (null !== $delivery = $item->getDelivery()) {
            $delivery->getItems();
        }
    }

    /**
     * Returns the supplier delivery item from the event.
     *
     * @param ResourceEventInterface $event
     *
     * @return SupplierDeliveryItemInterface
     * @throws InvalidArgumentException
     */
    protected function getSupplierDeliveryItemFromEvent(ResourceEventInterface $event)
    {
        $item = $event->getResource();

        if (!$item instanceof SupplierDeliveryItemInterface) {
            throw new InvalidArgumentException("Expected instance of SupplierDeliveryItemInterface.");
        }

        return $item;
    }
}
