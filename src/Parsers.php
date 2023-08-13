<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

function convertContentToArray(string $fileContent, string $extension): array
{
    switch ($extension) {
        case 'json':
            return $array = json_decode($fileContent, true);
        case 'yaml':
            return $array = Yaml::parse($fileContent);
        case 'yml':
            return $array = Yaml::parse($fileContent);
        default:
            throw new \Exception("Unknown extension: '{$extension}'");
    }
}
