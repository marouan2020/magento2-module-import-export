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

namespace MageTunisia\ImportExport\Helper;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\File\Csv;
use Magento\Framework\Filesystem\Driver\File as FileDriver;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory;

/**
 * Class File
 *
 * @package MageTunisia\ImportExport\Helper
 */
class File extends Data
{
    /**
     * @var Filesystem
     */
    protected $fileSystem;

    /**
     * @var Csv
     */
    protected $csvParser;

    /**
     * @var FileDriver
     */
    protected $fileDriver;

    /**
     * File constructor.
     *
     * @param Filesystem $file_system
     * @param Context $context
     * @param StoreManagerInterface $store_manager
     * @param Csv $csv_parser 
     * @param FileDriver $file_driver
     */
    public function __construct(
        Filesystem $file_system,
        Context $context,
        StoreManagerInterface $store_manager,
        Csv $csv_parser,
        FileDriver $file_driver,
        Json $json,
        CollectionFactory $attribute_factory
    )
    {
        $this->fileSystem = $file_system;
        $this->csvParser = $csv_parser;
        $this->fileDriver = $file_driver;
        parent::__construct($context, $store_manager, $attribute_factory, $json);
    }

    /**
     * Convert csv to array data.
     *
     * @return array
     */
    public function getDataFromCsvFile(): array 
    {
        $fileUploded = $this->getConfigProductFile();
        $absolutePathProductFile = $this->fileSystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath('importexport/') . $fileUploded;
        if (!$this->fileDriver->isExists($absolutePathProductFile)) {
            return [];
        }
        $delimiter = $this->getDelimiter();
        if (empty($delimiter)) {
            return [];
        }
        $this->csvParser->setDelimiter($delimiter);
        $data = $this->csvParser->getData($absolutePathProductFile);
        return $data;
    }

    public function getDelimiter() 
    {
        return $this->getConfigDelimiter();
    }

    public function getLoggerFile()
    {
        return $this->getConfigLoggerFile();
    }
}