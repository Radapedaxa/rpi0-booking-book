# Installation and Server Stack Deployment Instructions

This guide outlines how to deploy the Book Booking application natively onto a clean Raspberry Pi Zero 2W running DietPi OS.

## 📦 1. Core Server Stack Installation
Run the automated DietPi software engine manager to configure the native software bundles:
```bash
sudo dietpi-software
```
* Navigate to **Browse Software** -> Select **LAMP** (Apache2, PHP, MariaDB).
* Select **Confirm** -> **Install** to deploy the server stack engines.

## 🔌 2. USB Link-Local Networking Configuration
To run the server offline without wireless routers, modify the MicroSD card boot parameters:
1. In `/boot/dietpi.txt` or system hardware managers, ensure the USB gadget ethernet driver module parameters are active (`dtoverlay=dwc2`).
2. The network interface automatically binds to the direct host target bridge IP configuration.

## 🗄️ 3. Relational Database Initial Schema Setup
Initialize the SQL relational containers and map Member 2's structural schema configurations:
```bash
# Log into MariaDB database prompt terminal console
sudo mysql

# Execute database initialization commands inside prompt
CREATE DATABASE booking_db;
CREATE USER 'admin'@'localhost' IDENTIFIED BY 'password123';
GRANT ALL PRIVILEGES ON booking_db.* TO 'admin'@'localhost';
FLUSH PRIVILEGES;
EXIT;

# Import the structural setup schemas directly from the project directory
sudo mysql booking_db < schema.sql
```

## 🚀 4. App Directory Launch Paths
Move all production PHP, HTML, and Markdown assets straight into the active webserver directory:
```bash
sudo cp *.php /var/www/
sudo cp *.php /var/www/html/
```
Target Link-Local Gateway Endpoint Address: `http://169.254.1.1`
