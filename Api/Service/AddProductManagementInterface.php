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

namespace MageTunisia\ImportExport\Api\Service;

/**
 * AddProductManagementInterface interface
 * @package \MageTunisia\ImportExport\Api\Service
 */
interface AddProductManagementInterface
{
    /**
     * @param mixed $params
     * 
     * @return int
     */
    public function postAddProduct($params);
}