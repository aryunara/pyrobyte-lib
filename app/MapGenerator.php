<?php

namespace App;

use App\Exceptions\CreatingException;
use App\Exceptions\FormatterException;
use App\Exceptions\InvalidDataException;
use App\Formatters\CSVFormatter;
use App\Formatters\FormatterInterface;
use App\Formatters\JSONFormatter;
use App\Formatters\XMLFormatter;

class MapGenerator
{
    private array $pages;

    private string $fileType;

    private string $filePath;

    private FormatterInterface $formatter;

    private array $formatters = [
        'xml' => XMLFormatter::class,
        'json' => JSONFormatter::class,
        'csv' => CSVFormatter::class,
    ];

    /**
     * @throws InvalidDataException
     */
    public function __construct(array $pages, string $fileType, string $filePath)
    {
        $this->pages = $pages;
        $this->fileType = $fileType;
        $this->filePath = $filePath;
        $this->formatter = $this->getFormatter($fileType);
    }

    /**
     * @throws InvalidDataException
     */
    private function getFormatter(string $fileType) : FormatterInterface
    {
        if (isset($this->formatters[$fileType])) {
            $formatterClass = $this->formatters[$fileType];

            return new $formatterClass();
        }

        throw new InvalidDataException("Invalid file type.");
    }

    /**
     * @throws InvalidDataException
     * @throws FormatterException
     * @throws CreatingException
     */
    public function generate(): void
    {
        if (empty($this->pages)) {
            throw new InvalidDataException("Invalid pages array.");
        }

        $map = $this->formatter->format($this->pages);
        if (!$map) {
            throw new FormatterException("Error while formatting pages.");
        }

        if (!$this->filePath) {
            throw new InvalidDataException("Invalid file path.");
        }

        $directory = dirname($this->filePath);

        if (!is_dir($directory)) {
            $newDirectory = mkdir($directory, 0755, true);
            if (!$newDirectory) {
                throw new CreatingException('Error while creating a directory.');
            }
        }

        if (!is_writable($directory)) {
            throw new CreatingException('Permission denied.');
        }

        $mapFile = file_put_contents($this->filePath, $map);
        if (!$mapFile) {
            throw new CreatingException('Error while creating a file.');
        }
    }
}