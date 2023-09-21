<?php

namespace Differ\Formatters\Plain;

function render(array $tree, array $valuePath = []): string
{
    $lines = array_map(function ($node) use ($valuePath) {

        $status = $node['status'];
        $oldValue = $node['oldValue'] ?? null;
        $newValue = $node['newValue'] ?? null;
        $fullValuePath = array_merge($valuePath, [$node['key']]);

        $path = implode('.', $fullValuePath);

        switch ($status) {
            case 'nested':
                return render($node['children'], $fullValuePath);
            case 'unchanged':
                return;
            case 'added':
                return "Property '$path' was added with value: " . convertString($newValue);
            case 'deleted':
                return "Property '$path' was removed";
            case 'changed':
                return "Property '$path' was updated. From " .
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

    return $value;
}
