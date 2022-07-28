<?php

use Dotenv\Dotenv;
use Importer\DatabaseConnectionFactory;
use Importer\Enum\Report;
use Importer\Importer;
use Importer\Parser\CSVParser;
use Importer\Storage\MySQLStorage;

include __DIR__.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

$filename = __DIR__.DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR.'report.csv';
$mapping = [
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

// database credentials are located in docker-compose.yml and loaded by phpdotenv package
$dbConnection = (new DatabaseConnectionFactory())->makeConnection(
    getenv('DB_HOST'),
    getenv('DB_NAME'),
    getenv('DB_USER'),
    getenv('DB_PASS')
);

$result = (new Importer(
    new MySQLStorage($dbConnection),
    new CSVParser,
))->process($filename, $mapping);


echo sprintf(
    'Imported %d merchants, %d batches, and %d transactions | Memory used: %.2f Mb'.PHP_EOL,
    $result->getMerchantCount(),
    $result->getBatchCount(),
    $result->getTransactionCount(),
    memory_get_peak_usage() / 1024 / 1024
);
