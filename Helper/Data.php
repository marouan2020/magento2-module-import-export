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

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory;


/**
 * Class Data
 *
 * @package MageTunisia\ImportExport\Helper
 */
class Data extends AbstractHelper
{
    const CONFIG_IMPORTEXPORT_ENABLE = "magetunisia_importexport/fields_general/activate";
    const CONFIG_IMPORTEXPORT_DELIMITER = "magetunisia_importexport/fields_general/delimiter";
    const CONFIG_IMPORTEXPORT_ATTRIBUTES_SET = "magetunisia_importexport/fields_general/attributesetid";
    const CONFIG_IMPORTEXPORT_PRODUCT_TYPE = "magetunisia_importexport/fields_general/product_type";
    const CONFIG_IMPORTEXPORT_MAPPING_PRODUCT_TYPE = "magetunisia_importexport/fields_product/mapping_product_type";
    const CONFIG_IMPORTEXPORT_MAPPING_STORE = "magetunisia_importexport/fields_product/mapping_store";
    const CONFIG_IMPORTEXPORT_MAPPING_ATTRIBUTES_SET = "magetunisia_importexport/fields_product/mapping_attributes_sets";
    const CONFIG_IMPORTEXPORT_LOGGER_FILE = "magetunisia_importexport/fields_general/log_file";
    const CONFIG_IMPORTEXPORT_PRODUCT_FILE = "magetunisia_importexport/fields_product/upload_file";
    const CONFIG_IMPORTEXPORT_PRODUCT_MAPPING = "magetunisia_importexport/fields_map_product/";

    /**
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $storeManager;

    /**
     * @var Json
     */
    private $json;

    /**
     * @var CollectionFactory
     */
    private $_attributeFactory;

    /**
     * Data constructor.
     *
     * @param Context $context
     * @param StoreManagerInterface $store_manager
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $store_manager,
        CollectionFactory $attribute_factory,
        Json $serializer_json
    ) {
        $this->storeManager = $store_manager;
        $this->json = $serializer_json;
        $this->_attributeFactory = $attribute_factory;
        parent::__construct($context);
    }

    /**
     * Get config value of enable field.
     *
     * @return string
     */
    public function getConfigEnable(): string
    {
        return $this->scopeConfig->getValue(
            self::CONFIG_IMPORTEXPORT_ENABLE,
            ScopeInterface::SCOPE_WEBSITE
        );
    }

    /**
     * Get config value of product file.
     *
     * @return string
     */
    public function getConfigProductFile()
    {
        return $this->scopeConfig->getValue(
            self::CONFIG_IMPORTEXPORT_PRODUCT_FILE,
            ScopeInterface::SCOPE_WEBSITE
        );
    }

    /**
     * Delemiter value
     *
     * @return string
     */
    public function getConfigDelimiter() 
    {
        return $this->scopeConfig->getValue(
            self::CONFIG_IMPORTEXPORT_DELIMITER,
            ScopeInterface::SCOPE_WEBSITE
        );
    }

    /**
     * Path logger file
     *
     * @return string
     */
    public function getConfigLoggerFile() 
    {
        return $this->scopeConfig->getValue(
            self::CONFIG_IMPORTEXPORT_LOGGER_FILE,
            ScopeInterface::SCOPE_WEBSITE
        );
    }

    /**
     * Attributes set
     *
     * @return string
     */
    public function getConfigAllowedAttributesetIds()
    {
        return $this->scopeConfig->getValue(
            self::CONFIG_IMPORTEXPORT_ATTRIBUTES_SET,
            ScopeInterface::SCOPE_WEBSITE
        );
    }

    /**
     * Mapping Attributes set
     *
     * @return string
     */
    public function getConfigMappingAttributeset()
    {
        return $this->scopeConfig->getValue(
            self::CONFIG_IMPORTEXPORT_MAPPING_ATTRIBUTES_SET,
            ScopeInterface::SCOPE_WEBSITE
        );
    }

    /**
     * Mapping Store
     *
     * @return string
     */
    public function getConfigMappingStore()
    {
        return $this->scopeConfig->getValue(
            self::CONFIG_IMPORTEXPORT_MAPPING_STORE,
            ScopeInterface::SCOPE_WEBSITE
        );
    }

    /**
     * Mapping attributes
     *
     * @return array
     */
    public function getConfigMappingInstructions(): array
    {
        $valueConfigMappingFields = [];
        $attributesInfo = $this->_attributeFactory->create();
        foreach($attributesInfo as $attribute)
        {
            $valueConfigValue =  $this->scopeConfig->getValue(
                self::CONFIG_IMPORTEXPORT_PRODUCT_MAPPING.$attribute->getAttributeCode(),
                ScopeInterface::SCOPE_WEBSITE
            );
            if (!is_numeric($valueConfigValue)) {
               continue;
            }
            $valueConfigMappingFields[$valueConfigValue] = $attribute->getAttributeCode();
        }
        ksort($valueConfigMappingFields);
        return $valueConfigMappingFields;
    }

     /**
     * Get product type from config
     *
     * @return string
     */
    public function getConfigProductTypes()
    {
        return $this->scopeConfig->getValue(
            self::CONFIG_IMPORTEXPORT_PRODUCT_TYPE,
            ScopeInterface::SCOPE_WEBSITE
        );
    }

    /**
     * Mapping product type from config
     *
     * @return string
     */
    public function getConfigMappingProductType()
    {
        return $this->scopeConfig->getValue(
            self::CONFIG_IMPORTEXPORT_MAPPING_PRODUCT_TYPE,
            ScopeInterface::SCOPE_WEBSITE
        );
    }

    /**
     * Check required field exist.
     *
     * @param array $mappingFields
     * 
     * @return bool
     */
    public function checkRequiredFiledExist(array $mappingFields=[]): bool
    {
        if (empty($mappingFields)) {
            return false;
        }
        if (!in_array("name", $mappingFields) ||
            !in_array("price", $mappingFields) || 
            !in_array("sku", $mappingFields) 
        ) {
           return false;
        }
        return true;
    }

    /**
     * Merge params with mapping fields.
     *
     * @param array $mappingFields
     * @param array $params
     * 
     * @return array
     */
    public function mappingFieldValue(array $mappingFields, array $params): array
    {
        $data = []; 
        foreach($params as $index => $param) {
            if (empty($mappingFields[$index])) {
                continue;
            }
            $data[$mappingFields[$index]] = $param;
        }
        $allowedsets = [];
        $allowedAttributesetIds = $this->getConfigAllowedAttributesetIds();
        if (!empty($allowedAttributesetIds)) {
            $allowedsets = explode(",",  $allowedAttributesetIds);
        }
        $mappingAttributeSet = $this->getConfigMappingAttributeset();
        $data['attribute_set'] = (empty($mappingAttributeSet) || empty($params[$mappingAttributeSet]) || !in_array($params[$mappingAttributeSet], $allowedsets))?'default':$params[$mappingAttributeSet];
        $mappingStore = $this->getConfigMappingStore();
        $data['store_code'] = (empty($mappingStore) || empty($params[$mappingStore]))?$this->storeManager->getDefaultStoreView()->getCode():$params[$mappingStore];
        $allowedtypes = [];
        $allowedProductTypes = $this->getConfigProductTypes();
        if (!empty($allowedProductTypes)) {
            $allowedtypes = explode(",",  $allowedProductTypes);
        }
        $mappingProductType = $this->getConfigMappingProductType();
        $data['product_type'] = (empty($mappingProductType) || empty($params[$mappingProductType]) || !in_array($params[$mappingProductType], $allowedtypes))?'simple':$params[$mappingProductType];
        return $data;
    }

    /**
     * Decode string json format
     *
     * @param string $value
     * @return array
     */
    public function jsonDecode($value): array
    {
        return $this->json->unserialize($value);
    }

    /**
     * Encode array to json format
     *
     * @param array $value
     * @return string
     */
    public function jsonEncode($value): string
    {
        return $this->json->serialize($value);
    }
}