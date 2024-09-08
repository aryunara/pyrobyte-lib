<?php

namespace Formatters;

interface FormatterInterface
{
    public function format(array $pages) : false|string;
}