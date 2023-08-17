<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

function parse(string $data, string $format): array
{
    switch ($format) {
        case 'json':
            return $array = json_decode($data, true);
        case 'yaml':
        case 'yml':
            return $array = Yaml::parse($data);
        default:
            throw new \Exception("Unknown extension: '$format'");
    }
}
