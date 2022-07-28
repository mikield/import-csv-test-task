<?php

namespace Importer\Storage;

use Importer\Contracts\StorageContract;
use Importer\DTO\ParsedDataDTO;
use Importer\DTO\StoredDataDTO;

class NullStorage implements StorageContract
{
    public function process(ParsedDataDTO $data): StoredDataDTO
    {
        return new StoredDataDTO(
            $data->getMerchantsCount(),
            $data->getBatchesCount(),
            $data->getTransactionsCount()
        );
    }
}