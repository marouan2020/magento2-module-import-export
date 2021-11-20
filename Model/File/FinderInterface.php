<?php
/**
 * Copyright © MageTunisia GmbH. All rights reserved.
 * See LICENSE.md bundled with this module for license details.
 */

namespace MageTunisia\ConfigImportExport\Model\File;

/**
 * Interface FinderInterface
 *
 * @package MageTunisia\ConfigImportExport\Model\File
 */
interface FinderInterface
{
    /**
     * @return array
     */
    public function find();

    /**
     * @param array $environment
     */
    public function setEnvironment($environment);

    /**
     * @param mixed $folder
     */
    public function setFolder($folder);

    /**
     * @param mixed $baseFolder
     */
    public function setBaseFolder($baseFolder);

    /**
     * @param mixed $format
     */
    public function setFormat($format);
}
