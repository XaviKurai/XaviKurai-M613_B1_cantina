<?php
session_start();

// Buidem les dades abans de destruir completament la sessió.
$_SESSION = [];

if (ini_get('session.use_cookies')) {
    $parametres = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $parametres['path'],
        $parametres['domain'],
        $parametres['secure'],
        $parametres['httponly']
    );
}

session_destroy();

// Després de sortir, l'usuari torna al formulari de login.
header('Location: login.php');
exit;
