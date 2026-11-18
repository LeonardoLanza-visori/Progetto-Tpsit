<?php
// v0.1 - monolite: una sola pagina PHP legge il JSON e mostra la tabella
$json = file_get_contents('dati.json');
$libri = json_decode($json, true);

// filtro: ?autore=Anna
$cerca = '';
if (isset($_GET['autore'])) {
    $cerca = $_GET['autore'];
}
if ($cerca != '') {
    $trovati = [];
    foreach ($libri as $l) {
        if (stripos($l['autore'], $cerca) !== false) {
            $trovati[] = $l;
        }
    }
    $libri = $trovati;
}

// statistica: pagine medie
$tot = 0;
foreach ($libri as $l) {
    $tot = $tot + $l['pagine'];
}
$media = 0;
if (count($libri) > 0) {
    $media = round($tot / count($libri));
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
  Autore: <input name="autore" value="<?= htmlspecialchars($cerca) ?>">
  <button>Cerca</button>
</form>
<p>Libri trovati: <?= count($libri) ?> - pagine medie: <?= $media ?></p>
<table>
<tr><th>ID</th><th>Titolo</th><th>Autore</th><th>Anno</th><th>Pagine</th><th>Disponibile</th><th>Generi</th></tr>
<?php foreach ($libri as $l): ?>
<tr>
  <td><?= $l['id'] ?></td>
  <td><?= htmlspecialchars($l['titolo']) ?></td>
  <td><?= htmlspecialchars($l['autore']) ?></td>
  <td><?= $l['anno'] ?></td>
  <td><?= $l['pagine'] ?></td>
  <td><?= $l['disponibile'] ? 'sì' : 'no' ?></td>
  <td><?= htmlspecialchars(implode(', ', $l['generi'])) ?></td>
</tr>
<?php endforeach; ?>
</table>
</body>
</html>
