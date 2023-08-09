<?php

namespace Differ\Differ;

use function Differ\Parsers\convertToArray;

function makeString(array $array): array
{
    $result = [];
    foreach ($array as $key => $value) {
        if (is_bool($value)) {
            $result[$key] = $value ? 'true' : 'false';
        } elseif (is_null($value)) {
            $result[$key] = 'null';
        } else {
            $result[$key] = $value;
        }
    }

    return $result;
}

function getDataForDiff(string $pathToFile): array
{
    $fileContent = file_get_contents($pathToFile);
    $extension = pathinfo($pathToFile, PATHINFO_EXTENSION);

    return convertToArray($fileContent, $extension);
}

function genDiff(string $pathToFile1, string $pathToFile2)
{
    $dataFromFile1 = getDataForDiff($pathToFile1);
    $dataFromFile2 = getDataForDiff($pathToFile2);
    $dataFromFile1 = makeString($dataFromFile1);
    $dataFromFile2 = makeString($dataFromFile2);

    $keys = array_merge(array_keys($dataFromFile1), array_keys($dataFromFile2));
    sort($keys);
    $diff = [];
    foreach ($keys as $key) {
        if (!array_key_exists($key, $dataFromFile1)) {
            $diff[$key] = 'added';
        } elseif (!array_key_exists($key, $dataFromFile2)) {
            $diff[$key] = 'deleted';
        } elseif ($dataFromFile1[$key] !== $dataFromFile2[$key]) {
            $diff[$key] = 'changed';
        } else {
            $diff[$key] = 'unchanged';
        }
    };

    $result = ["{"];

    foreach ($diff as $key => $value) {
        switch ($value) {
            case 'added':
                $result[] = "  + {$key}: {$dataFromFile2[$key]}";
                break;
            case 'deleted':
                $result[] = "  - {$key}: {$dataFromFile1[$key]}";
                break;
            case 'changed':
                $result[] = "  - {$key}: {$dataFromFile1[$key]}";
                $result[] = "  + {$key}: {$dataFromFile2[$key]}";
                break;
            case 'unchanged':
                $result[] = "    {$key}: {$dataFromFile1[$key]}";
                break;
            default:
                throw new Exception("Error! Invalid value!");
        }
    }
    $result[] = "}";

    return implode("\n", $result);
}
