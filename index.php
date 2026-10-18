<?php
session_start();

// La pàgina inicial envia l'usuari al lloc corresponent.
if (isset($_SESSION['usuari'])) {
    header('Location: cataleg.php');
    exit;
}

header('Location: login.php');
exit;
