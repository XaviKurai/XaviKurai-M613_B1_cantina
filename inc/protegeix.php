<?php
$tempsMaximInactivitat = 10 * 60;
$ultimaActivitat = $_SESSION['ultima_activitat'] ?? time();

// Aquesta comprovació impedeix entrar a les pàgines privades sense login.
if (!isset($_SESSION['usuari'])) {
    header('Location: login.php');
    exit;
}

if (time() - $ultimaActivitat > $tempsMaximInactivitat) {
    // Si fa massa temps que no hi ha activitat, tanquem la sessió.
    $_SESSION = [];
    session_destroy();
    header('Location: login.php?expirada=1');
    exit;
}

$_SESSION['ultima_activitat'] = time();
