<?php

namespace Ekyna\Component\Commerce\Cart\Event;

/**
 * Class CartAdjustmentEvents
 * @package Ekyna\Component\Commerce\Cart\Event
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
final class CartAdjustmentEvents
{
    // Persistence
    const INSERT         = 'ekyna_commerce.cart_adjustment.insert';
    const UPDATE         = 'ekyna_commerce.cart_adjustment.update';
    const DELETE         = 'ekyna_commerce.cart_adjustment.delete';

    // Domain
    const PRE_CREATE     = 'ekyna_commerce.cart_adjustment.pre_create';
    const POST_CREATE    = 'ekyna_commerce.cart_adjustment.post_create';

    const PRE_UPDATE     = 'ekyna_commerce.cart_adjustment.pre_update';
    const POST_UPDATE    = 'ekyna_commerce.cart_adjustment.post_update';

    const PRE_DELETE     = 'ekyna_commerce.cart_adjustment.pre_delete';
    const POST_DELETE    = 'ekyna_commerce.cart_adjustment.post_delete';
}
