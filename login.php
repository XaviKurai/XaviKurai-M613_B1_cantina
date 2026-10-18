<?php
session_start();

if (isset($_SESSION['usuari'])) {
    header('Location: cataleg.php');
    exit;
}

// Carreguem les dades fixes i les funcions que utilitza el login.
$dades = require __DIR__ . '/inc/dades.php';
require_once __DIR__ . '/inc/funcions.php';

$email = '';
$missatge = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $contrasenya = $_POST['contrasenya'] ?? '';
    $usuari = buscaUsuari($dades['usuaris'], $email);

    if ($usuari !== null && $usuari['contrasenya'] === $contrasenya) {
        // Canviem l'ID després del login per evitar fixacions de sessió.
        session_regenerate_id(true);
        $_SESSION['usuari'] = [
            'nom' => $usuari['nom'],
            'email' => $usuari['email'],
            'rol' => $usuari['rol'],
        ];
        $_SESSION['hora_login'] = time();
        $_SESSION['ultima_activitat'] = time();
        $_SESSION['cistell'] = [];
        $_SESSION['comandes'] = [];

        header('Location: cataleg.php');
        exit;
    }

    // Mantenim l'email escrit per no haver-lo de tornar a introduir.
    $missatge = 'Credencials incorrectes';
}

if (isset($_GET['expirada'])) {
    $missatge = 'La sessió ha caducat per inactivitat';
}
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Inici de sessió</title>
</head>
<body>
    <h1>Inici de sessió</h1>

    <?php if ($missatge !== ''): ?>
        <p><?= htmlspecialchars($missatge, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>

    <form method="post">
        <p>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email"
                   value="<?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?>" required>
        </p>
        <p>
            <label for="contrasenya">Contrasenya:</label>
            <input type="password" id="contrasenya" name="contrasenya" required>
        </p>
        <button type="submit">Entrar</button>
    </form>
</body>
</html>
