<?php

namespace j0hnys\Vista\Base\Utilities;

class WordCaseConverter
{
    public function camelCaseToSnakeCase(string $string): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $string));
    }

    public function snakeAndKebabCaseToCamelCase(string $string): string
    {
        $string = ucwords(str_replace(array('-', '_'), ' ', $string));
        $string = str_replace(' ', '', $string);
        return lcfirst($string);
    }

    public function snakeAndKebabCaseToPascalCase(string $string): string
    {
        $string = ucwords(str_replace(array('-', '_'), ' ', $string));
        $string = str_replace(' ', '', $string);
        return ucfirst($string);
    }
}
