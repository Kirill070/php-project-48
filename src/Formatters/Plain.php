<?php

namespace Differ\Formatters\Plain;

function render(array $tree, string $valuePath = ''): string
{
    $string = array_map(function ($node) use ($valuePath) {

        $key = $node['key'];
        $status = $node['status'];
        $oldValue = isset($node['oldValue']) ? convertString($node['oldValue']) : null;
        $newValue = isset($node['newValue']) ? convertString($node['newValue']) : null;

        $fullValuePath = $valuePath === '' ? $key : "$valuePath.$key";

        switch ($status) {
            case 'nested':
                return render($node['children'], $fullValuePath);
            case 'unchanged':
                return;
            case 'added':
                return "Property '$fullValuePath' was added with value: $newValue";
            case 'deleted':
                return "Property '$fullValuePath' was removed";
            case 'changed':
                return "Property '$fullValuePath' was updated. From $oldValue to $newValue";
            default:
                throw new \Exception("Unknown node status: '$status'");
        }
    }, $tree);
    $result = array_filter($string);

    return implode("\n", $result);
}

function convertString(mixed $value): string
{
    if ($value === 'false') {
        return 'false';
    }

    if ($value === 'true') {
        return 'true';
    }

    if ($value === 'null') {
        return 'null';
    }

    if (is_string($value)) {
        return "'$value'";
    }

    if (is_array($value)) {
        return "[complex value]";
    }

    return $value;
}
