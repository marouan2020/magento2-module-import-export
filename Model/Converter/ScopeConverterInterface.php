<?php
/**
 * Copyright © MageTunisia GmbH. All rights reserved.
 * See LICENSE.md bundled with this module for license details.
 */

namespace MageTunisia\ConfigImportExport\Model\Converter;

/**
 * Interface ScopeConverterInterface
 *
 * @package MageTunisia\ConfigImportExport\Model\Converter
 */
interface ScopeConverterInterface
{
    const SCOPE_STORES = 'stores';
    const SCOPE_WEBSITES = 'websites';

    /**
     * Converts a string scope to integer scope id if needed
     *
     * @param string|int   $scopeId Scope ID
     * @param string|mixed $scope   Scope
     * @return int
     */
    public function convert($scopeId, $scope);
}
