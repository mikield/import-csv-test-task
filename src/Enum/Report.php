<?php

namespace Importer\Enum;

/**
 * Enum Report
 *
 * @package Importer
 */
enum Report: string
{
    case MERCHANT_ID = 'mid';             // digits only, up to 18 digits
    case MERCHANT_NAME = 'dba';             // string, max length - 100
    case BATCH_DATE = 'batch_date';      // YYYY-MM-DD
    case BATCH_REF_NUM = 'batch_ref_num';   // digits only, up to 24 digits
    case TRANSACTION_DATE = 'trans_date';      // YYYY-MM-DD
    case TRANSACTION_TYPE = 'trans_type';      // string, max length - 20
    case TRANSACTION_CARD_TYPE = 'trans_card_type'; // string, max length - 2, possible values - VI/MC/AX and so on
    case TRANSACTION_CARD_NUMBER = 'trans_card_num';  // string, max length - 20
    case TRANSACTION_AMOUNT = 'trans_amount';    // amount, negative values are possible
}
