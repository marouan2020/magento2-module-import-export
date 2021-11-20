<?php
/**
 * Copyright © MageTunisia GmbH. All rights reserved.
 * See LICENSE.md bundled with this module for license details.
 */

namespace MageTunisia\ConfigImportExport\Model\File\Reader;

/**
 * Interface ReaderInterface
 *
 * @package MageTunisia\ConfigImportExport\Model\File\Reader
 */
interface ReaderInterface
{
    /**
     * @param string $fileName
     * @return mixed
     */
    public function parse($fileName);
}
