/*!40101 SET @OLD_CHARACTER_SET_CLIENT = @@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS = @@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION = @@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_UNIQUE_CHECKS = @@UNIQUE_CHECKS, UNIQUE_CHECKS = 0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS = @@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS = 0 */;
/*!40101 SET @OLD_SQL_MODE = @@SQL_MODE, SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES = @@SQL_NOTES, SQL_NOTES = 0 */;


DROP TABLE IF EXISTS `batches`;
CREATE TABLE `batches`
(
    `id`          varchar(255)   NOT NULL,
    `merchant_id` bigint(18)     NOT NULL,
    `ref`         decimal(24, 0) NOT NULL,
    `date`        date           NOT NULL,
    UNIQUE KEY `id` (`id`) USING BTREE,
    KEY `merchantId` (`merchant_id`),
    CONSTRAINT `batches_ibfk_1` FOREIGN KEY (`merchant_id`) REFERENCES `merchants` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = latin1;

DROP TABLE IF EXISTS `merchants`;
CREATE TABLE `merchants`
(
    `id`   bigint(18)   NOT NULL,
    `name` varchar(100) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = latin1;

DROP TABLE IF EXISTS `transactions`;
CREATE TABLE `transactions`
(
    `type`        varchar(20)    NOT NULL,
    `card_type`   varchar(2)     NOT NULL,
    `card_number` varchar(20)    NOT NULL,
    `amount`      decimal(19, 4) NOT NULL,
    `date`        date           NOT NULL,
    `batch_id`    varchar(255)   NOT NULL,
    `merchant_id` bigint(18)     NOT NULL,
    KEY `batch_id` (`batch_id`),
    KEY `merchant_id` (`merchant_id`),
    CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`batch_id`) REFERENCES `batches` (`id`),
    CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`merchant_id`) REFERENCES `merchants` (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = latin1;



/*!40101 SET SQL_MODE = @OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS = @OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS = @OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT = @OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS = @OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION = @OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES = @OLD_SQL_NOTES */;