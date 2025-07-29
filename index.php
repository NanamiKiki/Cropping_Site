<?php

session_start(); // Pornim sesiunea pentru a putea accesa mesajele de eroare și formularul activ
// Start the session to access error messages and the active form

// Preluăm erorile din sesiune dacă există, altfel șir gol
// Retrieve errors from session if exist, otherwise empty string
$errors = [
    'login'=> $_SESSION['login_error'] ?? '',
    'register'=> $_SESSION['register_error'] ?? '',
];

// Determinăm care formular trebuie să fie activ (login sau register), default login
// Determine which form should be active (login or register), default is login
$activeForm = $_SESSION['active_form'] ?? 'login';

// Ștergem toate variabilele de sesiune (inclusiv mesajele de eroare) după preluare
// Clear all session variables (including error messages) after reading them
session_unset();

// Funcție pentru afișarea mesajului de eroare dacă există
// Function to display error message if it exists
function showErrors($error) {
    return !empty($error) ? "<p class='error-message'>$error</p>" : '';
}

// Funcție care adaugă clasa CSS "active" dacă formularul este cel activ
// Function that adds the CSS class "active" if the form is the active one
function isActiveForm($formName, $activeForm) {
    return $formName == $activeForm ? 'active' : '';
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Full-Stack Login & Register Form With User & Admin Page | Codehal</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Textul mare din fundal, semi-transparent, centrat orizontal */
        /* Large semi-transparent background text, horizontally centered */
        .background-text {
            position: fixed;
            top: 1%;
            left: 50%;
            transform: translateX(-50%); /* doar pe orizontală, să fie centrat */
            font-size: 10vw; /* dimensiune mare, dar mai mică decât înainte */
            color: rgba(0, 0, 0, 0.7);
            white-space: nowrap;
            user-select: none; /* previne selectarea textului */
            pointer-events: none; /* dezactivează evenimentele mouse */
            z-index: 0;
            font-weight: 900; /* bold */
        }
    </style>
</head>

<body>

    <div class="container">
        <!-- Text mare de fundal -->
        <!-- Large background text -->
        <div class="background-text">Cropping site</div>

        <!-- Formular de login, clasa "active" îl face vizibil -->
        <!-- Login form, "active" class makes it visible -->
        <div class="form-box <?= isActiveForm('login', $activeForm); ?>" id="login-form">
            <form action="login_register.php" method="post">
                <h2>Login</h2>
                <?= showErrors($errors['login']); ?> <!-- Afișăm eroarea dacă există -->
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" name="login">Login</button>
                <p>Don't have an account? <a href="#" onclick="showForm('register-form')">Register</a></p>
            </form>
        </div>

        <!-- Formular de înregistrare, clasa "active" îl face vizibil -->
        <!-- Register form, "active" class makes it visible -->
        <div class="form-box <?= isActiveForm('register', $activeForm); ?>" id="register-form">
            <form action="login_register.php" method="post">
                <h2>Register</h2>
                <?= showErrors($errors['register']); ?> <!-- Afișăm eroarea dacă există -->
                <input type="text" name="name" placeholder="Name" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <select name="role" required>
                    <option value="">--Select Role--</option>
                    <option value="user">User</option>
                    <option value="admin">Admin</option>
                </select>
                <button type="submit" name="register">Register</button>
                <p>Already have an account? <a href="#" onclick="showForm('login-form')">Login</a></p>
            </form>
        </div>
    </div>

    <!-- Script pentru schimbarea vizibilității formularelor -->
    <!-- Script to toggle form visibility -->
    <script src="script.js"></script>
</body>

</html>
