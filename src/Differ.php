<?php

namespace Differ\Differ;

use function Differ\Parsers\parse;
use function Functional\sort;
use function Differ\Formatters\format;

function getFileData(string $path): string
{
    $data = file_get_contents($path);
    if ($data === false) {
        throw new \Exception("Can't read file");
    }

    return $data;
}

function makeTree(object $data1, object $data2): array
{
    $keys = array_unique(array_merge(array_keys((array) $data1), array_keys((array) $data2)));
    $sortKeys = sort($keys, fn ($left, $right) => strcmp($left, $right));

    $tree = array_map(function ($key) use ($data1, $data2) {

        $oldValue = $data1->$key ?? null;
        $newValue = $data2->$key ?? null;

        if (is_object($oldValue) && is_object($newValue)) {
            return [
                'key' => $key,
                'status' => 'nested',
                'children' => makeTree($oldValue, $newValue)
            ];
        }
        if (!property_exists($data2, $key)) {
            return [
                'key' => $key,
                'status' => 'deleted',
                'oldValue' => $oldValue
            ];
        }
        if (!property_exists($data1, $key)) {
            return [
                'key' => $key,
                'status' => 'added',
                'newValue' => $newValue
            ];
        }
        if ($oldValue !== $newValue) {
            return [
                'key' => $key,
                'status' => 'changed',
                'oldValue' => $oldValue,
                'newValue' => $newValue
            ];
        }
        return [
            'key' => $key,
            'status' => 'unchanged',
            'oldValue' => $oldValue
        ];
    }, $sortKeys);

    return $tree;
}

function genDiff(string $path1, string $path2, string $format = 'stylish'): string
{
    $data1 = parse(getFileData($path1), pathinfo($path1, PATHINFO_EXTENSION));
    $data2 = parse(getFileData($path2), pathinfo($path2, PATHINFO_EXTENSION));

    $tree = makeTree($data1, $data2);

    return format($tree, $format);
}
