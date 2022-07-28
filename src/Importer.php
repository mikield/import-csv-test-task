<?php

namespace Importer;

use Importer\Contracts\ParserContract;
use Importer\Contracts\StorageContract;
use Importer\DTO\ResultDTO;

/**
 * Class Importer
 */
class Importer
{
    /**
     * Importer constructor.
     */
    public function __construct(private readonly StorageContract $storage, private readonly ParserContract $parser)
    {
    }

    /**
     * Imports a given report
     *
     * @param  string  $filename  Full path to the report
     * @param  string[]  $mapping  Report mapping
     *
     * @return ResultDTO Result of the import process
     */
    public function process(string $filename, array $mapping): ResultDTO
    {
        $this->parser->setMapping($mapping);

        $data = $this->parser->parse($filename);

        $updatedData = $this->storage->process($data);

        return new ResultDTO(
            $updatedData->storedTransactionsCount,
            $updatedData->storedMerchantsCount,
            $updatedData->storedBatchesCount
        );
    }

}
