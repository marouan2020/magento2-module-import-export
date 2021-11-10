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

namespace MageTunisia\ImportExport\Model\Service;

use MageTunisia\ImportExport\Api\Service\AddProductManagementInterface;
use MageTunisia\ImportExport\Helper\Data as HelperData;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Catalog\Model\ProductFactory; 

/**
 * AddProductManagement class.
 * 
 * @package \MageTunisia\ImportExport\Model\Service
 */
class AddProductManagement implements AddProductManagementInterface
{
    /**
     * @var DateTime
     */
    protected $_date;

    /**
     * @var HelperData
     */
    protected $helper;

    /**
     * @var ProductFactory
     */
    protected $_productFactory;
  

    /**
     * @param DateTime $date
     * @param Filesystem $filesystem
     * @param HelperData $helper
     */
    public function __construct(
        ProductFactory $product_factory,
        DateTime $date,
        Filesystem $filesystem,
        HelperData $helper
    ) {
        $this->_productFactory = $product_factory;
        $this->_date = $date;
        $this->_mediaDirectory = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        $this->helper = $helper;
    }

    /**
     * {@inheritdoc}
     */
    public function postAddProduct($params)
    {
      $mappingFields = $this->helper->getConfigMappingInstructions();
      $writer = new \Zend\Log\Writer\Stream(BP . "/var/log/status_import_product.log");
      $logger = new \Zend\Log\Logger();
      $logger->addWriter($writer);
      if (!$this->helper->checkRequiredFiledExist($mappingFields)){
          return "Check required fields (name, sku, price) exist and try aigen!";
          exit; 
      }

      $mappingFieldValue = $this->helper->mappingFieldValue($mappingFields, $params);
      try {

        /** @var $mageProduct \Magento\Catalog\Model\Product */
        $mageProduct = $this->_productFactory->create();
        if (!empty($mappingFieldValue['sku'])) {
            $mageProduct->loadByAttribute('sku', $mappingFieldValue['sku']);
        }
        foreach ($mappingFieldValue as $field => $value) {
          $mageProduct->setData($field, $value);
        }
        if ($mageProduct->save()) {
            $logger->info("Product ".$mageProduct->getSku()." created successfully 100%.");
            return "Product: ".$mageProduct->getSku()." ...... 100%.";
        }
      } catch (\Exception $e) {
            $logger->info($e->getMessage());
      }
    }
}
