<?php
// v0.1 - monolite: una sola pagina PHP legge il JSON e mostra la tabella
$json = file_get_contents('dati.json');
$libri = json_decode($json, true);
//var_dump($libri);

// filtro: ?cerca=fantascienza
$cerca = '';
if (isset($_GET['cerca']) && is_string($_GET['cerca'])) {
    $cerca = trim($_GET['cerca']);
}
if ($cerca != '') {
    $trovati = [];
    foreach ($libri as $l) {
        if (
            stripos($l['autore'], $cerca) !== false ||
            stripos($l['titolo'], $cerca) !== false ||
            stripos(implode(', ', $l['generi']), $cerca) !== false
        ) {
            $trovati[] = $l;
        }
    }
    $libri = $trovati;
}

// libro con il maggior numero di pagine
$maxPagine = 0;
$titoloMax = '';
foreach ($libri as $l) {
    if ($l['pagine'] > $maxPagine) {
        $maxPagine = $l['pagine'];
        $titoloMax = $l['titolo'];
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head><meta charset="utf-8"><title>Biblioteca</title>
<style>body{font-family:sans-serif;margin:2em}table{border-collapse:collapse}td,th{border:1px solid #999;padding:4px 8px}</style>
</head>
<body>
<h1>Biblioteca</h1>
<form method="get">
  Titolo, autore o genere: <input name="cerca" value="<?= htmlspecialchars($cerca, ENT_QUOTES, 'UTF-8') ?>">
  <button>Cerca</button>
</form>
<p>Libri trovati: <?= count($libri) ?> - libro con più pagine:
<?php if ($titoloMax !== ''): ?>
  <?= htmlspecialchars($titoloMax, ENT_QUOTES, 'UTF-8') ?> (<?= $maxPagine ?> pagine)
<?php else: ?>
  nessuno
<?php endif; ?>
</p>
<table>
<tr><th>ID</th><th>Titolo</th><th>Autore</th><th>Anno</th><th>Pagine</th><th>Disponibile</th><th>Generi</th></tr>
<?php foreach ($libri as $l): ?>
<tr>
  <td><?= $l['id'] ?></td>
  <td><?= htmlspecialchars($l['titolo'], ENT_QUOTES, 'UTF-8') ?></td>
  <td><?= htmlspecialchars($l['autore'], ENT_QUOTES, 'UTF-8') ?></td>
  <td><?= $l['anno'] ?></td>
  <td><?= $l['pagine'] ?></td>
  <td><?= $l['disponibile'] ? 'sì' : 'no' ?></td>
  <td><?= htmlspecialchars(implode(', ', $l['generi']), ENT_QUOTES, 'UTF-8') ?></td>
</tr>
<?php endforeach; ?>
</table>
</body>
</html>
