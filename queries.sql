-- Query 1: display all transactions for a batch (filter: merchant_id + batch_date + batch_ref_num)
SELECT * FROM transactions WHERE merchant_id = ? AND batch_date = ? AND batch_ref_num = ?

-- Query 2: display statistics for a batch (filter: merchant_id + batch_date + batch_ref_num)
--          grouped by transaction card type
SELECT card_type, COUNT(*) AS count, SUM(amount) AS sum FROM transactions LEFT JOIN batches ON transactions.batch_id=batches.id WHERE merchant_id=? AND batch_date=? AND batch_ref_num=? GROUP BY card_type;

-- Query 3: display top 10 merchants (by total amount) for a given date range (batch_date)
--          merchant id, merchant name, total amount, number of transactions
SELECT merchant_id, merchant_name, SUM(amount) AS total_amount, COUNT(*) AS total_transactions FROM transactions LEFT JOIN batches ON transactions.batch_id=batches.id WHERE batch_date>=? AND batch_date<=? GROUP BY merchant_id ORDER BY total_amount DESC LIMIT 10;
