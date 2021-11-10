<?php
/**
 * MageTunisia Software.
 *
 * @category  MageTunisia
 * @package   MageTunisia_ImportExport
 * @author    Ben Mansour Marouan marouan.ben.mansour@gmail.com
 * @copyright Copyright (c) MageTunisia Software Private Limited (http://marouan-ben-mansour.com)
 * @license   http://marouan-ben-mansour.com/license.html
 */

declare(strict_types=1);

namespace MageTunisia\ImportExport\Model\Config\Backend;

use Magento\Config\Model\Config\Backend\File;

/**
 * Class ImportExportFile
 *
 * @package MageTunisia\ImportExport\Model\Config\Backend
 */
class ImportExportFile extends File
{
    /**
     * @return string[]
     */
    public function getAllowedExtensions() {
        return ['csv'];
    }
}