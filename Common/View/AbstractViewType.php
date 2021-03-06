<?php

namespace Ekyna\Component\Commerce\Common\View;

use Ekyna\Component\Commerce\Common\Model;

/**
 * Class AbstractViewType
 * @package Ekyna\Component\Commerce\Common\View
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
abstract class AbstractViewType implements ViewTypeInterface
{
    /**
     * @inheritDoc
     */
    public function buildSaleView(Model\SaleInterface $sale, SaleView $view, array $options)
    {

    }

    /**
     * @inheritDoc
     */
    public function buildItemView(Model\SaleItemInterface $item, LineView $view, array $options)
    {

    }

    /**
     * @inheritDoc
     */
    public function buildAdjustmentView(Model\AdjustmentInterface $adjustment, LineView $view, array $options)
    {

    }

    /**
     * @inheritDoc
     */
    public function buildShipmentView(Model\SaleInterface $sale, LineView $view, array $options)
    {

    }
}
