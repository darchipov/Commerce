<?php

namespace Ekyna\Component\Commerce\Quote\Model;

/**
 * Class QuoteStates
 * @package Ekyna\Component\Commerce\Quote\Model
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
final class QuoteStates
{
    const STATE_NEW       = 'new';
    const STATE_PENDING   = 'pending';
    const STATE_REFUSED   = 'refused';
    const STATE_ACCEPTED  = 'accepted';
    const STATE_COMPLETED = 'completed';
    const STATE_REFUNDED  = 'refunded';
    const STATE_CANCELLED = 'cancelled';


    /**
     * Returns all the states.
     *
     * @return array
     */
    static public function getStates()
    {
        return [
            static::STATE_NEW,
            static::STATE_PENDING,
            static::STATE_REFUSED,
            static::STATE_ACCEPTED,
            static::STATE_COMPLETED,
            static::STATE_REFUNDED,
            static::STATE_CANCELLED,
        ];
    }

    /**
     * Returns whether the given state is valid or not.
     *
     * @param string $state
     *
     * @return bool
     */
    static public function isValidState($state)
    {
        return in_array($state, static::getStates(), true);
    }
}