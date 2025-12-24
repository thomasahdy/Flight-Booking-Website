-- Flight Booking Website - Seed Data
-- Run this after the database is created to populate test data

USE flight_booking;

-- Insert test companies
INSERT INTO users (email, password, name, tel, user_type, account_balance) VALUES
('emirates@airline.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Emirates Airlines', '+971-4-214-4444', 'company', 15000.00),
('delta@airline.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Delta Airlines', '+1-800-221-1212', 'company', 12000.00),
('lufthansa@airline.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Lufthansa', '+49-69-86799799', 'company', 18000.00);

-- Insert company profiles
INSERT INTO companies (user_id, bio, address, location) VALUES
(1, 'Emirates is one of the world\'s leading airlines, offering luxury travel experiences.', '123 Airport Road, Dubai', 'Dubai, UAE'),
(2, 'Delta Air Lines is a major American airline, with an extensive domestic and international network.', '1030 Delta Blvd, Atlanta, GA', 'Atlanta, USA'),
(3, 'Lufthansa is the largest German airline and one of the world\'s largest airline groups.', 'Von-Gablenz-Straße 2-6, Cologne', 'Cologne, Germany');

-- Insert test passengers (password for all: 'password')
INSERT INTO users (email, password, name, tel, user_type, account_balance) VALUES
('john.doe@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'John Doe', '+1-555-0101', 'passenger', 5000.00),
('sarah.smith@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Sarah Smith', '+1-555-0102', 'passenger', 7500.00),
('mike.johnson@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Mike Johnson', '+1-555-0103', 'passenger', 3200.00),
('emma.wilson@email.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Emma Wilson', '+44-20-7946-0958', 'passenger', 6000.00);

-- Insert passenger profiles
INSERT INTO passengers (user_id) VALUES (4), (5), (6), (7);

-- Insert test flights
INSERT INTO flights (company_id, flight_name, flight_id, itinerary, fees, max_passengers, registered_passengers, pending_passengers, start_date, start_time, end_date, end_time, status) VALUES
-- Emirates flights
(1, 'Dubai to London', 'EK001', 'Dubai (DXB) → London Heathrow (LHR)', 850.00, 300, 2, 0, '2025-01-15', '02:30:00', '2025-01-15', '07:15:00', 'active'),
(1, 'Dubai to New York', 'EK002', 'Dubai (DXB) → New York JFK (JFK)', 1200.00, 350, 1, 1, '2025-01-20', '08:45:00', '2025-01-20', '14:30:00', 'active'),
(1, 'Dubai to Tokyo', 'EK003', 'Dubai (DXB) → Tokyo Narita (NRT)', 950.00, 280, 0, 0, '2025-02-01', '22:00:00', '2025-02-02', '11:30:00', 'active'),

-- Delta flights
(2, 'New York to Los Angeles', 'DL101', 'New York JFK (JFK) → Los Angeles (LAX)', 350.00, 180, 1, 0, '2025-01-18', '06:00:00', '2025-01-18', '09:30:00', 'active'),
(2, 'Atlanta to Paris', 'DL102', 'Atlanta (ATL) → Paris Charles de Gaulle (CDG)', 780.00, 250, 0, 0, '2025-01-25', '17:45:00', '2025-01-26', '08:15:00', 'active'),
(2, 'Los Angeles to Tokyo', 'DL103', 'Los Angeles (LAX) → Tokyo Narita (NRT)', 890.00, 220, 0, 0, '2025-02-05', '11:30:00', '2025-02-06', '15:45:00', 'active'),

-- Lufthansa flights
(3, 'Frankfurt to New York', 'LH400', 'Frankfurt (FRA) → New York JFK (JFK)', 820.00, 300, 1, 0, '2025-01-22', '10:15:00', '2025-01-22', '13:45:00', 'active'),
(3, 'Munich to Singapore', 'LH401', 'Munich (MUC) → Singapore Changi (SIN)', 1100.00, 280, 0, 0, '2025-02-10', '23:30:00', '2025-02-11', '17:15:00', 'active'),
(3, 'Berlin to Dubai', 'LH402', 'Berlin (BER) → Dubai (DXB)', 650.00, 200, 0, 0, '2025-02-15', '14:20:00', '2025-02-15', '23:45:00', 'active');

-- Insert test bookings
INSERT INTO bookings (passenger_id, flight_id, status, payment_type, amount) VALUES
-- John Doe's bookings
(4, 1, 'confirmed', 'account', 850.00),
(4, 4, 'confirmed', 'account', 350.00),

-- Sarah Smith's bookings
(5, 2, 'confirmed', 'account', 1200.00),
(5, 7, 'confirmed', 'account', 820.00),

-- Mike Johnson's bookings
(6, 2, 'pending', 'cash', 1200.00);

-- Insert test messages
INSERT INTO messages (sender_id, receiver_id, flight_id, message) VALUES
-- Conversation between John Doe and Emirates
(4, 1, 1, 'Hello, I would like to know about baggage allowance for this flight.'),
(1, 4, 1, 'Hello John! For economy class, you are allowed 2 pieces of checked baggage, each up to 23kg.'),
(4, 1, 1, 'Thank you! Can I add extra baggage if needed?'),
(1, 4, 1, 'Yes, you can purchase additional baggage allowance online or at the airport.'),

-- Conversation between Sarah and Delta
(5, 2, NULL, 'Hi, do you offer special meals on international flights?'),
(2, 5, NULL, 'Hello Sarah! Yes, we offer various special meals including vegetarian, vegan, kosher, and halal options. Please request at least 24 hours before departure.'),

-- Conversation between Mike and Emirates
(6, 1, 2, 'Is this flight still available? I see it says pending for my booking.'),
(1, 6, 2, 'Hello! Yes, the flight is available. Your booking is pending because you selected cash payment. Please visit our office to complete the payment.');

-- Update account balances after bookings
UPDATE users SET account_balance = account_balance + 850.00 WHERE id = 1;  -- Emirates gets payment from John
UPDATE users SET account_balance = account_balance + 350.00 WHERE id = 2;  -- Delta gets payment from John
UPDATE users SET account_balance = account_balance + 1200.00 WHERE id = 1; -- Emirates gets payment from Sarah
UPDATE users SET account_balance = account_balance + 820.00 WHERE id = 3;  -- Lufthansa gets payment from Sarah

UPDATE users SET account_balance = account_balance - 850.00 WHERE id = 4;  -- John paid for Emirates flight
UPDATE users SET account_balance = account_balance - 350.00 WHERE id = 4;  -- John paid for Delta flight
UPDATE users SET account_balance = account_balance - 1200.00 WHERE id = 5; -- Sarah paid for Emirates flight
UPDATE users SET account_balance = account_balance - 820.00 WHERE id = 5;  -- Sarah paid for Lufthansa flight

-- Display summary
SELECT 'Seed data inserted successfully!' as Status;
SELECT '=== TEST ACCOUNTS (All passwords: password) ===' as Info;
SELECT 'COMPANIES:' as Type;
SELECT email, name FROM users WHERE user_type = 'company';
SELECT 'PASSENGERS:' as Type;
SELECT email, name, CONCAT('$', account_balance) as balance FROM users WHERE user_type = 'passenger';
