<?php

namespace App\Formatters;

class JSONFormatter implements FormatterInterface
{
    public function format(array $pages) : false|string
    {
        return json_encode($pages, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }
}