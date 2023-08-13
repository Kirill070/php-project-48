<?php

namespace Differ\Formatters\Plain;

function formatPlain(array $ast, $valuePath = ''): string
{
    $string = array_map(function ($node) use ($valuePath) {
        $fullValuePath = $valuePath === '' ? $node['key'] : "{$valuePath}.{$node['key']}";

        switch ($node['status']) {
            case 'nested':
                return formatPlain($node['children'], $fullValuePath);
            case 'unchanged':
                return;
            case 'added':
                $node['newValue'] = convertString($node['newValue']);
                return "Property '{$fullValuePath}' was added with value: {$node['newValue']}";
            case 'deleted':
                return "Property '{$fullValuePath}' was removed";
            case 'changed':
                $node['newValue'] = convertString($node['newValue']);
                $node['oldValue'] = convertString($node['oldValue']);
                return "Property '{$fullValuePath}' was updated. From {$node['oldValue']} to {$node['newValue']}";
                throw new \Exception("Unknown node status: {$status}");
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

    if ($value === '0') {
        return 0;
    }

    if (!is_array($value)) {
        return "'{$value}'";
    }

    return "[complex value]";
}
