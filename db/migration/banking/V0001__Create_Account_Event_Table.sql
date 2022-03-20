CREATE TABLE `account_events`
(
    `code`            varchar(36)    NOT NULL,
    `aggregate_type`  varchar(100)   DEFAULT NULL,
    `aggregate_id`    varchar(36)    DEFAULT NULL,
    `event_name`      varchar(100)   DEFAULT NULL,
    `sequence_number` int            DEFAULT NULL,
    `revision`        int            DEFAULT NULL,
    `payload`         json           DEFAULT NULL,
    `occurred_on`     timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`code`),
    UNIQUE KEY `ACC_EV_UK01` (`aggregate_type`,`aggregate_id`, `event_name`, `revision`, `occurred_on`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
