<?php
session_start();

require_once 'config.php'; // includem conexiunea la baza de date
// include the database connection

// Verificăm dacă utilizatorul este autentificat, altfel redirecționăm la pagina de login
// Check if the user is logged in, otherwise redirect to login page
if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

// Ștergere user (dacă a venit cerere POST cu id-ul userului de șters)
// Delete user (if a POST request with user id to delete is received)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user_id'])) {
    $deleteUserId = intval($_POST['delete_user_id']);

    if ($deleteUserId) {
        // Preluăm emailul utilizatorului ce urmează a fi șters pentru verificare
        // Fetch the email of the user to be deleted for validation
        $stmt = $conn->prepare("SELECT email FROM users WHERE id = ?");
        $stmt->bind_param("i", $deleteUserId);
        $stmt->execute();
        $resultCheck = $stmt->get_result();
        $userToDelete = $resultCheck->fetch_assoc();
        $stmt->close();

        // Verificăm să nu se poată șterge contul propriu
        // Ensure the user does not delete their own account
        if ($userToDelete && $userToDelete['email'] !== $_SESSION['email']) {
            // Ștergem utilizatorul din baza de date
            // Delete the user from the database
            $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
            $stmt->bind_param("i", $deleteUserId);
            $stmt->execute();
            $stmt->close();

            $_SESSION['message'] = "Utilizatorul a fost șters cu succes.";
            // User successfully deleted.
        } else {
            $_SESSION['message'] = "Nu poți să îți ștergi propriul cont!";
            // You cannot delete your own account!
        }
    }

    // După procesare redirecționăm înapoi la pagina admin
    // Redirect back to the admin page after processing
    header("Location: admin_page.php");
    exit();
}

// Preluăm lista tuturor utilizatorilor pentru afișare
// Get the list of all users to display
$sql = "SELECT id, name, email, role FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Page</title>
    <style>
        /* Stilizare generală */
        body {
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-image: url('https://images.unsplash.com/photo-1753507451863-4ea8da098b50?q=80&w=1744&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D');
            background-size: cover;
            background-position: center;
            color: white;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            padding: 20px;
        }

        .welcome-header {
            text-align: center;
            padding: 30px 20px 10px;
            font-size: 2em;
            background-color: rgba(194, 11, 166, 0.4);
            color: #a161cdff; /* violet */
            border-radius: 10px;
            margin-bottom: 10px;
        }

        .role-text {
            text-align: center;
            margin-top: 0;
            font-weight: bold;
            color: #8f0ca3ff;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: rgba(0, 0, 0, 0.5);
            border-radius: 10px;
            overflow: hidden;
        }

        th, td {
            padding: 12px 15px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: rgba(194, 11, 166, 0.7);
            color: white;
        }

        tr:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        button.delete-btn {
            background-color: #a11;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button.delete-btn:hover {
            background-color: #d33;
        }

        .logout-btn {
            align-self: flex-end;
            margin-top: auto;
            padding: 10px 20px;
            background-color: #6a0dad;
            border: none;
            color: white;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .logout-btn:hover {
            background-color: #520b9d;
        }

        .message {
            max-width: 600px;
            margin: 10px auto 20px auto;
            padding: 10px;
            background-color: #a11;
            color: white;
            text-align: center;
            border-radius: 5px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <!-- Mesaj de bun venit personalizat -->
    <!-- Personalized welcome message -->
    <div class="welcome-header">
        Welcome, <?= htmlspecialchars($_SESSION['name'] ?? 'Admin') ?>!
    </div>
    <!-- Afișăm rolul utilizatorului -->
    <!-- Display the user's role -->
    <p class="role-text">You are logged in as <strong><?= htmlspecialchars($_SESSION['role'] ?? 'admin') ?></strong>.</p>

    <!-- Afișăm mesajul din sesiune dacă există -->
    <!-- Display session message if exists -->
    <?php if (isset($_SESSION['message'])): ?>
        <div class="message">
            <?= htmlspecialchars($_SESSION['message']) ?>
        </div>
        <?php unset($_SESSION['message']); // ștergem mesajul după afișare / remove message after display ?>
    <?php endif; ?>

    <h2 style="text-align:center; margin-bottom: 15px;">Lista utilizatorilor</h2>
    <!-- Tabel cu utilizatorii -->
    <!-- Users table -->
    <table>
        <thead>
            <tr>
                <th>Nume</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Acțiuni</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($user = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['name']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['role']) ?></td>
                        <td>
                            <?php if ($user['email'] !== $_SESSION['email']): ?>
                                <!-- Formular pentru ștergere utilizator -->
                                <!-- Form to delete user -->
                                <form method="POST" style="display:inline;" onsubmit="return confirm('Sigur vrei să ștergi acest utilizator?');">
                                    <input type="hidden" name="delete_user_id" value="<?= $user['id'] ?>">
                                    <button type="submit" class="delete-btn">Șterge</button>
                                </form>
                            <?php else: ?>
                                <em>Nu poți șterge contul tău</em>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="4" style="text-align:center;">Nu există utilizatori în baza de date.</td></tr>
                <!-- No users found in the database -->
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Buton de logout -->
    <!-- Logout button -->
    <button class="logout-btn" onclick="window.location.href='logout.php'">Logout</button>
</body>
</html>
