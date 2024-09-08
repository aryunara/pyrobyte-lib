<?php

namespace App\Formatters;

class CSVFormatter implements FormatterInterface
{
    public function format(array $pages): false|string
    {
        $headers = [];
        $output = fopen('php://memory', 'r+');

        foreach ($pages as $page) {
            if (empty($headers)) {
                $headers = array_keys($page);
                fputcsv($output, $headers, ';');
            }
            fputcsv($output, $page, ';');
        }

        rewind($output);
        $contents = stream_get_contents($output);
        fclose($output);

        return $contents;
    }
}