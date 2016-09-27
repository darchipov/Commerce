<?php

namespace Ekyna\Component\Commerce\Common;

use Ekyna\Component\Commerce\Cart;
use Ekyna\Component\Commerce\Customer;
use Ekyna\Component\Commerce\Common\Model\SaleItemInterface;
use Ekyna\Component\Commerce\Exception\InvalidArgumentException;
use Ekyna\Component\Commerce\Order;
use Ekyna\Component\Commerce\Quote;

/**
 * Class SaleFactory
 * @package Ekyna\Component\Commerce\Common
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class SaleFactory implements SaleFactoryInterface
{
    /**
     * @var array
     */
    private $classes;


    /**
     * Constructor.
     *
     * @param array $classes
     */
    public function __construct(array $classes = [])
    {
        $this->classes = array_replace_recursive($this->getDefaultClasses(), $classes);

        // TODO validate classes
    }

    /**
     * @inheritdoc
     */
    public function createAddressForSale(Model\SaleInterface $sale)
    {
        return $this->resolveClassAndCreateObject('address', $sale);
    }

    /**
     * @inheritdoc
     */
    public function createItemForSale(Model\SaleInterface $sale)
    {
        return $this->resolveClassAndCreateObject('item', $sale);
    }

    /**
     * @inheritdoc
     */
    public function createAdjustmentForSale(Model\SaleInterface $sale)
    {
        return $this->resolveClassAndCreateObject('adjustment', $sale);
    }

    /**
     * @inheritdoc
     */
    public function createAdjustmentForSaleItem(Model\SaleItemInterface $item)
    {
        return $this->resolveClassAndCreateObject('item_adjustment', $item);
    }

    /**
     * Resolves the class and creates the expected object.
     *
     * @param string $type
     * @param object $subject
     *
     * @return object
     */
    private function resolveClassAndCreateObject($type, $subject)
    {
        foreach ($this->classes[$type] as $source => $target) {
            if ($subject instanceof $source) {
                return new $target;
            }
        }

        throw new InvalidArgumentException('Unsupported object class.');
    }

    /**
     * Returns the default classes.
     *
     * @return array
     */
    private function getDefaultClasses()
    {
        return [
            'address'         => [
//                Customer\Model\CustomerInterface::class => Customer\Entity\CustomerAddress::class,
                Cart\Model\CartInterface::class         => Cart\Entity\CartAddress::class,
                Order\Model\OrderInterface::class       => Order\Entity\OrderAddress::class,
                Quote\Model\QuoteInterface::class       => Quote\Entity\QuoteAddress::class,
            ],
            'item'            => [
                Cart\Model\CartInterface::class   => Cart\Entity\CartItem::class,
                Order\Model\OrderInterface::class => Order\Entity\OrderItem::class,
                Quote\Model\QuoteInterface::class => Quote\Entity\QuoteItem::class,
            ],
            'adjustment'      => [
                Cart\Model\CartInterface::class   => Cart\Entity\CartAdjustment::class,
                Order\Model\OrderInterface::class => Order\Entity\OrderAdjustment::class,
                Quote\Model\QuoteInterface::class => Quote\Entity\QuoteAdjustment::class,
            ],
            'item_adjustment' => [
                Cart\Model\CartItemInterface::class   => Cart\Entity\CartItemAdjustment::class,
                Order\Model\OrderItemInterface::class => Order\Entity\OrderItemAdjustment::class,
                Quote\Model\QuoteItemInterface::class => Quote\Entity\QuoteItemAdjustment::class,
            ],
        ];
    }
}
