<?php

namespace Importer\Parser;

use Generator;
use Importer\Contracts\ParserContract;
use Importer\DTO\ParsedDataDTO;
use Importer\Enum\Report;
use RuntimeException;
use SplFileObject;

class CSVParser implements ParserContract
{
    private array $mapping;

    /**
     * @param  array  $mapping
     *
     * @return void
     */
    public function setMapping(array $mapping): void
    {
        $this->mapping = $mapping;
    }

    /**
     * @param  string  $filepath
     *
     * @return ParsedDataDTO
     */
    public function parse(string $filepath): ParsedDataDTO
    {
        $file = $this->openFile($filepath);
        $mappedKeys = $this->mapKeys($file, $this->mapping);

        $data = [
            "merchants" => [],
            "batches" => [],
            "transactions" => [],
        ];

        foreach ($this->getRow($file, $mappedKeys) as $row) {
            //  To make it faster we can use https://www.php.net/manual/en/philosophy.parallel.php here.
            //  That will allow to process multiple rows batches in parallel.
            $this->processRow($row, $data);
        }

        return new ParsedDataDTO(
            $data['merchants'],
            $data['batches'],
            $data['transactions'],
        );
    }

    /**
     * @throws RuntimeException
     */
    private function openFile(string $filename): SplFileObject
    {
        if (!file_exists($filename)) {
            throw new RuntimeException('File not found');
        }

        return new SplFileObject($filename);
    }

    /**
     * @param  SplFileObject  $file
     * @param  array  $mapping
     *
     * @return array
     */
    private function mapKeys(SplFileObject $file, array $mapping): array
    {
        return array_map(static fn($header) => array_search($header, $mapping, true), $file->fgetcsv());
    }

    /**
     *
     * @param  SplFileObject  $fileStream
     * @param $mappedKeys
     *
     * @return Generator
     */
    protected function getRow(SplFileObject $fileStream, $mappedKeys): Generator
    {
        while (!$fileStream->eof()) {
            $row = $fileStream->fgetcsv();
            if ($row[0] === null) {
                continue;
            }
            yield array_combine($mappedKeys, $row);
        }
    }

    /**
     * @param  array  $row
     * @param  array  $data
     *
     * @return void
     */
    private function processRow(array $row, array &$data): void
    {
        $data['merchants'][$row[Report::MERCHANT_ID->value]] = $row;
        $data['batches'][$row[Report::MERCHANT_ID->value].$row[Report::BATCH_DATE->value].$row[Report::BATCH_REF_NUM->value]] = $row;
        $data["transactions"][] = $row;
    }
}