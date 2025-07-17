# BPRV Pet Adoption Center

A full-featured PHP web application designed to manage a pet adoption center. This project provides a beautiful, user-friendly interface for the public to browse available pets and a secure, powerful dashboard for administrators to manage all listings.

---

## ✨ Features

### Public-Facing Features
*   **Stunning Homepage:** A welcoming hero banner with a custom background image.
*   **Dynamic Pet Listings:** Browse all available pets in a clean, modern card layout.
*   **Live Filtering:** Instantly filter pets by species (Dogs/Cats) without a page reload.
*   **"My Carrier" (Favorites):** Users can add pets to a "carrier" to save a list of their favorites, which persists across the site.
*   **Success Stories:** A dedicated page to showcase happily adopted pets, complete with heartfelt descriptions.
*   **Detailed Pet Profiles:** Each pet has a dedicated page with all their information, including age, gender, breed, and personality.
*   **Fully Responsive Design:** Looks and works great on desktops, tablets, and mobile phones.

### Administrative Features
*   **Secure Authentication:** Separate registration and login system for administrators. Passwords are fully hashed and secure.
*   **Admin Dashboard:** A central hub to view all pets (both available and adopted) at a glance.
*   **Full CRUD Functionality:**
    *   **Create:** Add new pets with detailed information, including name, species, breed, age, gender, personality tags, spayed/neutered status, and image uploads.
    *   **Read:** View all pets in the system.
    *   **Update:** Edit every detail of an existing pet, including changing their status from "Available" to "Adopted".
    *   **Delete:** Permanently remove a pet listing from the database.

---

## 🔧 Built With

*   **Backend:** PHP
*   **Database:** MySQL
*   **Frontend:** HTML5, CSS3, JavaScript
*   **Frameworks/Libraries:**
    *   [Bootstrap 5](https://getbootstrap.com/) - For layout, components, and icons.
    *   [Google Fonts](https://fonts.google.com/) - For the "Poppins" font.

---

## 🚀 Getting Started

To get a local copy up and running, follow these simple steps.

### Prerequisites

You need a local server environment like [XAMPP](https://www.apachefriends.org/index.html) or WAMP installed, which includes Apache, MySQL, and PHP.

### Installation

1.  **Clone the repo** (or download the source code).
    ```sh
    git clone https://github.com/your-username/pet-adoption.git
    ```
2.  **Place the project** in your local server's web directory (e.g., `C:\xampp\htdocs\pet-adoption`).

3.  **Set up the database:**
    *   Open your database management tool (like phpMyAdmin).
    *   Create a new database and name it `p_r_s_db`.
    *   Select the new database and go to the "Import" tab.
    *   Import the `final_database.sql` file provided in this repository to create all tables and populate them with default data.

4.  **Configure the database connection:**
    *   Open the file `config/db.php`.
    *   Update the database credentials (`$hostname`, `$username`, `$password`, `$dbName`) to match your local setup.

5.  **Start your local server:** Open your XAMPP control panel and start the **Apache** and **MySQL** modules.

6.  **Access the site:** Open your web browser and navigate to `http://localhost/pet-adoption/`.

---

## 📋 Usage

The application has two primary user roles:

*   **Public User:** Anyone can visit the site to browse available pets, filter them by species, view their detailed profiles, and add them to their "Carrier" to keep track of favorites.
*   **Administrator:** After registering and logging in, an admin gains access to the **Dashboard**. From here, they can add new pets, edit any information for existing pets (including changing their status to "Adopted"), and delete listings.

---

## 📁 Project Structure
/pet-adoption
├── auth/
│   ├── login.php
│   ├── logout.php
│   └── register.php
│
├── config/
│   └── db.php
│
├── images/
│   ├── background.jpg
│   ├── adopted_background.jpg
│   └── logo-transparent.png
│
├── includes/
│   ├── header.php
│   └── footer.php
│
├── uploads/
│   └── (pet images)
│
├── adopted.php
├── add_pet.php
├── dashboard.php
├── delete_pet.php
├── edit_pet.php
├── favorites.php
├── index.php
├── manage_favorites.php
└── view_pet.php

## 👥 Team & Credits

This project was developed based on an initial plan by:

*   **Member 1 (Rosel):** User Authentication & Session Handling
*   **Member 2 (Badilla):** Database & File Upload Integration
*   **Member 3 (Valera):** CRUD for Pets
*   **Member 4 (Pedreña):** Frontend & Pet Listings

Special thanks to the **Quezon City Animal Care and Adoption Center** for providing the animal photos used in this project. 
https://www.facebook.com/share/p/1B5dQRRWon/
