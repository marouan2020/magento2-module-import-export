<?php
/**
 * Copyright © MageTunisia GmbH. All rights reserved.
 * See LICENSE.md bundled with this module for license details.
 */

namespace MageTunisia\ConfigImportExport\Model\Processor;

use MageTunisia\ConfigImportExport\Model\File\FinderInterface;
use MageTunisia\ConfigImportExport\Model\File\Reader\ReaderInterface;

/**
 * Interface ImportProcessorInterface
 *
 * @package MageTunisia\ConfigImportExport\Model\Processor
 */
interface ImportProcessorInterface extends AbstractProcessorInterface
{
    /**
     * @param ReaderInterface $reader
     */
    public function setReader(ReaderInterface $reader);

    /**
     * @param FinderInterface $finder
     */
    public function setFinder(FinderInterface $finder);
}
