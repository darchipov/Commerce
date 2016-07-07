<?php

namespace Ekyna\Component\Commerce\Bridge\Symfony\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * Class Order
 * @package Ekyna\Component\Commerce\Bridge\Symfony\Validator\Constraints
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class Order extends Constraint
{
    public $invoiceAddressIsMandatory = 'ekyna_order.order.invoice_address_is_mandatory';
    public $deliveryAddressIsMandatory = 'ekyna_order.order.delivery_address_is_mandatory';

    /**
     * {@inheritdoc}
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
