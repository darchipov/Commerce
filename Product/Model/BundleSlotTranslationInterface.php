<?php

namespace Ekyna\Component\Commerce\Product\Model;

use Ekyna\Component\Resource\Model\TranslationInterface;

/**
 * Interface BundleSlotTranslationInterface
 * @package Ekyna\Component\Commerce\Product\Entity
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
interface BundleSlotTranslationInterface extends TranslationInterface
{
    /**
     * Returns the title.
     *
     * @return string
     */
    public function getTitle();

    /**
     * Sets the title.
     *
     * @param string $title
     *
     * @return $this|BundleSlotTranslationInterface
     */
    public function setTitle($title);

    /**
     * Returns the description.
     *
     * @return string
     */
    public function getDescription();

    /**
     * Sets the description.
     *
     * @param string $description
     *
     * @return $this|BundleSlotTranslationInterface
     */
    public function setDescription($description);
}