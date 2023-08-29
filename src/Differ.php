<?php

namespace Differ\Differ;

use function Differ\Parsers\parse;
use function Functional\sort;
use function Differ\Formatters\format;

function getFileData(string $path): array
{
    $data = file_get_contents($path);
    if ($data === false) {
        throw new \Exception("Can't read file");
    }

    $format = pathinfo($path, PATHINFO_EXTENSION);

    return parse($data, $format);
}

function makeTree(array $data1, array $data2): array
{
    $keys = array_unique(array_merge(array_keys($data1), array_keys($data2)));
    $sortKeys = sort($keys, fn ($left, $right) => strcmp($left, $right));

    $tree = array_map(function ($key) use ($data1, $data2) {

        $oldValue = $data1[$key] ?? null;
        $newValue = $data2[$key] ?? null;

        if (is_array($oldValue) && is_array($newValue)) {
            return [
                'key' => $key,
                'status' => 'nested',
                'children' => makeTree($oldValue, $newValue)
            ];
        }
        if (!key_exists($key, $data2)) {
            return [
                'key' => $key,
                'status' => 'deleted',
                'oldValue' => $oldValue
            ];
        }
        if (!key_exists($key, $data1)) {
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
    $data1 = getFileData($path1);
    $data2 = getFileData($path2);

    $tree = makeTree($data1, $data2);

    return format($tree, $format);
}
