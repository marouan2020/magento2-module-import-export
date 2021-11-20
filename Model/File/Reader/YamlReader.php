<?php
/**
 * Copyright © MageTunisia GmbH. All rights reserved.
 * See LICENSE.md bundled with this module for license details.
 */

namespace MageTunisia\ConfigImportExport\Model\File\Reader;

use Symfony\Component\Yaml\Yaml as SymfonyYaml;

/**
 * Class YamlReader
 *
 * @package MageTunisia\ConfigImportExport\Model\File\Reader
 */
class YamlReader extends AbstractReader
{
    /**
     * @param string $fileName
     * @return mixed
     */
    public function parse($fileName)
    {
        $content = SymfonyYaml::parseFile($fileName);

        return is_array($content)
            ? $this->normalize($content)
            : $content;
    }
}
