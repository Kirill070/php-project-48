<?php

namespace Differ\Formatters;

use Differ\Formatters\Stylish;
use Differ\Formatters\Plain;
use Differ\Formatters\Json;

function format(array $tree, string $format): string
{
    switch ($format) {
        case 'stylish':
            return Stylish\render($tree);
        case 'plain':
            return Plain\render($tree);
        case 'json':
            return Json\render($tree);
        default:
            throw new \Exception("Unknown format: '$format'");
    }
}
