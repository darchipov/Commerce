<?php

namespace Ekyna\Component\Commerce\Common\Repository;

use Ekyna\Component\Commerce\Common\Model\CountryInterface;

/**
 * Interface CountryRepositoryInterface
 * @package Ekyna\Component\Commerce\Common\Repository
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
interface CountryRepositoryInterface
{
    /**
     * Returns the default country.
     *
     * @return CountryInterface
     */
    public function findDefault();

    /**
     * Finds a country by its code.
     *
     * @param string $code
     *
     * @return CountryInterface
     */
    public function findOneByCode($code);
}
