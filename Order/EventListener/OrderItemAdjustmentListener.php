<?php

namespace Ekyna\Component\Commerce\Order\EventListener;

use Ekyna\Component\Commerce\Common\EventListener\AbstractAdjustmentListener;
use Ekyna\Component\Commerce\Common\Model;
use Ekyna\Component\Commerce\Exception\IllegalOperationException;
use Ekyna\Component\Commerce\Exception\InvalidArgumentException;
use Ekyna\Component\Commerce\Order\Event\OrderEvents;
use Ekyna\Component\Commerce\Order\Model\OrderItemAdjustmentInterface;
use Ekyna\Component\Commerce\Order\Model\OrderStates;
use Ekyna\Component\Resource\Event\ResourceEventInterface;

/**
 * Class OrderItemAdjustmentListener
 * @package Ekyna\Component\Commerce\Order\EventListener
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class OrderItemAdjustmentListener extends AbstractAdjustmentListener
{
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
     * Throws an illegal operation exception if the order is completed.
     *
     * @param ResourceEventInterface $event
     *
     * @throws IllegalOperationException
     */
    private function throwIllegalOperationIfOrderIsCompleted(ResourceEventInterface $event)
    {
        $item = $this->getAdjustmentFromEvent($event);
        /** @var \Ekyna\Component\Commerce\Order\Model\OrderInterface $order */
        $order = $item->getAdjustable();

        // Stop sale is completed.
        if ($order->getState() === OrderStates::STATE_COMPLETED) {
            throw new IllegalOperationException(); // TODO reason message
        }
    }

    /**
     * @inheritdoc
     */
    protected function scheduleSaleContentChangeEvent(Model\AdjustmentInterface $adjustment)
    {
        /** @var \Ekyna\Component\Commerce\Order\Model\OrderItemInterface $item */
        $item = $adjustment->getAdjustable();

        $this->persistenceHelper->scheduleEvent(OrderEvents::CONTENT_CHANGE, $item->getSale());
    }

    /**
     * @inheritdoc
     */
    protected function getAdjustmentFromEvent(ResourceEventInterface $event)
    {
        $adjustment = $event->getResource();

        if (!$adjustment instanceof OrderItemAdjustmentInterface) {
            throw new InvalidArgumentException("Expected instance of OrderItemAdjustmentInterface");
        }

        return $adjustment;
    }
}
