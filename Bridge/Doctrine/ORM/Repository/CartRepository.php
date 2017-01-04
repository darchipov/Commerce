<?php

namespace Ekyna\Component\Commerce\Bridge\Doctrine\ORM\Repository;

use Ekyna\Component\Commerce\Cart\Repository\CartRepositoryInterface;

/**
 * Class CartRepository
 * @package Ekyna\Component\Commerce\Bridge\Doctrine\ORM\Repository
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class CartRepository extends AbstractSaleRepository implements CartRepositoryInterface
{
    /**
     * @inheritdoc
     */
    protected function getAlias()
    {
        return 'c';
    }
}
