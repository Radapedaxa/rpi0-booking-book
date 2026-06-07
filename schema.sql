-- ==========================================
-- DATABASE SCHEMA FOR BOOK BOOKING SYSTEM
-- MEMBER 2: DATABASE ARCHITECTURE
-- ==========================================

-- Drop tables if they exist to allow clean re-runs of the script
DROP TABLE IF EXISTS bookings;
DROP TABLE IF EXISTS books;
DROP TABLE IF EXISTS users;

-- 1. USERS TABLE
-- Stores user credentials and details for Member 3's PHP session management
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL, -- Storing hashed passwords for best practice
    email VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. BOOKS TABLE
-- Stores the library inventory
CREATE TABLE books (
    book_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    author VARCHAR(100) NOT NULL,
    isbn VARCHAR(20) UNIQUE,
    available_copies INT DEFAULT 1
);

-- 3. BOOKINGS TABLE
-- Links users to books, tracks booking dates, return deadlines, and status
CREATE TABLE bookings (
    booking_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    book_id INT NOT NULL,
    booking_date DATE NOT NULL,
    return_date DATE DEFAULT NULL, -- Filled when the book is actually returned
    due_date DATE NOT NULL,        -- The deadline for the return
    status ENUM('active', 'returned') DEFAULT 'active',
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (book_id) REFERENCES books(book_id) ON DELETE CASCADE
);

-- ==========================================
-- MOCK DATA INSERT STATEMENTS
-- ==========================================

-- Inserting Mock Users 
-- (Passwords are mock hashes, useful for Member 3's login logic)
INSERT INTO users (username, password, email) VALUES
('johndoe', '$2y$10$e0MYzXyDx...', 'john@example.com'),
('janedoe', '$2y$10$e0MYzXyDx...', 'jane@example.com'),
('alexsmith', '$2y$10$e0MYzXyDx...', 'alex@example.com');

-- Inserting Mock Books
INSERT INTO books (title, author, isbn, available_copies) VALUES
('The Hobbit', 'J.R.R. Tolkien', '9780007440833', 3),
('To Kill a Mockingbird', 'Harper Lee', '9780446310789', 1),
('1984', 'George Orwell', '9780451524935', 5),
('The Great Gatsby', 'F. Scott Fitzgerald', '9780743273565', 0); -- Out of stock for testing

-- Inserting Mock Bookings
-- Booking 1: An active booking by John Doe (User 1) for The Hobbit (Book 1)
INSERT INTO bookings (user_id, book_id, booking_date, due_date, status) VALUES
(1, 1, '2026-06-01', '2026-06-15', 'active');

-- Booking 2: A book already returned by Jane Doe (User 2)
INSERT INTO bookings (user_id, book_id, booking_date, return_date, due_date, status) VALUES
(2, 3, '2026-05-10', '2026-05-20', '2026-05-24', 'returned');

-- Booking 3: An active booking by Alex Smith (User 3) that is past its due date (overdue testing)
INSERT INTO bookings (user_id, book_id, booking_date, due_date, status) VALUES
(3, 2, '2026-05-15', '2026-05-29', 'active');
