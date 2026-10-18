<?php
session_start();
require_once __DIR__ . '/inc/protegeix.php';

$dades = require __DIR__ . '/inc/dades.php';
require_once __DIR__ . '/inc/funcions.php';

if (!isset($_SESSION['cistell'])) {
    $_SESSION['cistell'] = [];
}
if (!isset($_SESSION['comandes'])) {
    $_SESSION['comandes'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accio = $_POST['accio'] ?? '';

    if ($accio === 'treure') {
        // unset elimina només la línia del producte seleccionat.
        $id = (int) ($_POST['id'] ?? 0);
        unset($_SESSION['cistell'][$id]);
        afegeixMissatge('Producte eliminat del cistell');
    } elseif ($accio === 'buidar') {
        $_SESSION['cistell'] = [];
        afegeixMissatge('Cistell buidat');
    } elseif ($accio === 'finalitzar' && $_SESSION['cistell'] !== []) {
        // Guardem una còpia de la comanda i després buidem el cistell.
        $total = totalCistell($_SESSION['cistell'], $dades['productes']);
        $_SESSION['comandes'][] = [
            'hora' => time(),
            'linies' => $_SESSION['cistell'],
            'total' => $total,
        ];
        $_SESSION['cistell'] = [];
        afegeixMissatge(
            'Comanda enviada: ' . number_format($total, 2, ',', '.') . ' €'
        );
    }

    // Patró PRG: després d'un POST tornem a carregar la pàgina.
    header('Location: cistell.php');
    exit;
}

require_once __DIR__ . '/inc/capcalera.php';
$cistell = $_SESSION['cistell'];
?>
<main>
    <h1>El meu cistell</h1>

    <?php if ($cistell === []): ?>
        <p>El cistell és buit</p>
        <p><a href="cataleg.php">Anar al catàleg</a></p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Producte</th>
                    <th>Preu</th>
                    <th>Quantitat</th>
                    <th>Subtotal</th>
                    <th>Acció</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cistell as $id => $quantitat): ?>
                    <?php $producte = $dades['productes'][$id]; ?>
                    <tr>
                        <td><?= htmlspecialchars($producte['nom'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= number_format($producte['preu'], 2, ',', '.') ?> €</td>
                        <td><?= $quantitat ?></td>
                        <td><?= number_format($producte['preu'] * $quantitat, 2, ',', '.') ?> €</td>
                        <td>
                            <form method="post">
                                <input type="hidden" name="accio" value="treure">
                                <input type="hidden" name="id" value="<?= $id ?>">
                                <button type="submit">Treure</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php $total = totalCistell($cistell, $dades['productes']); ?>
        <p>Total: <?= number_format($total, 2, ',', '.') ?> €</p>

        <form method="post">
            <input type="hidden" name="accio" value="buidar">
            <button type="submit">Buidar el cistell</button>
        </form>
        <form method="post">
            <input type="hidden" name="accio" value="finalitzar">
            <button type="submit">Finalitzar comanda</button>
        </form>
    <?php endif; ?>

    <h2>Comandes fetes</h2>
    <?php if ($_SESSION['comandes'] === []): ?>
        <p>Encara no hi ha comandes.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($_SESSION['comandes'] as $comanda): ?>
                <li>
                    <?= date('d/m/Y H:i', $comanda['hora']) ?> -
                    <?= number_format($comanda['total'], 2, ',', '.') ?> €
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</main>
<?php require_once __DIR__ . '/inc/peu.php'; ?>
