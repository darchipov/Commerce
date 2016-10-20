<?php

namespace Ekyna\Component\Commerce\Quote\EventListener;

use Ekyna\Component\Commerce\Common\Model\SaleInterface;
use Ekyna\Component\Commerce\Exception\InvalidArgumentException;
use Ekyna\Component\Commerce\Quote\Event\QuoteEvents;
use Ekyna\Component\Commerce\Payment\EventListener\AbstractPaymentListener;
use Ekyna\Component\Commerce\Quote\Model\QuotePaymentInterface;
use Ekyna\Component\Resource\Event\ResourceEventInterface;

/**
 * Class QuotePaymentListener
 * @package Ekyna\Component\Commerce\Quote\EventListener
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class QuotePaymentListener extends AbstractPaymentListener
{
    /**
     * @inheritdoc
     */
    protected function dispatchSaleContentChangeEvent(SaleInterface $sale)
    {
        $event = $this->dispatcher->createResourceEvent($sale);

        $this->dispatcher->dispatch(QuoteEvents::CONTENT_CHANGE, $event);
    }

    /**
     * @inheritdoc
     */
    protected function getPaymentFromEvent(ResourceEventInterface $event)
    {
        $resource = $event->getResource();

        if (!$resource instanceof QuotePaymentInterface) {
            throw new InvalidArgumentException("Expected instance of QuotePaymentInterface");
        }

        return $resource;
    }
}
