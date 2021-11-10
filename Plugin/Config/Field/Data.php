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

namespace MageTunisia\ImportExport\Plugin\Config\Field;

use Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory;
use Magento\Config\Model\Config\Structure\Data as StructureData;
use Magento\Framework\Module\ModuleListInterface;

/**
 * Data class
 * 
 * @package MageTunisia\ImportExport\Plugin\Config\Field
 */
class Data
{

    protected $_attributeFactory;

    /**
     * Data construct
     *
     * @param ModuleListInterface $moduleList
     * @param CollectionFactory $attributeFactory
     */
    public function __construct(
        ModuleListInterface $moduleList,
        CollectionFactory $attributeFactory
        )
    {
        $this->_moduleList = $moduleList;
        $this->_attributeFactory = $attributeFactory;
    }

    /**
     * Undocumented function
     *
     * @param StructureData $object
     * @param array $config
     * @return void
     */
    public function beforeMerge(StructureData $object, array $config)
    {
       $moduleList = $this->_moduleList->getNames();
       foreach ($moduleList as $name)
       {
          if (strpos($name, 'MageTunisia_ImportExport') === false)
          {
            continue;
          }
          $this->moduleslist[] = $name;
        }
        if (!isset($config['config']['system']))
        {
          return [$config];
        }
        $sections = $config['config']['system']['sections'];
        foreach ($sections as $sectionId => $section)
        {
            if (isset($section['tab']) && ($section['tab'] === 'MageTunisia') && ($section['id'] == 'magetunisia_importexport'))
            {
                foreach ($this->moduleslist as $moduleName)
                {
                    if ($section['id'] !== 'magetunisia_importexport')
                    {
                        continue;
                    }
                    $dynamicGroups = $this->getGroups($moduleName, $section['id']);
                    if (!empty($dynamicGroups))
                    {
                        $config['config']['system']['sections'][$sectionId]['children'] = $dynamicGroups + $section['children'];
                    }
                    break;
                }
            }
        }
        return [$config];
    }

    /**
     * Get Group
     *
     * @param string $moduleName
     * @param string $sectionName
     * @return array
     */
    protected function getGroups($moduleName, $sectionName): array
    {
        $defaultFieldOptions = [
            'type'          => 'text',
            'showInDefault' => '1',
            'showInWebsite' => '1',
            'showInStore'   => '1',
            'sortOrder'     => 1,
            'module_name'   => $moduleName,
            "sortOrder" => 100,
            '_elementType'  => 'field',
            'path'          => $sectionName . '/fields_map_product'
        ];
        $fields = [];
        foreach ($this->getMappedAttributesField() as $id => $option)
        {
            $fields[$id] = array_merge($defaultFieldOptions, ['id' => $id], $option);
        }
        return [
            'fields_map_product' => [
                'id'            => 'fields_map_product',
                'label'         => __('Mapping Attributes'),
                'showInDefault' => '1',
                'showInWebsite' => '1',
                'showInStore'   => '1',
                "sortOrder" => 3,
                '_elementType'  => 'group',
                'path'          => $sectionName,
                'children'      => $fields
            ]
        ];
    }

    /**
     * Mapping attributes fields
     *
     * @return array
     */
    protected function getMappedAttributesField(): array
    {
      $attributes = [];
      $attributesInfo = $this->_attributeFactory->create();
      foreach($attributesInfo as $attribute)
      {
        $attributes[$attribute->getAttributeCode()] = [
            'label'          => $attribute->getAttributeCode(),
            'frontend_class' => $attribute->getAttributeCode(),
            'show'           => 1,
            'tooltip'  => __("Mapping attribute {$attribute->getAttributeCode()} with an column in your csv."),
        ];
      }
      return $attributes;
    }
}