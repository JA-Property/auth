CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `initials` varchar(255) NOT NULL,
  `display_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('admin','staff','customer') NOT NULL,
  `last_login_attempt` datetime DEFAULT NULL,
  `last_successful_login` datetime DEFAULT NULL,
  `status` enum('active','inactive','suspended') DEFAULT 'active',
  `onboarded` tinyint(1) DEFAULT 0,
  `verified` tinyint(1) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci

CREATE TABLE `password_resets` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(255) NOT NULL,
  `token` VARCHAR(255) NOT NULL,
  `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `email` (`email`)
);


CREATE TABLE `customers` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  
  -- Basic Customer Identification
  `account_number`         VARCHAR(50) NOT NULL,
  `customer_type`          VARCHAR(50) NOT NULL,  -- e.g. 'Residential', 'Commercial', 'Other'
  `sub_type`               VARCHAR(50) DEFAULT NULL, -- e.g. 'Office', 'Retail', etc.
  
  -- Names
  `first_name`             VARCHAR(100) DEFAULT NULL, -- Residential first name
  `last_name`              VARCHAR(100) DEFAULT NULL, -- Residential last name
  `primary_first_name`     VARCHAR(100) DEFAULT NULL, -- Commercial/Other contact first name
  `primary_last_name`      VARCHAR(100) DEFAULT NULL, -- Commercial/Other contact last name
  `display_name`           VARCHAR(255) DEFAULT NULL, -- Could store company name or combined name
  
  -- Referral
  `referral_source`        VARCHAR(100) DEFAULT NULL, -- e.g. 'Internet', 'Friend', 'Other'
  `referral_other`         VARCHAR(255) DEFAULT NULL, -- text if 'Other' is selected
  
  -- Billing Types
  `billing_bill_type`      VARCHAR(50) DEFAULT NULL,  -- e.g. 'Visit', 'Pre', 'Post', 'Other'
  `billing_other`          VARCHAR(50) DEFAULT NULL,  -- if the user selects 'Other' billing plan
  `terms`                  VARCHAR(50) DEFAULT NULL,  -- e.g. 'Net15', 'Net30'
  `statement_period`       VARCHAR(50) DEFAULT NULL,  -- e.g. 'Weekly', 'Monthly'
  `delivery_method`        VARCHAR(50) DEFAULT NULL,  -- e.g. 'Email', 'Mail', 'Both'
  
  -- Tax & Discounts
  `tax_exempt`             TINYINT(1) DEFAULT 0,      -- 0 = No, 1 = Yes
  `tax_exempt_id`          VARCHAR(50) DEFAULT NULL,  -- if tax_exempt=1
  `discount_amount`        DECIMAL(5,2) DEFAULT '0.00',  -- numeric discount value
  `discount_type`          VARCHAR(50) DEFAULT 'Flat',  -- 'Flat' or 'Percent'
  
  -- Primary Contact Info
  `primary_email`          VARCHAR(255) NOT NULL,
  `primary_phone`          VARCHAR(50)  NOT NULL,
  
  -- Billing Address
  `billing_address`        VARCHAR(255) NOT NULL,
  `billing_city`           VARCHAR(100) NOT NULL,
  `billing_state`          VARCHAR(100) NOT NULL,
  `billing_zip`            VARCHAR(20)  NOT NULL,
  `billing_country`        VARCHAR(100) NOT NULL,
  
  -- Communication Preferences
  `comm_email`             TINYINT(1) DEFAULT 0, -- 0 = unchecked, 1 = checked
  `comm_sms`               TINYINT(1) DEFAULT 0,
  `comm_phone`             TINYINT(1) DEFAULT 0,
  
  -- Additional
  `notes`                  TEXT DEFAULT NULL,
  
  -- Tracking / Audit Fields
  `status` ENUM('active','inactive','suspended') DEFAULT 'active',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  PRIMARY KEY (`id`),
  -- Optionally ensure account_number is unique if your system requires it:
  -- UNIQUE KEY `unique_account_number` (`account_number`)
  
  KEY `idx_customers_email` (`primary_email`)  -- optional, for faster lookups on email
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
