<?php


namespace MageTunisia\ImportExport\Model\Config\Source;

/**
 * Used in creating options for getting product type value.
 */
class Producttype
{
    /**
     * @var \Magento\Catalog\Model\ProductTypes\ConfigInterface
     */
    protected $_config;

    /**
     * @var \Magento\Framework\Module\Manager
     */
    protected $manager;
    /**
     * Construct.
     *
     * @param \Magento\Catalog\Model\ProductTypes\ConfigInterface $config
     */
    public function __construct(
        \Magento\Framework\Module\Manager $manager,
        \Magento\Catalog\Model\ProductTypes\ConfigInterface $config
    ) {
        $this->manager = $manager;
        $this->_config = $config;
    }
    /**
     * Options getter.
     *
     * @return array
     */
    public function toOptionArray()
    {
        $data = [
            ['value' => 'simple', 'label' => __('Simple')],
            ['value' => 'downloadable', 'label' => __('Downloadable')],
            ['value' => 'virtual', 'label' => __('Virtual')],
            ['value' => 'configurable', 'label' => __('Configurable')]
        ];
        if ($this->manager->isEnabled('MageTunisia_ImportExport')) {
            array_push($data, ['value' => 'bundle', 'label' => __('Bundle Product')]);
        }
        if ($this->manager->isEnabled('MageTunisia_ImportExport')) {
            array_push($data, ['value' => 'grouped', 'label' => __('Grouped Product')]);
        }
        return $data;
    }
}
