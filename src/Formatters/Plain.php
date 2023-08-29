<?php

namespace Differ\Formatters\Plain;

function render(array $tree, string $valuePath = ''): string
{
    $lines = array_map(function ($node) use ($valuePath) {

        $key = $node['key'];
        $status = $node['status'];
        $oldValue = $node['oldValue'] ?? null;
        $newValue = $node['newValue'] ?? null;

        $fullValuePath = $valuePath === '' ? $key : "$valuePath.$key";

        switch ($status) {
            case 'nested':
                return render($node['children'], $fullValuePath);
            case 'unchanged':
                return;
            case 'added':
                return "Property '$fullValuePath' was added with value: " . convertString($newValue);
            case 'deleted':
                return "Property '$fullValuePath' was removed";
            case 'changed':
                return "Property '$fullValuePath' was updated. From " .
                    convertString($oldValue) . " to " . convertString($newValue);
            default:
                throw new \Exception("Unknown node status: '$status'");
        }
    }, $tree);
    $output = array_filter($lines);

    return implode("\n", $output);
}

function convertString(mixed $value): string
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }

    if (is_null($value)) {
        return 'null';
    }

    if (is_string($value)) {
        return "'$value'";
    }

    if (is_array($value)) {
        return "[complex value]";
    }

    return '$value';
}
