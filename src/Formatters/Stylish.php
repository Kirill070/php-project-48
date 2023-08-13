<?php

namespace Differ\Formatters\Stylish;

function formatStylish(array $ast, int $depth = 0): string
{
    $indent = str_repeat('    ', $depth);

    $string = array_map(function ($node) use ($indent, $depth) {

        switch ($node['status']) {
            case 'nested':
                return "{$indent}    {$node['key']}: " . formatStylish($node['children'], $depth + 1);
            case 'unchanged':
                return "{$indent}    {$node['key']}: " . convertString($node['oldValue'], $depth);
            case 'added':
                return "{$indent}  + {$node['key']}: " . convertString($node['newValue'], $depth);
            case 'deleted':
                return "{$indent}  - {$node['key']}: " . convertString($node['oldValue'], $depth);
            case 'changed':
                return "{$indent}  - {$node['key']}: " . convertString($node['oldValue'], $depth) . "\n"
                . "{$indent}  + {$node['key']}: " . convertString($node['newValue'], $depth);
            default:
                throw new \Exception("Unknown node status: {$status}");
        }
    }, $ast);
    $result = ["{", ...$string, "{$indent}}"];
    return implode("\n", $result);
}

function convertString(mixed $value, int $depth): string
{
    if (!is_array($value)) {
        return $value;
    }
    $indent = str_repeat('    ', $depth + 1);

    $keys = array_keys($value);
    $string = array_map(function ($key, $value) use ($indent, $depth) {
        $result = convertString($value, $depth + 1);
        return "{$indent}    {$key}: {$result}";
    }, $keys, $value);
    return '{' . "\n" . implode("\n", $string) . "\n" . $indent . '}';
}
