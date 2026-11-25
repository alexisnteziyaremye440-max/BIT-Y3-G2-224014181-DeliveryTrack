-- Online Delivery Tracking System — schema (3NF, InnoDB, utf8mb4)
CREATE DATABASE IF NOT EXISTS delivery_tracker CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE delivery_tracker;
DROP TABLE IF EXISTS deliveries, orders, customers, users;

CREATE TABLE users (
  user_id       INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  username      VARCHAR(50)  NOT NULL UNIQUE,
  email         VARCHAR(100) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role          ENUM('admin','customer','rider') NOT NULL DEFAULT 'customer',
  created_at    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE customers (
  customer_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id     INT UNSIGNED NULL UNIQUE,            -- optional link: registered customer login (NULL = walk-in)
  full_name   VARCHAR(100) NOT NULL,
  phone       VARCHAR(20)  NOT NULL,
  address     VARCHAR(255) NOT NULL,
  CONSTRAINT fk_cust_user FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE orders (
  order_id    INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  customer_id INT UNSIGNED NOT NULL,
  details     VARCHAR(255) NOT NULL,               -- what was ordered
  order_date  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  total       DECIMAL(10,2) NOT NULL CHECK (total > 0),
  status      ENUM('pending','assigned','in_transit','delivered','cancelled') NOT NULL DEFAULT 'pending',
  CONSTRAINT fk_order_cust FOREIGN KEY (customer_id) REFERENCES customers(customer_id) ON DELETE CASCADE,
  INDEX idx_order_status (status)
) ENGINE=InnoDB;

CREATE TABLE deliveries (
  delivery_id  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  order_id     INT UNSIGNED NOT NULL UNIQUE,       -- one delivery per order
  rider_id     INT UNSIGNED NOT NULL,              -- FK -> users (role = rider)
  picked_at    DATETIME NULL,
  delivered_at DATETIME NULL,
  status       ENUM('assigned','picked_up','delivered','failed') NOT NULL DEFAULT 'assigned',
  CONSTRAINT fk_del_order FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE,
  CONSTRAINT fk_del_rider FOREIGN KEY (rider_id) REFERENCES users(user_id)  ON DELETE RESTRICT
) ENGINE=InnoDB;
