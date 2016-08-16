<?php

namespace Ekyna\Component\Commerce\Common\Calculator;

use Ekyna\Component\Commerce\Common\Model\AdjustmentInterface;
use Ekyna\Component\Commerce\Common\Model\AdjustmentModes;
use Ekyna\Component\Commerce\Common\Model\AdjustmentTypes;
use Ekyna\Component\Commerce\Common\Model\SaleInterface;
use Ekyna\Component\Commerce\Common\Model\SaleItemInterface;
use Ekyna\Component\Commerce\Exception\InvalidArgumentException;

/**
 * Class Calculator
 * @package Ekyna\Component\Commerce\Common\Calculator
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class Calculator implements CalculatorInterface
{
    /**
     * @inheritdoc
     */
    public function calculateSaleItemAmounts(SaleItemInterface $item)
    {
        // TODO don't calculate twice

        $amounts = new Amounts();

        if ($item->hasChildren()) { // Calculate as a "parent" item

            // Merge children amounts
            foreach ($item->getChildren() as $child) {
                $amounts->merge($this->calculateSaleItemAmounts($child));
            }

        } else { // Calculate as a "child" item

            // Item total quantity
            $quantity = $item->getQuantity();
            $parent = $item;
            while (null !== $parent = $parent->getParent()) {
                $quantity *= $parent->getQuantity();
            }

            // Item base total
            $amounts->addBase($item->getNetPrice() * $quantity);

            // Taxation adjustments
            if ($item->hasAdjustments(AdjustmentTypes::TYPE_TAXATION)) {
                $adjustments = $item->getAdjustments(AdjustmentTypes::TYPE_TAXATION);
                foreach ($adjustments as $adjustment) {
                    $this->calculateTaxationAdjustment($adjustment, $amounts);
                }
            }

        }

        // Discount adjustments
        if ($item->hasAdjustments(AdjustmentTypes::TYPE_DISCOUNT)) {
            $parentAmounts = clone $amounts;
            $adjustments = $item->getAdjustments(AdjustmentTypes::TYPE_DISCOUNT);
            foreach ($adjustments as $adjustment) {
                // Only 'percent' mode adjustments are allowed here.
                $this->assertAdjustmentMode($adjustment, AdjustmentModes::MODE_PERCENT);

                $amounts->merge($this->calculateDiscountAdjustmentAmounts($adjustment, $parentAmounts));
            }
        }

        return $amounts;
    }

    /**
     * @inheritdoc
     */
    public function calculateSaleAmounts(SaleInterface $sale)
    {
        // TODO don't calculate twice

        $amounts = new Amounts();

        // Items amounts
        if ($sale->hasItems()) {
            foreach ($sale->getItems() as $item) {
                $amounts->merge($this->calculateSaleItemAmounts($item));
            }
        }

        // Discount adjustments amounts
        if ($sale->hasAdjustments(AdjustmentTypes::TYPE_DISCOUNT)) {
            $parentAmounts = clone $amounts;
            $adjustments = $sale->getAdjustments(AdjustmentTypes::TYPE_DISCOUNT);
            foreach ($adjustments as $adjustment) {
                $amounts->merge($this->calculateDiscountAdjustmentAmounts($adjustment, $parentAmounts));
            }
        }

        return $amounts;
    }

    /**
     * @inheritdoc
     */
    public function calculateDiscountAdjustmentAmounts(AdjustmentInterface $adjustment, Amounts $parentAmounts)
    {
        $this->assertAdjustmentType($adjustment, AdjustmentTypes::TYPE_DISCOUNT);

        // TODO don't calculate twice

        $amounts = new Amounts();

        $mode = $adjustment->getMode();
        if (AdjustmentModes::MODE_PERCENT === $mode) {
            $adjustmentRate = $adjustment->getAmount() / 100;

            $amounts->addBase(-$parentAmounts->getBase() * $adjustmentRate);

            foreach ($parentAmounts->getTaxes() as $taxAmount) {
                $amounts->addTaxAmount(
                    $taxAmount->getName(),
                    $taxAmount->getRate(),
                    $taxAmount->getAmount() * $adjustmentRate
                );
            }
        } elseif (AdjustmentModes::MODE_FLAT === $mode) {
            $amounts->addBase(- $adjustment->getAmount());
        } else {
            throw new InvalidArgumentException("Unexpected adjustment mode '$mode'.");
        }

        return $amounts;
    }

    /**
     * Calculates the taxation adjustment amount.
     *
     * @param AdjustmentInterface $adjustment
     * @param Amounts             $targetAmounts
     */
    protected function calculateTaxationAdjustment(AdjustmentInterface $adjustment, Amounts $targetAmounts)
    {
        $this->assertAdjustmentType($adjustment, AdjustmentTypes::TYPE_TAXATION);

        $targetAmounts->addTaxAmount(
            $adjustment->getDesignation(),
            $adjustment->getAmount(),
            $targetAmounts->getBase() * $adjustment->getAmount() / 100
        );
    }

    /**
     * Asserts that the adjustment type is as expected.
     *
     * @param AdjustmentInterface $adjustment
     * @param string              $expectedType
     */
    protected function assertAdjustmentType(AdjustmentInterface $adjustment, $expectedType)
    {
        if ($expectedType !== $type = $adjustment->getType()) {
            throw new InvalidArgumentException("Unexpected adjustment type '$type'.");
        }
    }

    /**
     * Asserts that the adjustment mode is as expected.
     *
     * @param AdjustmentInterface $adjustment
     * @param string              $expectedMode
     */
    protected function assertAdjustmentMode(AdjustmentInterface $adjustment, $expectedMode)
    {
        if ($expectedMode !== $mode = $adjustment->getMode()) {
            throw new InvalidArgumentException("Unexpected adjustment mode '$mode'.");
        }
    }
}
