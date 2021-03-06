<?php

namespace Ekyna\Component\Commerce\Bridge\Doctrine\ORM\Listener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\ClassMetadata;
use Ekyna\Component\Commerce\Payment\Model as Payment;
use Ekyna\Component\Commerce\Pricing\Model as Tax;
use Ekyna\Component\Commerce\Stock\Entity\AbstractStockUnit;
use Ekyna\Component\Commerce\Stock\Model as Stock;
use Ekyna\Component\Commerce\Subject\Entity\SubjectIdentity;
use Ekyna\Component\Commerce\Subject\Model\SubjectRelativeInterface;
use Ekyna\Component\Resource\Doctrine\ORM\Mapping\DiscriminatorMapper;
use Ekyna\Component\Resource\Doctrine\ORM\Mapping\EmbeddableMapper;

/**
 * Class LoadMetadataListener
 * @package Ekyna\Component\Commerce\Bridge\Doctrine\ORM\Listener
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class LoadMetadataListener implements EventSubscriber
{
    /**
     * @var DiscriminatorMapper
     */
    private $stockUnitMapper;

    /**
     * @var EmbeddableMapper
     */
    private $subjectIdentityMapper;

    /**
     * @var array
     */
    private $paymentTermSubjectClassCache = [];

    /**
     * @var array
     */
    private $taxableClassCache = [];

    /**
     * @var array
     */
    private $relativeClassCache = [];

    /**
     * @var array
     */
    private $stockClassCache = [];


    /**
     * @inheritdoc
     */
    public function getSubscribedEvents()
    {
        return [Events::loadClassMetadata];
    }

    /**
     * @param LoadClassMetadataEventArgs $eventArgs
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        /** @var ClassMetadata $metadata */
        $metadata = $eventArgs->getClassMetadata();
        $class = $metadata->getName();

        // Skip abstract classes.
        if ((new \ReflectionClass($class))->isAbstract()) {
            return;
        }

        if (is_subclass_of($class, Tax\TaxableInterface::class)) {
            $this->configureTaxableMapping($eventArgs);
        }

        if (is_subclass_of($class, Payment\PaymentTermSubjectInterface::class)) {
            $this->configurePaymentTermSubjectMapping($eventArgs);
        }

        if (is_subclass_of($class, Stock\StockSubjectInterface::class)) {
            $this->configureStockSubjectMapping($eventArgs);
        }

        if (is_subclass_of($class, SubjectRelativeInterface::class)) {
            $this->configureSubjectRelativeMapping($eventArgs);
        }

        if (is_subclass_of($class, Stock\StockUnitInterface::class)) {
            $this->configureStockUnitDiscriminatorMap($eventArgs);
        }
    }

    /**
     * Configures the taxable mapping.
     *
     * @param LoadClassMetadataEventArgs $eventArgs
     */
    private function configureTaxableMapping(LoadClassMetadataEventArgs $eventArgs)
    {
        /** @var ClassMetadata $metadata */
        $metadata = $eventArgs->getClassMetadata();
        $class = $metadata->getName();

        // Check class
        if (!is_subclass_of($class, Tax\TaxableInterface::class)) {
            return;
        }

        // Skip abstract classes.
        if ((new \ReflectionClass($class))->isAbstract()) {
            return;
        }

        // Don't add twice
        if (in_array($class, $this->taxableClassCache)) {
            return;
        }

        if (!$metadata->hasAssociation('taxGroup')) {
            $metadata->mapManyToOne([
                'fieldName'    => 'taxGroup',
                'targetEntity' => Tax\TaxGroupInterface::class,
                'joinColumns'  => [
                    [
                        'name'                 => 'tax_group_id',
                        'referencedColumnName' => 'id',
                        'onDelete'             => 'RESTRICT',
                        'nullable'             => true,
                    ],
                ],
            ]);
        }

        // Cache class
        $this->taxableClassCache[] = $class;
    }

    /**
     * Configures the payment term subject mapping.
     *
     * @param LoadClassMetadataEventArgs $eventArgs
     */
    private function configurePaymentTermSubjectMapping(LoadClassMetadataEventArgs $eventArgs)
    {
        /** @var ClassMetadata $metadata */
        $metadata = $eventArgs->getClassMetadata();
        $class = $metadata->getName();

        // Check class
        if (!is_subclass_of($class, Payment\PaymentTermSubjectInterface::class)) {
            return;
        }

        // Skip abstract classes.
        if ((new \ReflectionClass($class))->isAbstract()) {
            return;
        }

        // Don't add twice
        if (in_array($class, $this->paymentTermSubjectClassCache)) {
            return;
        }

        if (!$metadata->hasAssociation('paymentTerm')) {
            $metadata->mapManyToOne([
                'fieldName'    => 'paymentTerm',
                'targetEntity' => Payment\PaymentTermInterface::class,
                'joinColumns'  => [
                    [
                        'name'                 => 'payment_term_id',
                        'referencedColumnName' => 'id',
                        'onDelete'             => 'RESTRICT',
                        'nullable'             => true,
                    ],
                ],
            ]);
        }

        // Cache class
        $this->paymentTermSubjectClassCache[] = $class;
    }

    /**
     * Configures the subject relative mapping.
     *
     * @param LoadClassMetadataEventArgs $eventArgs
     */
    private function configureSubjectRelativeMapping(LoadClassMetadataEventArgs $eventArgs)
    {
        /** @var ClassMetadata $metadata */
        $metadata = $eventArgs->getClassMetadata();
        $class = $metadata->getName();

        // Check class
        if (!is_subclass_of($class, SubjectRelativeInterface::class)) {
            return;
        }

        // Skip abstract classes.
        if ((new \ReflectionClass($class))->isAbstract()) {
            return;
        }

        // Don't add twice
        if (in_array($class, $this->relativeClassCache)) {
            return;
        }

        // Add mappings
        $this->addMappings($metadata, $this->getSubjectRelativeMappings());

        // Map embedded
        $this
            ->getSubjectIdentityMapper($eventArgs->getEntityManager())
            ->processClassMetadata($metadata, 'subjectIdentity', 'subject_');

        // Cache class
        $this->relativeClassCache[] = $class;
    }

    /**
     * Configures the stock subject mapping.
     *
     * @param LoadClassMetadataEventArgs $eventArgs
     */
    private function configureStockSubjectMapping(LoadClassMetadataEventArgs $eventArgs)
    {
        /** @var ClassMetadata $metadata */
        $metadata = $eventArgs->getClassMetadata();
        $class = $metadata->getName();

        // Check class
        if (!is_subclass_of($class, Stock\StockSubjectInterface::class)) {
            return;
        }

        // Skip abstract classes.
        if ((new \ReflectionClass($class))->isAbstract()) {
            return;
        }

        // Don't add twice
        if (in_array($class, $this->stockClassCache)) {
            return;
        }

        // Add mappings
        $this->addMappings($metadata, $this->getStockSubjectMappings());

        // Cache class
        $this->stockClassCache[] = $class;
    }

    /**
     * Configures the stock unit discriminator map.
     *
     * @param LoadClassMetadataEventArgs $eventArgs
     */
    private function configureStockUnitDiscriminatorMap(LoadClassMetadataEventArgs $eventArgs)
    {
        /** @var ClassMetadata $metadata */
        $metadata = $eventArgs->getClassMetadata();

        if (!is_subclass_of($metadata->name, Stock\StockUnitInterface::class)) {
            return;
        }

        $this
            ->getStockUnitMapper($eventArgs->getEntityManager())
            ->processClassMetadata($metadata);
    }

    /**
     * Returns the stock unit mapper.
     *
     * @param EntityManagerInterface $em
     *
     * @return DiscriminatorMapper
     */
    private function getStockUnitMapper(EntityManagerInterface $em)
    {
        if (null === $this->stockUnitMapper) {
            $this->stockUnitMapper = new DiscriminatorMapper($em, AbstractStockUnit::class);
        }

        return $this->stockUnitMapper;
    }

    /**
     * Returns the subjectIdentityMapper.
     *
     * @param EntityManagerInterface $em
     *
     * @return EmbeddableMapper
     */
    private function getSubjectIdentityMapper(EntityManagerInterface $em)
    {
        if (null === $this->subjectIdentityMapper) {
            $this->subjectIdentityMapper = new EmbeddableMapper($em, SubjectIdentity::class);
        }

        return $this->subjectIdentityMapper;
    }

    /**
     * Adds the mappings to the metadata.
     *
     * @param ClassMetadata $metadata
     * @param array         $mappings
     */
    private function addMappings(ClassMetadata $metadata, array $mappings)
    {
        foreach ($mappings as $mapping) {
            if (!$metadata->hasField($mapping['fieldName'])) {
                $metadata->mapField($mapping);
            }
        }
    }

    /**
     * Returns the subject relative mappings.
     *
     * @return array
     */
    private function getSubjectRelativeMappings()
    {
        return [
            /*[
                'fieldName'  => 'subjectProvider',
                'columnName' => 'subject_provider',
                'type'       => 'string',
                'length'     => 16,
                'nullable'   => true,
            ],
            [
                'fieldName'  => 'subjectIdentifier',
                'columnName' => 'subject_identifier',
                'type'       => 'string',
                'length'     => 16,
                'nullable'   => true,
            ],*/
            [
                'fieldName'  => 'subjectData',
                'columnName' => 'subject_data',
                'type'       => 'json_array',
                'nullable'   => true,
            ],
        ];
    }

    /**
     * Returns the stock subject mappings.
     *
     * @return array
     */
    private function getStockSubjectMappings()
    {
        return [
            [
                'fieldName'  => 'stockMode',
                'columnName' => 'stock_mode',
                'type'       => 'string',
                'length'     => 16,
                'nullable'   => true,
            ],
            [
                'fieldName'  => 'stockState',
                'columnName' => 'stock_state',
                'type'       => 'string',
                'length'     => 16,
                'nullable'   => true,
            ],
            [
                'fieldName'  => 'inStock',
                'columnName' => 'in_stock',
                'type'       => 'decimal',
                'precision'  => 10,
                'scale'      => 3,
                'nullable'   => true,
            ],
            [
                'fieldName'  => 'virtualStock',
                'columnName' => 'virtual_stock',
                'type'       => 'decimal',
                'precision'  => 10,
                'scale'      => 3,
                'nullable'   => true,
            ],
            [
                'fieldName'  => 'estimatedDateOfArrival',
                'columnName' => 'estimated_date_of_arrival',
                'type'       => 'datetime',
                'nullable'   => true,
            ],
        ];
    }
}
