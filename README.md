# rpi0-booking-book
OPEN SOURCE PROJECT final group project

# 📚 Bare-Metal Book Booking Portal (Raspberry Pi Zero 2W Stack)

A lightweight, zero-framework, server-side PHP web application integrated with a MariaDB relational database. This system is optimized explicitly to operate entirely on low-power hardware in an offline configuration.

## 🛠️ Project Architecture & Technical Constraints
* **No Frameworks:** Developed entirely using raw, vanilla PHP scripting logic blocks and semantic HTML/CSS layouts. No server-side or client-side frameworks are used.
* **Hardware Target:** Raspberry Pi Zero 2W platform running DietPi Linux.
* **Network Constraint:** Operates seamlessly via a dedicated USB OTG Link-Local communication matrix with Wi-Fi disabled.
* **Database Normalization:** Fully structured up to 3rd Normal Form (3NF) containing cascading transaction constraints.
* **Compliance Safeguard:** Features an automated Overdue System Warning engine that dynamically flags accounts breaching return parameters.

## 📂 Repository Documentation Matrix
According to the final project submission guidelines, use the links below to access the specific documentation modules:
* **[Contributors.md](./Contributors.md)**: Outlines individual group member student registries and role allocations.
* **[Installation.md](./Installation.md)**: Detailed technical log showing raw Linux bash shell execution strings for stack deployment.
* **[UserGuide.md](./UserGuide.md)**: Operational manual detailing account registration, catalog queries, and booking workflows.
* **[AdminGuide.md](./AdminGuide.md)**: System administrator reference guide showing network routing parameters and database upkeep.

## 🚀 Live Presentation Launch Steps
To evaluate this project during the live demonstration, execute the following setup sequence:
1. Connect the target Raspberry Pi Zero 2W via a single Micro-USB OTG data cable into the host laptop machine.
2. Ensure the host laptop operating system network adapter binds to the manual static IP gateway block: `169.254.1.2`.
3. Open a fresh browser tab or an Incognito Window and navigate directly to the system entrance gate:
   `http://169.254.1.1`

## 🏷️ Version Freeze
This repository has been officially frozen for grading assessment under the mandatory tag: `FINAL_PRESENTATION`.
