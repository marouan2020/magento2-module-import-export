<?php
/**
 * Copyright © MageTunisia GmbH. All rights reserved.
 * See LICENSE.md bundled with this module for license details.
 */

namespace MageTunisia\ConfigImportExport\Model\Processor;

use MageTunisia\ConfigImportExport\Model\File\Writer\WriterInterface;

/**
 * Interface ExportProcessorInterface
 *
 * @package MageTunisia\ConfigImportExport\Model\Processor
 */
interface ExportProcessorInterface extends AbstractProcessorInterface
{
    /**
     * @param WriterInterface $writer
     */
    public function setWriter(WriterInterface $writer);

    /**
     * @param string $include
     */
    public function setInclude($include);

    /**
     * @param string $includeScope
     */
    public function setIncludeScope($includeScope);

    /**
     * @param string $exclude
     */
    public function setExclude($exclude);
}
