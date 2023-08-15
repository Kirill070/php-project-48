### Hexlet tests and linter status:
[![Actions Status](https://github.com/Kirill070/php-project-48/workflows/hexlet-check/badge.svg)](https://github.com/Kirill070/php-project-48/actions)<br>
<a href="https://codeclimate.com/github/Kirill070/php-project-48/maintainability"><img src="https://api.codeclimate.com/v1/badges/295354b96a25dd51311e/maintainability" /></a><br>
[![Differ CI](https://github.com/Kirill070/php-project-48/actions/workflows/my-check.yml/badge.svg)](https://github.com/Kirill070/php-project-48/actions/workflows/my-check.yml)<br>
[![Test Coverage](https://api.codeclimate.com/v1/badges/295354b96a25dd51311e/test_coverage)](https://codeclimate.com/github/Kirill070/php-project-48/test_coverage)<br>

## Описание:

Вычислитель отличий – программа, определяющая разницу между двумя структурами данных.
Возможности утилиты:
  - Поддержка разных входных форматов: yaml и json
  - Генерация отчета в виде plain text, stylish и json

Пример использования:

```sh
# формат plain
gendiff --format plain path/to/file.yml another/path/file.json

Property 'common.follow' was added with value: false
Property 'group1.baz' was updated. From 'bas' to 'bars'
Property 'group2' was removed

# формат stylish
gendiff filepath1.json filepath2.json

{
  + follow: false
    setting1: Value 1
  - setting2: 200
  - setting3: true
  + setting3: {
        key: value
    }
  + setting4: blah blah
  + setting5: {
        key5: value5
    }
}
``` 

## Записи примера работы пакета:

### Запись сравнения плоских .json-файлов

[![asciicast](https://asciinema.org/a/wNR5yK7yML6RCVGqzGwKmZK1y.svg)](https://asciinema.org/a/wNR5yK7yML6RCVGqzGwKmZK1y)

### Запись сравнения плоских .yml-файлов

[![asciicast](https://asciinema.org/a/did6HEXY1JVgqe0uX0twfRWTM.svg)](https://asciinema.org/a/did6HEXY1JVgqe0uX0twfRWTM)

### Запись сравнения файлов, имеющих вложенную структуру

[![asciicast](https://asciinema.org/a/tVe4J0pBBEq140sKpSEmynvDf.svg)](https://asciinema.org/a/tVe4J0pBBEq140sKpSEmynvDf)

### Запись сравнения файлов, имеющих вложенную структуру, с выводом в формате 'plain'

[![asciicast](https://asciinema.org/a/XuOH5IkAFow9Q7TJIJ6rXtXXO.svg)](https://asciinema.org/a/XuOH5IkAFow9Q7TJIJ6rXtXXO)

### Запись сравнения файлов, имеющих вложенную структуру, с выводом в формате 'json'

[![asciicast](https://asciinema.org/a/4Wbkb9QL2aXl5qo26pfou2hX0.svg)](https://asciinema.org/a/4Wbkb9QL2aXl5qo26pfou2hX0)