# Book Booking Portal - Administration & Maintenance Guide

This document outlines structural system settings, configuration states, and standard data backup protocols for server upkeep.

## 🎛️ System Configuration & Infrastructure Stack
* **Hardware Platform:** Raspberry Pi Zero 2W
* **Operating System Environment:** DietPi Linux Engine
* **Web Server Gateway:** Apache2 Daemon (Listening on Port 80)
* **Script Compilation Runtime:** PHP Engine
* **Relational Storage Container:** MariaDB Database

## ⚙️ Network Diagnostics (Link-Local Interface)
The server interface drops wireless data processing and defaults to link-local fallback connectivity.
* **Server USB Loop Address:** `169.254.1.1`
* **Assigned Host Laptop Static Pointer:** `169.254.1.2`
* **Subnet Isolation Mask:** `255.255.255.0`

## 🗄️ Database Upkeep & Backup Control Loops
To secure inventory configurations, execute structural exports directly via the system console terminal:
```bash
# Export the database state to a local storage backup file
sudo mysqldump -u admin -p password123 booking_db > backup_db.sql
```
