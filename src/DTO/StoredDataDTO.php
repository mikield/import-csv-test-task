<?php

namespace Importer\DTO;

class StoredDataDTO
{
    public function __construct(
        public readonly int $storedMerchantsCount,
        public readonly int $storedBatchesCount,
        public readonly int $storedTransactionsCount,
    )
    {
    }

}