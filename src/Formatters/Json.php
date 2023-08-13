<?php

namespace Differ\Formatters\Json;

function formatJson(array $ast): string
{
    return json_encode($ast);
}
