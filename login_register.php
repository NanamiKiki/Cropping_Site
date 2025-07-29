<?php
session_start(); // Pornim sesiunea pentru a stoca mesaje și date între pagini

require_once 'config.php'; // Includem fișierul de configurare pentru conexiunea la baza de date

// Procesăm înregistrarea utilizatorului dacă formularul de înregistrare a fost trimis
// Process user registration if the registration form was submitted
if (isset($_POST['register'])) {
    // Preluăm datele din formular și eliminăm spațiile albe la început și sfârșit
    // Get form data and trim whitespace
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    // Hash-uim parola pentru securitate
    // Hash the password for security
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    // Verificăm dacă emailul este deja înregistrat în baza de date
    // Check if the email is already registered in the database
    $checkEmail = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    $checkEmail->store_result();

    if ($checkEmail->num_rows > 0) {
        // Dacă emailul există deja, setăm un mesaj de eroare în sesiune
        // If email already exists, set an error message in session
        $_SESSION['register_error'] = 'Email is already registered!';
        $_SESSION['active_form'] = 'register'; // Pentru a indica ce formular să fie afișat
    } else {
        // Dacă emailul nu există, inserăm noul utilizator în baza de date
        // If email does not exist, insert new user into database
        $insert = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        $insert->bind_param("ssss", $name, $email, $password, $role);
        $insert->execute();
    }

    // Închidem statement-ul și conexiunea
    // Close statement and connection
    $checkEmail->close();
    $conn->close();

    // Redirecționăm înapoi la pagina principală (index.php)
    // Redirect back to main page (index.php)
    header("Location: index.php");
    exit();
}

// Procesăm logarea utilizatorului dacă formularul de login a fost trimis
// Process user login if login form was submitted
if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Căutăm utilizatorul după email în baza de date
    // Look for the user by email in the database
    $stmt = $conn->prepare("SELECT name, email, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verificăm dacă parola introdusă corespunde cu hash-ul stocat
        // Verify that the entered password matches the stored hash
        if (password_verify($password, $user['password'])) {
            // Setăm variabilele de sesiune pentru utilizatorul autentificat
            // Set session variables for the authenticated user
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];

            // Redirecționăm în funcție de rolul utilizatorului
            // Redirect based on user role
            if ($user['role'] === 'admin') {
                header("Location: admin_page.php");
            } else {
                header("Location: user_page.php");
            }
            exit();
        }
    }

    // Dacă autentificarea a eșuat, setăm mesaj de eroare și afișăm formularul de login
    // If authentication fails, set error message and show login form again
    $_SESSION['login_error'] = 'Incorrect email or password';
    $_SESSION['active_form'] = 'login';
    header("Location: index.php");
    exit();
}

?>
