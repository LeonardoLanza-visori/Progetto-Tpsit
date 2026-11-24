<?php
// v0.3 - elenco, dettaglio con 404, filtro per genere, statistica sulla disponibilità
header('Content-Type: application/json; charset=utf-8');

$libri = json_decode(file_get_contents(__DIR__ . '/dati.json'), true);

function rispondi($dati, $codice = 200) {
    http_response_code($codice);
    echo json_encode($dati, JSON_UNESCAPED_UNICODE);
    exit;
}

// api.php?id=3  ->  dettaglio
if (isset($_GET['id'])) {
    foreach ($libri as $l) {
        if ($l['id'] === (int)$_GET['id']) {
            rispondi($l);
        }
    }
    rispondi(['errore' => 'Libro non trovato'], 404);
}

// api.php?stat=disponibili  ->  statistica sulla disponibilità
if (isset($_GET['stat'])) {
    $totale = count($libri);
    $disponibili = 0;
    foreach ($libri as $l) {
        if ($l['disponibile']) {
            $disponibili++;
        }
    }
    rispondi([
        'totale' => $totale,
        'disponibili' => $disponibili,
        'percentuale_disponibili' => $totale > 0 ? round($disponibili / $totale * 100, 1) : 0
    ]);
}

// api.php?genere=giallo  ->  filtro sul campo generi; senza parametri: elenco completo
if (isset($_GET['genere']) && trim($_GET['genere']) !== '') {
    $cercato = strtolower(trim($_GET['genere']));
    $trovati = [];
    foreach ($libri as $l) {
        foreach ($l['generi'] as $g) {
            if (strtolower($g) === $cercato) {
                $trovati[] = $l;
                break;
            }
        }
    }
    $libri = $trovati;
}

rispondi($libri);