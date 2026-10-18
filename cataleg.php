<?php
session_start();
require_once __DIR__ . '/inc/protegeix.php';

$dades = require __DIR__ . '/inc/dades.php';
require_once __DIR__ . '/inc/funcions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int) ($_POST['id'] ?? 0);
    $quantitat = (int) ($_POST['quantitat'] ?? 0);

    // Validem també al servidor, perquè el formulari es pot modificar.
    if (isset($dades['productes'][$id]) && $quantitat >= 1 && $quantitat <= 5) {
        $cistell = $_SESSION['cistell'] ?? [];
        // Si el producte ja hi era, sumem la nova quantitat.
        $cistell[$id] = ($cistell[$id] ?? 0) + $quantitat;
        $_SESSION['cistell'] = $cistell;

        afegeixMissatge(
            "S'han afegit " . $quantitat . ' × ' . $dades['productes'][$id]['nom']
        );
        // Evitem que en recarregar es torni a enviar el formulari.
        header('Location: cataleg.php');
        exit;
    }
}

require_once __DIR__ . '/inc/capcalera.php';
?>
<main>
    <h1>Catàleg</h1>

    <table>
        <thead>
            <tr>
                <th>Producte</th>
                <th>Preu</th>
                <th>Quantitat</th>
                <th>Acció</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($dades['productes'] as $id => $producte): ?>
                <tr>
                    <td><?= htmlspecialchars($producte['nom'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= number_format($producte['preu'], 2, ',', '.') ?> €</td>
                    <td>
                        <form method="post">
                            <input type="hidden" name="id" value="<?= $id ?>">
                            <input type="number" name="quantitat" min="1" max="5"
                                   value="1" required>
                            <button type="submit">Afegir</button>
                        </form>
                    </td>
                    <td></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</main>
<?php require_once __DIR__ . '/inc/peu.php'; ?>
