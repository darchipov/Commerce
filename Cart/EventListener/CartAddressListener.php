<?php

namespace Ekyna\Component\Commerce\Cart\EventListener;

use Ekyna\Component\Commerce\Common\EventListener\AbstractSaleAddressListener;
use Ekyna\Component\Commerce\Common\Model;
use Ekyna\Component\Commerce\Exception\InvalidArgumentException;
use Ekyna\Component\Commerce\Cart\Event\CartEvents;
use Ekyna\Component\Commerce\Cart\Model\CartAddressInterface;
use Ekyna\Component\Resource\Event\ResourceEventInterface;

/**
 * Class CartAddressListener
 * @package Ekyna\Component\Commerce\Cart\EventListener
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class CartAddressListener extends AbstractSaleAddressListener
{
    /**
     * @inheritdoc
     */
    protected function dispatchSaleTaxResolutionEvent(Model\AddressInterface $address)
    {
        /** @var CartAddressInterface $address */
        $cart = $address->getCart();

        $event = $this->dispatcher->createResourceEvent($cart);

        $this->dispatcher->dispatch(CartEvents::TAX_RESOLUTION, $event);
    }

    /**
     * @inheritdoc
     */
    protected function getAddressFromEvent(ResourceEventInterface $event)
    {
        $address = $event->getResource();

        if (!$address instanceof CartAddressInterface) {
            throw new InvalidArgumentException("Expected instance of CartAddressInterface.");
        }

        return $address;
    }
}
