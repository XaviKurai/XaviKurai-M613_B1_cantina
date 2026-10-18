<?php
require_once __DIR__ . '/funcions.php';

// La capçalera llegeix el missatge i les dades que es veuen al menú.
$missatge = llegeixMissatge();
$nomUsuari = $_SESSION['usuari']['nom'] ?? '';
$unitats = unitatsCistell($_SESSION['cistell'] ?? []);
?>
<!DOCTYPE html>
<html lang="ca">
<head>
    <meta charset="UTF-8">
    <title>Cantina</title>
</head>
<body>
    <header>
        <p>Usuari: <?= htmlspecialchars($nomUsuari, ENT_QUOTES, 'UTF-8') ?></p>
        <nav>
            <a href="cataleg.php">Catàleg</a> |
            <a href="cistell.php">Cistell (<?= $unitats ?>)</a> |
            <a href="logout.php">Tancar sessió</a>
        </nav>
    </header>

    <?php if ($missatge !== null): ?>
        <p><?= htmlspecialchars($missatge, ENT_QUOTES, 'UTF-8') ?></p>
    <?php endif; ?>
