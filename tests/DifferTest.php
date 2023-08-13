<?php

namespace Gendiff\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\gendiff;

class DifferTest extends TestCase
{
    public function testGendiff(): void
    {
        $expected = file_get_contents("tests/fixtures/resultGendiff.txt");
        $this->assertEquals($expected, gendiff("tests/fixtures/file1.json", "tests/fixtures/file2.json"));
        $this->assertEquals($expected, gendiff("tests/fixtures/filepath1.yml", "tests/fixtures/filepath2.yml"));
    }

    public function testPlain(): void
    {
        $expected = file_get_contents("tests/fixtures/resultPlain.txt");
        $this->assertEquals($expected, gendiff("tests/fixtures/file1.json", "tests/fixtures/file2.json", 'plain'));
        $this->assertEquals($expected, gendiff("tests/fixtures/filepath1.yml", "tests/fixtures/filepath2.yml", 'plain'));
    }
}
