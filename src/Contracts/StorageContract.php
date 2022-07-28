<?php

namespace Importer\Contracts;

use Importer\DTO\ParsedDataDTO;
use Importer\DTO\StoredDataDTO;

interface StorageContract
{
    /**
     * @param  ParsedDataDTO  $data
     *
     * @return StoredDataDTO
     */
    public function process(ParsedDataDTO $data): StoredDataDTO;
}