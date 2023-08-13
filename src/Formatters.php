<?php

namespace Differ\Formatters;

use function Differ\Formatters\Stylish\formatStylish;
use function Differ\Formatters\Plain\formatPlain;
use function Differ\Formatters\Json\formatJson;

function format(array $ast, string $format): string
{
    switch ($format) {
        case 'stylish':
            return formatStylish($ast);
        case 'plain':
            return formatPlain($ast);
        case 'json':
            return formatJson($ast);
        default:
            throw new \Exception("Unknown format: {$format}");
    }
}
