<?php

namespace Differ\Differ;

use function Differ\Parsers\convertContentToArray;
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

function getDataForDiff(string $pathToFile): array
{
    $fileContent = file_get_contents($pathToFile);
    if ($fileContent === false) {
        throw new \Exception("Can't read file");
    }

    $extension = pathinfo($pathToFile, PATHINFO_EXTENSION);

    return convertContentToArray($fileContent, $extension);
}

function makeAst(array $dataFromFile1, array $dataFromFile2): array
{
    $keysArrays = array_merge(array_keys($dataFromFile1), array_keys($dataFromFile2));
    $keysArray = array_unique($keysArrays);
    $sortKeysArray = sort($keysArray, fn ($left, $right) => strcmp($left, $right));

    $result = array_map(function ($key) use ($dataFromFile1, $dataFromFile2) {

        $oldValue = $dataFromFile1[$key] ?? null;
        $newValue = $dataFromFile2[$key] ?? null;

        if (is_array($oldValue) && is_array($newValue)) {
            return [
                'key' => $key,
                'status' => 'nested',
                'children' => makeAst($oldValue, $newValue)
            ];
        }
        if (!key_exists($key, $dataFromFile2)) {
            return [
                'key' => $key,
                'status' => 'deleted',
                'oldValue' => makeString($oldValue)
            ];
        }
        if (!key_exists($key, $dataFromFile1)) {
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

function genDiff(string $pathToFile1, string $pathToFile2, string $format = 'stylish'): string
{
    $dataFromFile1 = getDataForDiff($pathToFile1);
    $dataFromFile2 = getDataForDiff($pathToFile2);

    $result = makeAst($dataFromFile1, $dataFromFile2);

    return format($result, $format);
}
