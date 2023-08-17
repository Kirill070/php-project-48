<?php

namespace Differ\Differ;

use function Differ\Parsers\parse;
use function Functional\sort;
use function Differ\Formatters\format;

function makeString(mixed $value): mixed
{
    if (!is_array($value)) {
        if (is_bool($value)) {
            $result = $value ? 'true' : 'false';
        } elseif (is_null($value)) {
            $result = 'null';
        } else {
            $result = $value;
        }
        return $result;
    }

    return $value;
}

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
    $keysArrays = array_merge(array_keys($data1), array_keys($data2));
    $keysArray = array_unique($keysArrays);
    $sortKeysArray = sort($keysArray, fn ($left, $right) => strcmp($left, $right));

    $result = array_map(function ($key) use ($data1, $data2) {

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
                'oldValue' => makeString($oldValue)
            ];
        }
        if (!key_exists($key, $data1)) {
            return [
                'key' => $key,
                'status' => 'added',
                'newValue' => makeString($newValue)
            ];
        }
        if ($oldValue !== $newValue) {
            return [
                'key' => $key,
                'status' => 'changed',
                'oldValue' => makeString($oldValue),
                'newValue' => makeString($newValue)
            ];
        }
        return [
            'key' => $key,
            'status' => 'unchanged',
            'oldValue' => makeString($oldValue)
        ];
    }, $sortKeysArray);

    return $result;
}

function genDiff(string $path1, string $path2, string $format = 'stylish'): string
{
    $data1 = getFileData($path1);
    $data2 = getFileData($path2);

    $result = makeTree($data1, $data2);

    return format($result, $format);
}
