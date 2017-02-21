<?php

namespace Ekyna\Component\Commerce\Subject\Provider;

use Ekyna\Component\Commerce\Stock\Repository\StockUnitRepositoryInterface;
use Ekyna\Component\Commerce\Subject;

/**
 * Interface SubjectProviderInterface
 * @package Ekyna\Bundle\CommerceBundle\Form
 * @author  Étienne Dauvergne <contact@ekyna.com>
 */
interface SubjectProviderInterface
{
    const DATA_KEY = 'provider';


    /**
     * Sets the subject int the relative.
     *
     * This method must set the relative subject fields (provider and identifier) for next resolutions.
     *
     * @param Subject\Model\SubjectRelativeInterface $relative
     * @param object                                 $subject
     *
     * @return SubjectProviderInterface
     */
    public function assign(Subject\Model\SubjectRelativeInterface $relative, $subject);

    /**
     * Returns the subject from the relative.
     *
     * This method should set the subject into the relative for future resolutions.
     *
     * @param Subject\Model\SubjectRelativeInterface $relative
     *
     * @return object
     */
    public function resolve(Subject\Model\SubjectRelativeInterface $relative);

    /**
     * Transforms the subject into the identity.
     *
     * This method must set the subject identity fields (provider and identifier) and
     * set the subject into the identity prior to next reverse transformations.
     *
     * @param mixed                          $subject
     * @param Subject\Entity\SubjectIdentity $identity
     *
     * @return SubjectProviderInterface
     */
    public function transform($subject, Subject\Entity\SubjectIdentity $identity);

    /**
     * Reverse transform the identity into the subject.
     *
     * This method must set the subject into the identity prior to next reverse transformations.
     *
     * @param Subject\Entity\SubjectIdentity $identity
     *
     * @return object
     */
    public function reverseTransform(Subject\Entity\SubjectIdentity $identity);

    /**
     * Returns whether the resolver supports the relative or not.
     *
     * @param Subject\Model\SubjectRelativeInterface $relative
     *
     * @return boolean
     */
    public function supportsRelative(Subject\Model\SubjectRelativeInterface $relative);

    /**
     * Returns whether the provider supports the subject or not.
     *
     * @param mixed $subject
     *
     * @return bool
     */
    public function supportsSubject($subject);

    /**
     * Returns the item builder.
     *
     * @return Subject\Builder\ItemBuilderInterface
     */
    public function getItemBuilder();

    /**
     * Returns the form builder.
     *
     * @return Subject\Builder\FormBuilderInterface
     */
    public function getFormBuilder();

    /**
     * Returns the productRepository.
     *
     * @return Subject\Repository\SubjectRepositoryInterface
     */
    public function getProductRepository();

    /**
     * Returns the subject stock unit repository.
     *
     * @return StockUnitRepositoryInterface|null
     */
    public function getStockUnitRepository();

    /**
     * Returns the stock unit change event name.
     *
     * @return string
     * @todo remove ?
     */
    public function getStockUnitChangeEventName();

    /**
     * Returns the provider name.
     *
     * @return string
     */
    public function getName();

    /**
     * Returns the subject type label.
     *
     * @return string
     */
    public function getLabel();
}
