<?php

namespace Ekyna\Component\Commerce\Common\Generator;

use Ekyna\Component\Commerce\Common\Model\NumberSubjectInterface;
use Ekyna\Component\Commerce\Exception\RuntimeException;

/**
 * Class DefaultNumberGenerator
 * @package Ekyna\Component\Commerce\Order\Generator
 * @author  Etienne Dauvergne <contact@ekyna.com>
 */
class DefaultNumberGenerator implements NumberGeneratorInterface
{
    /**
     * @var string
     */
    private $filePath;

    /**
     * @var string
     */
    private $datePrefixFormat;

    /**
     * @var int
     */
    private $length;


    /**
     * Constructor.
     *
     * @param string $filePath          The number file path
     * @param string $datePrefixFormat  The format to use with date function
     * @param int    $length            The total number length
     */
    public function __construct($filePath, $datePrefixFormat = 'ym', $length = 10)
    {
        $this->filePath = $filePath;
        $this->datePrefixFormat = $datePrefixFormat;
        $this->length = $length;
    }

    /**
     * {@inheritdoc}
     */
    public function generate(NumberSubjectInterface $subject)
    {
        if (null !== $subject->getNumber()) {
            return $this;
        }

        // Open
        if (false === $handle = fopen($this->filePath, 'c+')) {
            throw new RuntimeException("Failed to open file {$this->filePath}.");
        }
        // Exclusive lock
        if (!flock($handle, LOCK_EX)) {
            throw new RuntimeException("Failed to lock file {$this->filePath}.");
        }

        $number = fread($handle, $this->length);

        $datePrefix = (new \DateTime())->format($this->datePrefixFormat);

        if (0 !== strpos($number, $datePrefix)) {
            $number = 0;
        } else {
            $number = intval(substr($number, strlen($datePrefix)));
        }

        $result = $datePrefix . str_pad($number + 1, 10 - strlen($datePrefix), '0', STR_PAD_LEFT);

        // Truncate
        if (!ftruncate($handle, 0)) {
            throw new RuntimeException("Failed to truncate file {$this->filePath}.");
        }
        // Reset
        if (0 > fseek($handle, 0)) {
            throw new RuntimeException("Failed to move pointer at the beginning of the file {$this->filePath}.");
        }
        // Write
        if (!fwrite($handle, $result)) {
            throw new RuntimeException("Failed to write file {$this->filePath}.");
        }
        // Flush
        if (!fflush($handle)) {
            throw new RuntimeException("Failed to flush file {$this->filePath}.");
        }
        // Unlock
        if (!flock($handle, LOCK_UN)) {
            throw new RuntimeException("Failed to unlock file {$this->filePath}.");
        }
        // Close
        fclose($handle);

        $subject->setNumber($result);

        return $this;
    }
}
