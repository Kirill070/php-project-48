<?php

namespace Differ\Formatters\Stylish;

function render(array $tree, int $depth = 0): string
{
    $indent = str_repeat('    ', $depth);

    $lines = array_map(function ($node) use ($indent, $depth) {

        $key = $node['key'];
        $status = $node['status'];
        $oldValue = $node['oldValue'] ?? null;
        $newValue = $node['newValue'] ?? null;

        switch ($status) {
            case 'nested':
                return "$indent    $key: " . render($node['children'], $depth + 1);
            case 'unchanged':
                return "$indent    $key: " . convertString($oldValue, $depth);
            case 'added':
                return "$indent  + $key: " . convertString($newValue, $depth);
            case 'deleted':
                return "$indent  - $key: " . convertString($oldValue, $depth);
            case 'changed':
                return "$indent  - $key: " . convertString($oldValue, $depth) . "\n"
                . "$indent  + $key: " . convertString($newValue, $depth);
            default:
                throw new \Exception("Unknown node status: '$status'");
        }
    }, $tree);
    $output = ["{", ...$lines, "$indent}"];
    return implode("\n", $output);
}

function convertString(mixed $value, int $depth): string
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }

    if (is_null($value)) {
        return 'null';
    }

    if (is_string($value)) {
        return "$value";
    }
    if (is_array($value)) {
        $indent = str_repeat('    ', $depth + 1);

        $keys = array_keys($value);
        $string = array_map(function ($key, $value) use ($indent, $depth) {
            $result = convertString($value, $depth + 1);
            return "$indent    $key: $result";
        }, $keys, $value);
        return '{' . "\n" . implode("\n", $string) . "\n" . $indent . '}';
    }

    return $value;
}
