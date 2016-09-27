<?php

namespace Ekyna\Component\Commerce\Payment\Model;

use Ekyna\Component\Commerce\Common\Model\MethodInterface;

/**
 * Interface PaymentMethodInterface
 * @package Ekyna\Component\Commerce\Payment\Model
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
interface PaymentMethodInterface extends MethodInterface
{
    /**
     * Returns whether or not the method requires manual management of payments state.
     *
     * @return bool
     */
    public function isManual();
}
