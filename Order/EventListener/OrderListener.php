<?php

namespace Ekyna\Component\Commerce\Order\EventListener;

use Ekyna\Component\Commerce\Common\EventListener\AbstractSaleListener;
use Ekyna\Component\Commerce\Common\Generator\KeyGeneratorInterface;
use Ekyna\Component\Commerce\Exception\IllegalOperationException;
use Ekyna\Component\Commerce\Exception\InvalidArgumentException;
use Ekyna\Component\Commerce\Common\Calculator\CalculatorInterface;
use Ekyna\Component\Commerce\Common\Generator\NumberGeneratorInterface;
use Ekyna\Component\Commerce\Order\Model\OrderEventInterface;
use Ekyna\Component\Commerce\Order\Model\OrderInterface;
use Ekyna\Component\Commerce\Order\Resolver\StateResolverInterface;
use Ekyna\Component\Commerce\Payment\Model\PaymentStates;
use Ekyna\Component\Commerce\Shipment\Model\ShipmentStates;
use Ekyna\Component\Resource\Event\PersistenceEvent;

/**
 * Class OrderEventSubscriber
 * @package Ekyna\Component\Commerce\Order\EventListener
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class OrderListener extends AbstractSaleListener
{
    /**
     * @var StateResolverInterface
     */
    protected $stateResolver;


    /**
     * Constructor.
     *
     * @param NumberGeneratorInterface $numberGenerator
     * @param KeyGeneratorInterface    $keyGenerator
     * @param CalculatorInterface      $calculator
     * @param StateResolverInterface   $stateResolver
     */
    public function __construct(
        NumberGeneratorInterface $numberGenerator,
        KeyGeneratorInterface $keyGenerator,
        CalculatorInterface $calculator,
        StateResolverInterface $stateResolver
    ) {
        parent::__construct($numberGenerator, $keyGenerator, $calculator);

        $this->stateResolver = $stateResolver;
    }

    /**
     * @inheritdoc
     */
    public function onInsert(PersistenceEvent $event)
    {
        $sale = $this->getSaleFromEvent($event);

        parent::onInsert($event);

        // TODO resolve states
    }

    /**
     * @inheritdoc
     */
    public function onUpdate(PersistenceEvent $event)
    {
        $sale = $this->getSaleFromEvent($event);

        parent::onUpdate($event);

        // TODO resolve states
    }

    /**
     * Pre delete event handler.
     *
     * @param OrderEventInterface $event
     *
     * @throws IllegalOperationException
     */
    public function onPreDelete(OrderEventInterface $event)
    {
        $order = $event->getOrder();

        // Stop if order has valid payments
        if (null !== $payments = $order->getPayments()) {
            $deletablePaymentStates = [PaymentStates::STATE_NEW, PaymentStates::STATE_CANCELLED];
            foreach ($payments as $payment) {
                if (!in_array($payment->getState(), $deletablePaymentStates)) {
                    throw new IllegalOperationException();
                }
            }
        }

        // Stop if order has valid shipments
        if (null !== $shipments = $order->getShipments()) {
            $deletableShipmentStates = [ShipmentStates::STATE_CHECKOUT, ShipmentStates::STATE_CANCELLED];
            foreach ($shipments as $shipment) {
                if (!in_array($shipment->getState(), $deletableShipmentStates)) {
                    throw new IllegalOperationException();
                }
            }
        }
    }

    /**
     * @inheritdoc
     */
    protected function getSaleFromEvent(PersistenceEvent $event)
    {
        $resource = $event->getResource();

        if (!$resource instanceof OrderInterface) {
            throw new InvalidArgumentException("Expected instance of OrderInterface");
        }

        return $resource;
    }
}
