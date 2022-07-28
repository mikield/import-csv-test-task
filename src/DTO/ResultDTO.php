<?php

namespace Importer\DTO;

/**
 * Class Result
 */
class ResultDTO
{
    /** @var int Number of imported merchants */
    private int $merchants;

    /** @var int Number of imported batches */
    private int $batches;

    /** @var int Number of imported transactions */
    private int $transactions;

    public function __construct(int $transactions = 0, int $merchants = 0, int $batches = 0)
    {
        $this->transactions = $transactions;
        $this->merchants = $merchants;
        $this->batches = $batches;
    }

    /**
     * Gets a number of imported merchants
     *
     * @return int Number of imported merchants
     */
    public function getMerchantCount(): int
    {
        return $this->merchants;
    }

    /**
     * Gets a number of imported batches
     *
     * @return int Number of imported batches
     */
    public function getBatchCount(): int
    {
        return $this->batches;
    }

    /**
     * Gets a number of imported transactions
     *
     * @return int Number of imported transactions
     */
    public function getTransactionCount(): int
    {
        return $this->transactions;
    }
}
