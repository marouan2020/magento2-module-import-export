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

namespace MageTunisia\ImportExport\Console\Command;


use Symfony\Component\Console\Command\Command;
use MageTunisia\ImportExport\Helper\File;
use Psr\Log\LoggerInterface;
use MageTunisia\ImportExport\Model\Service\AddProductManagementFactory;


abstract class AbstractImport extends Command
{
    /**
     * @var File
     */
    protected $helperFile;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var AddProductManagement
     */
    private $_addProductManagement;

    /**
     * AbstractImport construct.
     *
     * @param File $helper_file
     * @param LoggerInterface $logger_interface
     */
    public function __construct(
       File $helper_file,
       LoggerInterface $logger_interface,
       AddProductManagementFactory $add_product
    )
    {
        $this->helperFile = $helper_file;
        $this->logger = $logger_interface;
        $this->_addProductManagement = $add_product;
        parent::__construct();
    }

    public function getDataFromCsv()
    {
       return $this->helperFile->getDataFromCsvFile();
    }

    public function import($lineData, $type) {
       if ($type == "products") {
          $result = $this->_addProductManagement->create()->postAddProduct($lineData);
          return $result;
       }
       if ($type == "categories") {
        return "<info>1 {$lineData}</info>";
       }
       return "<error>{$type} can't be process!</error>";
    }

    protected function writeLoggerInFile($data) {
        $writer = new \Zend\Log\Writer\Stream(BP . "/var/log/{$this->helperFile->getLoggerFile()}");
        $logger = new \Zend\Log\Logger();
        $logger->addWriter($writer);
        $logger->info($data);
    }
}