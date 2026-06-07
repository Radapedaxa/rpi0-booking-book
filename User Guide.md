# Book Booking Portal - Operational User Guide

This reference handbook outlines standard user operational loops for account creation, catalog queries, and active booking settlements.

## 🔐 1. Account Initialization (Registration)
1. Access the web server gateway directly inside an incognito browser window: `http://169.254.1`
2. Click the permanent **"Don't have an account? Register here"** hyperlink sitting below the main form.
3. Enter your unique Username, Email, and Password values inside the input text fields and submit the form.
4. Upon receiving the green verification success banner, select "Back to Login".

## 📥 2. Scheduling an Inventory Booking (CRUD Create)
1. Authenticate your account profile using your newly registered login parameters.
2. View the **Available Books Catalogue** grid table displayed on the main dashboard view.
3. Click the explicit blue **`Book Now`** hyperlink located within your desired inventory item row cell.
4. On the scheduling form sub-page, use the system calendar date picker to select an action start date (constrained to a maximum threshold of 3 days from the current execution date).
5. Input your expected return deadline date parameter and select **Confirm Booking**.

## 📤 3. Returning Active Bookings (CRUD Update)
1. Select the **📋 View My Borrowed Books** navigation option inside your main account control panel header.
2. Locate the outstanding checkout transaction ledger item row you intend to settle.
3. Click the designated **`Return Book`** form button. The database will handle inventory updates and drop the active record from your history profile.

## ⚠️ 4. Automated Compliance System Warnings
If an active booking transaction breaches its documented return deadline parameter (`due_date < current_date`), the server engine automatically flag-triggers an isolated red alert warning box across the top of your dashboard area. Access to further inventory booking operations remains restricted until outstanding transaction items are settled.
