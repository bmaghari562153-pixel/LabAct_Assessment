-- schema.sql  |  Database: assessment_db
-- Import via phpMyAdmin (Import tab) or: mysql -u root < schema.sql

CREATE DATABASE IF NOT EXISTS assessment_db
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE assessment_db;

DROP TABLE IF EXISTS payments;
DROP TABLE IF EXISTS booking_tools;
DROP TABLE IF EXISTS bookings;
DROP TABLE IF EXISTS tools;
DROP TABLE IF EXISTS services;
DROP TABLE IF EXISTS clients;

CREATE TABLE clients (
  client_id   INT AUTO_INCREMENT PRIMARY KEY,
  full_name   VARCHAR(100) NOT NULL,
  email       VARCHAR(100) NULL,
  phone       VARCHAR(30)  NULL,
  address     VARCHAR(255) NULL,
  created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE services (
  service_id   INT AUTO_INCREMENT PRIMARY KEY,
  service_name VARCHAR(100) NOT NULL,
  description  TEXT NULL,
  hourly_rate  DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  is_active    TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB;

CREATE TABLE tools (
  tool_id      INT AUTO_INCREMENT PRIMARY KEY,
  tool_name    VARCHAR(100) NOT NULL,
  description  VARCHAR(255) NULL,
  daily_cost   DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  is_available TINYINT(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB;

CREATE TABLE bookings (
  booking_id           INT AUTO_INCREMENT PRIMARY KEY,
  client_id            INT NOT NULL,
  service_id           INT NOT NULL,
  booking_date         DATE NOT NULL,
  hours                INT NOT NULL DEFAULT 1,
  hourly_rate_snapshot DECIMAL(10,2) NOT NULL,
  total_cost           DECIMAL(10,2) NOT NULL,
  status               VARCHAR(20) NOT NULL DEFAULT 'PENDING',
  created_at           TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_booking_client  FOREIGN KEY (client_id)  REFERENCES clients(client_id),
  CONSTRAINT fk_booking_service FOREIGN KEY (service_id) REFERENCES services(service_id)
) ENGINE=InnoDB;

CREATE TABLE booking_tools (
  booking_id INT NOT NULL,
  tool_id    INT NOT NULL,
  quantity   INT NOT NULL DEFAULT 1,
  PRIMARY KEY (booking_id, tool_id),
  CONSTRAINT fk_bt_booking FOREIGN KEY (booking_id) REFERENCES bookings(booking_id) ON DELETE CASCADE,
  CONSTRAINT fk_bt_tool    FOREIGN KEY (tool_id)    REFERENCES tools(tool_id)
) ENGINE=InnoDB;

CREATE TABLE payments (
  payment_id     INT AUTO_INCREMENT PRIMARY KEY,
  booking_id     INT NOT NULL,
  amount         DECIMAL(10,2) NOT NULL,
  payment_method VARCHAR(30) NOT NULL DEFAULT 'CASH',
  payment_date   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_payment_booking FOREIGN KEY (booking_id) REFERENCES bookings(booking_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Sample data
INSERT INTO clients (full_name, email, phone) VALUES
  ('Juan Dela Cruz', 'juan@example.com', '09171234567'),
  ('Maria Santos',   'maria@example.com', '09181234567');

INSERT INTO services (service_name, description, hourly_rate, is_active) VALUES
  ('Cleaning',     'General home cleaning', 250.00, 1),
  ('Plumbing',     'Pipe repair and installation', 400.00, 1),
  ('Electrical',   'Wiring and fixture repair', 450.00, 1),
  ('Gardening',    'Lawn and garden maintenance', 200.00, 0);

INSERT INTO tools (tool_name, description, daily_cost) VALUES
  ('Vacuum Cleaner', 'Industrial vacuum', 150.00),
  ('Pipe Wrench Set', 'Plumbing tool set', 100.00),
  ('Ladder', '8-foot ladder', 80.00);
