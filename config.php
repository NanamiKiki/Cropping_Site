<?php

// Pentru a rula acest site local, trebuie să pui toate fișierele proiectului
// în folderul \xampp\htdocs (ex: \xampp\htdocs\cropping_site).
// Apoi, pornește serverul Apache din panoul de control XAMPP.
//
// Accesează site-ul în browser tastând adresa:
// http://localhost/cropping_site
//
// Astfel, serverul web va încărca fișierele din folderul proiectului tău local.
//
// To run this site locally, place all your project files
// inside the \xampp\htdocs folder (e.g., \xampp\htdocs\cropping_site).
// Then, start the Apache server from the XAMPP control panel.
//
// Open your browser and go to:
// http://localhost/cropping_site
//
// This way, the web server will serve your local project files.


// Detaliile pentru conexiunea la baza de date
// Database connection details
$host = "localhost";
$user = "root";
$password = "ParolaNoua";
$database = "users_form_db";

// Creăm o conexiune nouă folosind clasa mysqli
// Create a new connection using mysqli class
$conn = new mysqli($host, $user, $password, $database);

// Verificăm dacă conexiunea a eșuat și oprim scriptul dacă da
// Check connection and stop script if connection failed
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
<?php
// Pentru acest proiect, trebuie să creezi o bază de date MySQL numită 'users_form_db'.
// Poți face asta în phpMyAdmin (de obicei accesibil la http://localhost/phpmyadmin).
// Pasii sunt:
// 1. Deschide phpMyAdmin.
// 2. Click pe "New" pentru a crea o bază de date nouă.
// 3. Scrie numele bazei de date: users_form_db
// 4. Click pe "Create".
//
// După ce baza de date este creată, creează tabela 'users' cu următoarea structură:
// CREATE TABLE users (
//     id INT AUTO_INCREMENT PRIMARY KEY,
//     name VARCHAR(100) NOT NULL,
//     email VARCHAR(100) NOT NULL UNIQUE,
//     password VARCHAR(255) NOT NULL,
//     role ENUM('user', 'admin') NOT NULL DEFAULT 'user'
// );
//
// This project requires a MySQL database named 'users_form_db'.
// You can create it using phpMyAdmin (usually at http://localhost/phpmyadmin).
// Steps:
// 1. Open phpMyAdmin.
// 2. Click "New" to create a new database.
// 3. Enter the database name: users_form_db
// 4. Click "Create".
//
// After creating the database, create the 'users' table with the following structure:
// CREATE TABLE users (
//     id INT AUTO_INCREMENT PRIMARY KEY,
//     name VARCHAR(100) NOT NULL,
//     email VARCHAR(100) NOT NULL UNIQUE,
//     password VARCHAR(255) NOT NULL,
//     role ENUM('user', 'admin') NOT NULL DEFAULT 'user'
// );
?>
