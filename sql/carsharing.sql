-- Erstelle die Benutzer-Tabelle
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `password` VARCHAR(100) NOT NULL,
  `address` VARCHAR(255),
  `phone_number` VARCHAR(20)
);

-- Füge Beispiel-Einträge zur Benutzer-Tabelle hinzu
INSERT INTO `users` (`username`, `email`, `password`, `address`, `phone_number`)
VALUES
('max_mustermann', 'max@example.com', 'pass123', '123 Main St, City', '123-456-7890'),
('lisa_meier', 'lisa@example.com', 'pass456', '456 Elm St, Town', '987-654-3210');

-- Erstelle die Fahrzeuge-Tabelle
CREATE TABLE IF NOT EXISTS `vehicles` (
  `vehicle_id` INT AUTO_INCREMENT PRIMARY KEY,
  `make` VARCHAR(50) NOT NULL,
  `model` VARCHAR(50) NOT NULL,
  `year` INT NOT NULL,
  `color` VARCHAR(20),
  `license_plate` VARCHAR(20),
  `availability` ENUM('available', 'unavailable') NOT NULL DEFAULT 'available'
);

-- Füge Beispiel-Einträge zur Fahrzeuge-Tabelle hinzu
INSERT INTO `vehicles` (`make`, `model`, `year`, `color`, `license_plate`, `availability`)
VALUES
('Toyota', 'Prius', 2019, 'Blue', 'ABC123', 'available'),
('Honda', 'Civic', 2020, 'Red', 'XYZ789', 'available');

-- Erstelle die Buchungen-Tabelle
CREATE TABLE IF NOT EXISTS `bookings` (
  `booking_id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `vehicle_id` INT NOT NULL,
  `booking_start` DATETIME NOT NULL,
  `booking_end` DATETIME NOT NULL,
  `total_cost` DECIMAL(10, 2) NOT NULL,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`),
  FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles`(`vehicle_id`)
);

-- Füge Beispiel-Einträge zur Buchungen-Tabelle hinzu
INSERT INTO `bookings` (`user_id`, `vehicle_id`, `booking_start`, `booking_end`, `total_cost`)
VALUES
(1, 1, '2024-04-21 09:00:00', '2024-04-23 09:00:00', 120.00),
(2, 2, '2024-04-22 10:00:00', '2024-04-24 10:00:00', 140.00);

CREATE TABLE comments (
    comment_id INT AUTO_INCREMENT PRIMARY KEY,
    vehicle_id INT,
    user_id INT,
    comment_text TEXT,
    star_rating INT,
    comment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (vehicle_id) REFERENCES vehicles(vehicle_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id) -- If comments are tied to users
);