<?php

namespace Ekyna\Component\Commerce\Quote\Model;

use Ekyna\Component\Commerce\Common\Model\AdjustmentInterface;

/**
 * Interface QuoteAdjustmentInterface
 * @package Ekyna\Component\Commerce\Quote\Model
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
interface QuoteAdjustmentInterface extends AdjustmentInterface
{
    /**
     * Returns the quote.
     *
     * @return QuoteInterface
     */
    public function getQuote();

    /**
     * Sets the quote.
     *
     * @param QuoteInterface $quote
     * @return $this|QuoteAdjustmentInterface
     */
    public function setQuote(QuoteInterface $quote = null);
}
