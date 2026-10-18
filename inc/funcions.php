<?php
function buscaUsuari(array $usuaris, string $email): ?array
{
    // Retornem l'usuari que coincideix amb l'email o null si no existeix.
    foreach ($usuaris as $usuari) {
        if ($usuari['email'] === $email) {
            return $usuari;
        }
    }

    return null;
}

function unitatsCistell(array $cistell): int
{
    // array_sum suma totes les unitats dels productes del cistell.
    return array_sum($cistell);
}

function totalCistell(array $cistell, array $productes): float
{
    $total = 0.0;

    // Multipliquem el preu de cada producte per les seves unitats.
    foreach ($cistell as $id => $quantitat) {
        if (isset($productes[$id])) {
            $total += $productes[$id]['preu'] * $quantitat;
        }
    }

    return $total;
}

function afegeixMissatge(string $text): void
{
    // El missatge es guarda fins que la capçalera el mostri.
    $_SESSION['missatge'] = $text;
}

function llegeixMissatge(): ?string
{
    $missatge = $_SESSION['missatge'] ?? null;
    // L'esborrem perquè el missatge flash només aparegui una vegada.
    unset($_SESSION['missatge']);

    return $missatge;
}
