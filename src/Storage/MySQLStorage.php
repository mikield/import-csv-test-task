<?php

namespace Importer\Storage;

use Closure;
use Importer\Contracts\StorageContract;
use Importer\DTO\ParsedDataDTO;
use Importer\DTO\StoredDataDTO;
use Importer\Enum\Report;
use PDO;
use PDOException;
use PDOStatement;

class MySQLStorage implements StorageContract
{
    public function __construct(private readonly PDO $connection)
    {
        $this->connection->beginTransaction();
    }

    public function process(ParsedDataDTO $data): StoredDataDTO
    {
        $storedMerchantsCount = $this->storeMerchants($data->merchants);
        $storedBatchesCount = $this->storeBatches($data->batches);
        $storedTransactionsCount = $this->storeTransactions($data->transactions);

        $this->connection->commit();

        return new StoredDataDTO(
            $storedMerchantsCount,
            $storedBatchesCount,
            $storedTransactionsCount
        );
    }

    private function storeMerchants(array $merchants): int
    {
        $storedMerchantsCount = 0;

        $query = 'INSERT INTO merchants (id, name) VALUES (:id, :name)';
        $stmt = $this->connection->prepare($query);

        $this->store($merchants, $stmt, static function ($stmt, $merchant) use (&$storedMerchantsCount) {
            $stmt->execute([
                ':id' => $merchant[Report::MERCHANT_ID->value],
                ':name' => $merchant[Report::MERCHANT_NAME->value],
            ]);
            $storedMerchantsCount++;
        });

        return $storedMerchantsCount;
    }

    private function storeBatches(array $batches): int
    {
        $storedBatchesCount = 0;

        $query = 'INSERT INTO batches (id, merchant_id, ref, date) VALUES (:id, :merchantId, :ref, :date)';
        $stmt = $this->connection->prepare($query);

        $this->store($batches, $stmt, static function ($stmt, $batch) use (&$storedBatchesCount) {
            $stmt->execute([
                ':id' => $batch[Report::MERCHANT_ID->value].$batch[Report::BATCH_DATE->value].$batch[Report::BATCH_REF_NUM->value],
                ':merchantId' => $batch[Report::MERCHANT_ID->value],
                ':ref' => $batch[Report::BATCH_REF_NUM->value],
                ':date' => $batch[Report::BATCH_DATE->value],
            ]);
            $storedBatchesCount++;
        });

        return $storedBatchesCount;
    }

    private function storeTransactions(array $transactions): int
    {
        $storedTransactionsCount = 0;

        $query = 'INSERT INTO transactions (type, card_type, card_number, amount, date, batch_id, merchant_id) VALUES (:type, :card_type, :card_number, :amount, :date, :batchId, :merchantId)';
        $stmt = $this->connection->prepare($query);

        $this->store($transactions, $stmt, static function ($stmt, $transaction) use (&$storedTransactionsCount) {
            $stmt->execute([
                ':type' => $transaction[Report::TRANSACTION_TYPE->value],
                ':card_type' => $transaction[Report::TRANSACTION_CARD_TYPE->value],
                ':card_number' => $transaction[Report::TRANSACTION_CARD_NUMBER->value],
                ':amount' => $transaction[Report::TRANSACTION_AMOUNT->value],
                ':date' => $transaction[Report::TRANSACTION_DATE->value],
                ':batchId' => $transaction[Report::MERCHANT_ID->value].$transaction[Report::BATCH_DATE->value].$transaction[Report::BATCH_REF_NUM->value],
                ':merchantId' => $transaction[Report::MERCHANT_ID->value],
            ]);
            $storedTransactionsCount++;
        });

        return $storedTransactionsCount;
    }

    public function __destruct()
    {
        if ($this->connection->inTransaction()) {
            $this->connection->rollBack();
        }
    }

    private function store(array $data, PDOStatement $stmt, Closure $closure): void
    {
        foreach ($data as $row) {
            try {
                $closure($stmt, $row);
            } catch (PDOException $exception) {
                // Ignore duplicate entries
                if ($exception->getCode() === '23000') {
                    continue;
                }
                throw $exception;
            }
        }
    }
}