<?php

namespace Source\Utils;

class Path
{
    public static function resolve(string ...$path): string
    {
        return implode(DIRECTORY_SEPARATOR, $path);
    }
}