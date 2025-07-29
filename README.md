<!DOCTYPE html>
<html lang="ro">
<head>
  <meta charset="UTF-8" />
  <title>README - Site de Cropping Imagine cu Login/Register</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      line-height: 1.6;
      margin: 20px;
      max-width: 900px;
      color: #333;
    }
    h1, h2 {
      color: #3F02D4;
    }
    code {
      background-color: #f4f4f4;
      padding: 2px 6px;
      border-radius: 4px;
      font-family: monospace;
    }
    pre {
      background-color: #f4f4f4;
      padding: 10px;
      border-radius: 5px;
      overflow-x: auto;
    }
    ul {
      margin-bottom: 20px;
    }
    a {
      color: #3F02D4;
      text-decoration: none;
    }
    a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

  <h1>Site de Cropping Imagine cu Login/Register și Roluri Admin/User</h1>

  <h2>Descriere</h2>
  <p>Acest proiect este un site web simplu de cropping imagini, inspirat de 
    <a href="https://www.youtube.com/watch?v=LiomRvK7AM8" target="_blank" rel="noopener noreferrer">
      tutorialul video
    </a>.  
    Are funcționalități complete de:</p>
  <ul>
    <li>Înregistrare și autentificare utilizatori (roluri <code>admin</code> și <code>user</code>)</li>
    <li>Stocarea utilizatorilor într-o bază de date MySQL (phpMyAdmin, XAMPP)</li>
    <li>Utilizatorii cu rol <code>user</code> pot încărca imagini și le pot decupa (cropp)</li>
    <li>Adminul poate gestiona utilizatorii, inclusiv ștergerea conturilor create</li>
  </ul>

  <h2>Structura proiectului</h2>
  <ul>
    <li><code>config.php</code> - Conexiunea la baza de date MySQL (localhost/XAMPP)</li>
    <li><code>index.php</code> - Pagina de login și înregistrare cu switch între formulare</li>
    <li><code>login_register.php</code> - Logica de autentificare și înregistrare, verificări și redirectări</li>
    <li><code>admin_page.php</code> - Pagina pentru admin: afișare listă utilizatori și opțiune de ștergere conturi (în afară de cel propriu)</li>
    <li><code>user_page.php</code> - Pagina pentru utilizatori: încărcare imagine, cropping folosind Cropper.js și ștergere cont propriu</li>
    <li><code>logout.php</code> - Logout și distrugerea sesiunii</li>
    <li><code>style.css</code> - Stilizarea generală a formularelor</li>
    <li><code>script.js</code> - JavaScript pentru toggle între formularele login și register</li>
    <li><code>Cropper.js</code> - Biblioteca JS inclusă din CDN pentru funcția de crop</li>
  </ul>

  <h2>Funcționalități principale</h2>
  <h3>Înregistrare / Login</h3>
  <ul>
    <li>Înregistrarea permite alegerea rolului <code>user</code> sau <code>admin</code></li>
    <li>La login, utilizatorul este redirecționat către pagina potrivită rolului:
      <ul>
        <li><code>admin_page.php</code> pentru admini</li>
        <li><code>user_page.php</code> pentru utilizatori normali</li>
      </ul>
    </li>
  </ul>

  <h3>Pagina Admin</h3>
  <ul>
    <li>Afișează o listă cu toți utilizatorii (nume, email, rol)</li>
    <li>Permite adminului să șteargă orice utilizator, cu excepția contului propriu</li>
  </ul>

  <h3>Pagina User</h3>
  <ul>
    <li>Permite încărcarea unei imagini de pe device</li>
    <li>Cropper.js permite decuparea imaginii în browser, cu descărcare locală a rezultatului</li>
    <li>Opțiune de ștergere a contului propriu</li>
  </ul>

  <h2>Baza de date</h2>
  <p>Se presupune o bază de date MySQL numită <code>users_form_db</code>, cu un tabel <code>users</code> având coloanele:</p>
  <pre>
| id (INT, AUTO_INCREMENT, PRIMARY KEY) | name (VARCHAR) | email (VARCHAR, UNIQUE) | password (VARCHAR) | role (ENUM('admin','user')) |
  </pre>

  <h2>Instalare & rulare locală</h2>
  <ol>
    <li>Instalează <a href="https://www.apachefriends.org/index.html" target="_blank" rel="noopener noreferrer">XAMPP</a> și pornește Apache și MySQL.</li>
    <li>Importă baza de date <code>users_form_db</code> în phpMyAdmin (sau creează manual tabelul <code>users</code>).</li>
    <li>Plasează fișierele proiectului în folderul <code>htdocs</code> din XAMPP.</li>
    <li>Modifică datele din <code>config.php</code> dacă este nevoie (user, parolă MySQL).</li>
    <li>Accesează în browser <code>http://localhost/index.php</code>.</li>
    <li>Înregistrează un cont <code>admin</code> și unul <code>user</code> pentru testare.</li>
  </ol>

  <h2>Tehnologii folosite</h2>
  <ul>
    <li>PHP </li>
    <li>MySQL / MariaDB (phpMyAdmin)</li>
    <li>HTML, CSS, JavaScript</li>
    <li>Cropper.js </li>
    <li>XAMPP (Apache + MySQL) pentru server local</li>
  </ul>

  <h2>Observații</h2>
  <ul>
    <li>Parolele sunt criptate cu <code>password_hash</code> PHP</li>
    <li>Protecție minimă prin sesiuni, fără implementări avansate de securitate (ex: CSRF)</li>
    <li>Sistem simplu pentru demo sau proiecte mici</li>
    <li>Cropping se face doar în client (browser), imaginea nu se salvează pe server</li>
  </ul>

  <p>Dacă dorești, pot să te ajut și cu scriptul de creare tabel în MySQL sau alte detalii.</p>

</body>
</html>
