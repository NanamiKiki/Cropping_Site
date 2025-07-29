<?php
session_start();
require_once 'config.php'; // conexiunea la baza de date
// database connection

// Dacă utilizatorul nu este autentificat, îl redirecționăm spre pagina principală
// Redirect to main page if user is not logged in
if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

// Ștergem contul dacă utilizatorul apasă butonul de delete
// Delete the user account if delete button is pressed
if (isset($_POST['delete_account'])) {
    $email = $_SESSION['email'];

    // Pregătim și executăm interogarea de ștergere a utilizatorului
    // Prepare and execute user delete query
    $stmt = $conn->prepare("DELETE FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    // Distrugem sesiunea și redirecționăm spre pagina principală
    // Destroy session and redirect to main page
    session_destroy();
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Page</title>

    <!-- Importăm stilurile și scripturile pentru Cropper.js -->
    <!-- Import Cropper.js CSS and JS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

    <style>
        /* Stiluri generale pentru pagina utilizator */
        /* General styles for user page */
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background-image: url('https://images.unsplash.com/photo-1753495591125-a75f4c45579a?q=80&w=1750&auto=format&fit=crop&ixlib=rb-4.1.0');
            background-size: cover;
            background-position: center;
            color: white;
            display: flex;
            flex-direction: column;
            height: 100vh;
        }

        .welcome-header {
            text-align: center;
            padding: 30px 20px 10px;
            font-size: 2em;
            background-color: rgba(194, 11, 166, 0.4); /* semi-transparent purple */
            color: #ffffff;
        }

        .role-text {
            text-align: center;
            margin-top: 5px;
            font-weight: bold;
            background-color: rgba(54, 158, 177, 0.4); /* semi-transparent blue */
            color: #000000;
        }

        /* Stiluri pentru butoanele de logout și delete */
        /* Styles for logout and delete buttons */
        .logout-btn,
        .delete {
            position: absolute;
            padding: 10px 20px;
            background-color: #d43785ff;
            border: none;
            color: white;
            cursor: pointer;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .logout-btn {
            bottom: 70px;
            right: 30px;
        }

        .delete {
            bottom: 20px;
            right: 30px;
        }

        .logout-btn:hover,
        .delete:hover {
            background-color: #520b9d;
        }

        /* Secțiunea pentru încărcare și afișare imagine */
        /* Section for image upload and preview */
        .image-section {
            padding: 20px;
            text-align: center;
        }

        #imagePreview {
            width: 100%;
            max-width: 400px;
            max-height: 300px;
            display: none; /* ascuns inițial */
            margin-top: 10px;
            border: 2px solid white;
            border-radius: 10px;
        }

        #cropBtn {
            display: none; /* ascuns inițial */
            margin-top: 10px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        #cropBtn:hover {
            background-color: #3e8e41;
        }
    </style>
</head>
<body>
    <div class="welcome-header">
        <!-- Salutăm utilizatorul după nume, folosind htmlspecialchars pentru securitate -->
        <!-- Greet the user by name, using htmlspecialchars for security -->
        Welcome, <?= htmlspecialchars($_SESSION['name'] ?? 'User') ?>!
    </div>
    <p class="role-text">You are logged in as <strong><?= $_SESSION['role'] ?? 'user' ?></strong>.</p>

    <!-- Formular pentru ștergerea contului cu confirmare -->
    <!-- Form for deleting account with confirmation -->
    <form method="post" style="display:inline;">
        <button type="submit" name="delete_account" class="delete" onclick="return confirm('Are you sure you want to delete your account?')">
            Delete Account
        </button>
    </form>

    <!-- Buton logout -->
    <!-- Logout button -->
    <button class="logout-btn" onclick="window.location.href='logout.php'">Logout</button>

    <div class="image-section">
        <h3>Upload and Crop</h3>
        <!-- Input pentru upload imagine -->
        <!-- Input to upload image -->
        <input type="file" id="inputImage" accept="image/*"><br>
        <!-- Previzualizare imagine încărcată -->
        <!-- Uploaded image preview -->
        <img id="imagePreview">
        <br>
        <!-- Buton pentru crop și download -->
        <!-- Button to crop and download image -->
        <button id="cropBtn">Crop & Download</button>
    </div>

    <!-- Script JavaScript pentru funcționalitatea Cropper.js -->
    <!-- JavaScript for Cropper.js functionality -->
    <script>
        let cropper;
        const inputImage = document.getElementById('inputImage');
        const imagePreview = document.getElementById('imagePreview');
        const cropBtn = document.getElementById('cropBtn');

        // Când se încarcă o imagine nouă
        // When a new image is loaded
        inputImage.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file && /^image\/\w+/.test(file.type)) { // verificăm dacă fișierul este o imagine
                const reader = new FileReader();
                reader.onload = function () {
                    imagePreview.src = reader.result;
                    imagePreview.style.display = 'block';

                    // Distrugem cropperul existent dacă există
                    // Destroy existing cropper if any
                    if (cropper) cropper.destroy();

                    // Inițializăm cropperul cu opțiuni (aspect ratio liber)
                    // Initialize cropper with options (free aspect ratio)
                    cropper = new Cropper(imagePreview, {
                        aspectRatio: NaN,
                        viewMode: 1,
                        movable: true,
                        zoomable: true,
                        scalable: false
                    });

                    // Arătăm butonul de crop
                    // Show crop button
                    cropBtn.style.display = 'inline-block';
                };
                reader.readAsDataURL(file);
            }
        });

        // Când se apasă butonul Crop & Download
        // When Crop & Download button is clicked
        cropBtn.addEventListener('click', function () {
            if (cropper) {
                // Obținem canvas-ul cu zona decupată
                // Get the cropped canvas
                const canvas = cropper.getCroppedCanvas();
                // Cream un link de descărcare
                // Create a download link
                const link = document.createElement('a');
                link.download = 'cropped_image.png';
                link.href = canvas.toDataURL('image/png');
                link.click();
            }
        });
    </script>
</body>
</html>
