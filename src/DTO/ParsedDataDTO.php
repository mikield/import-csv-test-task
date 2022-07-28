<?php

namespace Importer\DTO;

class ParsedDataDTO
{
    public function __construct(
        public readonly array $merchants,
        public readonly array $batches,
        public readonly array $transactions,
    ) {
    }

    /**
     * @return array
     */
    public function getMerchantIds(): array
    {
        return array_keys($this->merchants);
    }

    /**
     * @return array
     */
    public function getBatchesIds(): array
    {
        return array_keys($this->batches);
    }

    /**
     * @return int
     */
    public function getTransactionsCount(): int
    {
        return count($this->transactions);
    }

    public function getMerchantsCount(): int
    {
        return count($this->merchants);
    }

    public function getBatchesCount(): int
    {
        return count($this->batches);
    }
}