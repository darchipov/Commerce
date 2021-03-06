<?php

namespace Ekyna\Component\Commerce\Order\EventListener;

use Ekyna\Component\Commerce\Common\EventListener\AbstractSaleItemListener;
use Ekyna\Component\Commerce\Common\Model;
use Ekyna\Component\Commerce\Exception\IllegalOperationException;
use Ekyna\Component\Commerce\Exception\InvalidArgumentException;
use Ekyna\Component\Commerce\Exception\RuntimeException;
use Ekyna\Component\Commerce\Order\Event\OrderEvents;
use Ekyna\Component\Commerce\Order\Model\OrderItemInterface;
use Ekyna\Component\Commerce\Order\Model\OrderStates;
use Ekyna\Component\Commerce\Stock\Assigner\StockUnitAssignerInterface;
use Ekyna\Component\Resource\Event\ResourceEventInterface;

/**
 * Class OrderItemListener
 * @package Ekyna\Component\Commerce\Order\EventListener
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class OrderItemListener extends AbstractSaleItemListener
{
    /**
     * @var StockUnitAssignerInterface
     */
    private $stockAssigner;


    /**
     * Sets the stock assigner.
     *
     * @param StockUnitAssignerInterface $stockAssigner
     */
    public function setStockAssigner(StockUnitAssignerInterface $stockAssigner)
    {
        $this->stockAssigner = $stockAssigner;
    }

    /**
     * @inheritdoc
     */
    public function onInsert(ResourceEventInterface $event)
    {
        parent::onInsert($event);

        $item = $this->getSaleItemFromEvent($event);

        // If order is in stockable state
        if (OrderStates::isStockableState($item->getSale()->getState())) {
            $this->stockAssigner->assignSaleItem($item);
        } else {
            $this->stockAssigner->detachSaleItem($item);
        }
    }

    /**
     * @inheritdoc
     */
    public function onUpdate(ResourceEventInterface $event)
    {
        parent::onUpdate($event);

        $item = $this->getSaleItemFromEvent($event);

        $doApply = true;
        $sale = $item->getSale();
        if ($this->persistenceHelper->isChanged($sale, 'state')) {
            $stateCs = $this->persistenceHelper->getChangeSet($sale, 'state');

            // If order just did a stockable state transition
            if (
                OrderStates::hasChangedToStockable($stateCs) ||
                OrderStates::hasChangedFromStockable($stateCs)
            ) {
                // Prevent assignments update (handled by the order listener)
                $doApply = false;
            }
        }

        // If order is in stockable state and order item quantity has changed
        if ($doApply && OrderStates::isStockableState($sale->getState())) {
            if ($this->persistenceHelper->isChanged($item, 'quantity')) {
                $this->applySaleItemRecursively($item);
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function onDelete(ResourceEventInterface $event)
    {
        parent::onDelete($event);

        $item = $this->getSaleItemFromEvent($event);

        if (null === $sale = $this->getSaleFromItem($item)) {
            throw new RuntimeException('Failed to retrieve the sale.');
        }

        // If order is in stockable state
        // TODO Or order was in stockable state (watch state change set) ?
        if (OrderStates::isStockableState($sale->getState())) {
            $this->stockAssigner->detachSaleItem($item);
        }
    }

    /**
     * Pre create event handler.
     *
     * @param ResourceEventInterface $event
     *
     * @throws IllegalOperationException
     */
    public function onPreCreate(ResourceEventInterface $event)
    {
        if ($event->getHard()) {
            return;
        }

        //$this->throwIllegalOperationIfOrderIsCompleted($event);
    }

    /**
     * Pre update event handler.
     *
     * @param ResourceEventInterface $event
     *
     * @throws IllegalOperationException
     */
    public function onPreUpdate(ResourceEventInterface $event)
    {
        if ($event->getHard()) {
            return;
        }

        parent::onPreUpdate($event);

        //$this->throwIllegalOperationIfOrderIsCompleted($event);
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
        if ($event->getHard()) {
            return;
        }

        parent::onPreDelete($event);

        //$this->throwIllegalOperationIfOrderIsCompleted($event);
    }

    /**
     * Applies the sale item to stock units recursively.
     *
     * @param Model\SaleItemInterface $item
     */
    protected function applySaleItemRecursively(Model\SaleItemInterface $item)
    {
        $this->stockAssigner->applySaleItem($item);

        foreach ($item->getChildren() as $child) {
            if (
                $this->persistenceHelper->isScheduledForInsert($child) ||
                $this->persistenceHelper->isScheduledForUpdate($child)
            ) {
                // Skip this item as the listener will be called on it.
                continue;
            }

            $this->applySaleItemRecursively($child);
        }
    }

    /**
     * Throws an illegal operation exception if the order is completed.
     *
     * @param ResourceEventInterface $event
     *
     * @throws IllegalOperationException
     */
//    private function throwIllegalOperationIfOrderIsCompleted(ResourceEventInterface $event)
//    {
//        $item = $this->getSaleItemFromEvent($event);
//        /** @var \Ekyna\Component\Commerce\Order\Model\OrderInterface $order */
//        $order = $item->getSale();
//
//        // Stop sale is completed.
//        if ($order->getState() === OrderStates::STATE_COMPLETED) {
//            throw new IllegalOperationException(); // TODO reason message
//        }
//    }

    /**
     * @inheritdoc
     */
    protected function getSalePropertyPath()
    {
        return 'order';
    }

    /**
     * @inheritdoc
     */
    protected function scheduleSaleContentChangeEvent(Model\SaleInterface $sale)
    {
        $this->persistenceHelper->scheduleEvent(OrderEvents::CONTENT_CHANGE, $sale);
    }

    /**
     * @inheritdoc
     */
    protected function getSaleItemFromEvent(ResourceEventInterface $event)
    {
        $item = $event->getResource();

        if (!$item instanceof OrderItemInterface) {
            throw new InvalidArgumentException("Expected instance of OrderItemInterface");
        }

        return $item;
    }
}
