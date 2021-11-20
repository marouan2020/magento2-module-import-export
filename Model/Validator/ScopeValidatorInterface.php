<?php
/**
 * Copyright © MageTunisia GmbH. All rights reserved.
 * See LICENSE.md bundled with this module for license details.
 */

namespace MageTunisia\ConfigImportExport\Model\Validator;

/**
 * Interface ScopeValidatorInterface
 *
 * @package MageTunisia\ConfigImportExport\Model\Validator
 */
interface ScopeValidatorInterface
{
    /**
     * Validates the given scope and scope id
     *
     * @param  string $scope   Scope
     * @param  int    $scopeId Scope ID
     * @return bool
     */
    public function validate($scope, $scopeId);
}
