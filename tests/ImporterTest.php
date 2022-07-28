<?php

namespace Tests;

use Importer\Enum\Report;
use Importer\Importer;
use Importer\Parser\CSVParser;
use Importer\Storage\NullStorage;
use PHPUnit\Framework\TestCase;

/**
 * Class ImporterTest
 *
 * @package Tests
 */
class ImporterTest extends TestCase
{
    /**
     * Tests Importer::process
     */
    public function testProcess(): void
    {
        $importer = $this->createImporter();
        $result = $importer->process($this->getFile(), $this->getMapping());

        // 2 merchants
        $this->assertEquals(2, $result->getMerchantCount());

        // with 3 batches
        $this->assertEquals(3, $result->getBatchCount());

        // with 5 transactions
        $this->assertEquals(5, $result->getTransactionCount());
    }

    /**
     * Creates an importer instance for testing purposes
     */
    private function createImporter(): Importer
    {
        return new Importer(
            new NullStorage,
            new CSVParser,
        );
    }

    /**
     * Gets a sample report
     *
     * @return string Full path to a sample report
     */
    private function getFile(): string
    {
        return __DIR__.DIRECTORY_SEPARATOR.'samples'.DIRECTORY_SEPARATOR.'small.csv';
    }

    /**
     * Gets a sample mapping
     *
     * @return string[] Sample mapping
     */
    private function getMapping(): array
    {
        return [
            Report::TRANSACTION_DATE->value => 'Transaction Date',
            Report::TRANSACTION_TYPE->value => 'Transaction Type',
            Report::TRANSACTION_CARD_TYPE->value => 'Transaction Card Type',
            Report::TRANSACTION_CARD_NUMBER->value => 'Transaction Card Number',
            Report::TRANSACTION_AMOUNT->value => 'Transaction Amount',
            Report::BATCH_DATE->value => 'Batch Date',
            Report::BATCH_REF_NUM->value => 'Batch Reference Number',
            Report::MERCHANT_ID->value => 'Merchant ID',
            Report::MERCHANT_NAME->value => 'Merchant Name',
        ];
    }
}
