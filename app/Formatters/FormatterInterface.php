<?php

namespace App\Formatters;

interface FormatterInterface
{
    public function format(array $pages) : false|string;
}