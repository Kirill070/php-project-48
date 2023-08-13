<?php

namespace Differ\Formatters\Plain;

function formatPlain(array $ast, string $valuePath = ''): string
{
    $string = array_map(function ($node) use ($valuePath) {
        $fullValuePath = $valuePath === '' ? $node['key'] : "{$valuePath}.{$node['key']}";

        switch ($node['status']) {
            case 'nested':
                return formatPlain($node['children'], $fullValuePath);
            case 'unchanged':
                return;
            case 'added':
                $newValue = convertString($node['newValue']);
                return "Property '{$fullValuePath}' was added with value: {$newValue}";
            case 'deleted':
                return "Property '{$fullValuePath}' was removed";
            case 'changed':
                $newValue = convertString($node['newValue']);
                $oldValue = convertString($node['oldValue']);
                return "Property '{$fullValuePath}' was updated. From {$oldValue} to {$newValue}";
            default:
                throw new \Exception("Unknown node status: {$node['status']}");
        }
    }, $ast);
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
        return "'{$value}'";
    }

    if (is_array($value)) {
        return "[complex value]";
    }

    return $value;
}
