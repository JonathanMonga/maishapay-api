CREATE TABLE `customers` (
  `customer_id` int(12) UNSIGNED NOT NULL,
  `customer_uuid` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `country_iso_code` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `phone_area_code` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `number_phone` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `names` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `customer_type` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `number_of_account`int(12) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `customer_status` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `created` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci