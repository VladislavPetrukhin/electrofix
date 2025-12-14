<?php
require_once __DIR__ . '/../server/bootstrap.php';

$pdo = db();

/* ===== ГАЙДЫ ПО УСТРОЙСТВАМ ===== */
$st = $pdo->query(
  "SELECT COALESCE(d.name, 'Без устройства') AS label,
          COUNT(g.id) AS cnt
   FROM guides g
   LEFT JOIN devices d ON d.id = g.device_id
   GROUP BY label
   ORDER BY cnt DESC"
);

$guidesLabels = [];
$guidesValues = [];

foreach ($st->fetchAll() as $r) {
  $guidesLabels[] = $r['label'];
  $guidesValues[] = (int)$r['cnt'];
}

/* ===== НЕИСПРАВНОСТИ ПО УСТРОЙСТВАМ ===== */
$st = $pdo->query(
  "SELECT COALESCE(d.name, 'Без устройства') AS label,
          COUNT(i.id) AS cnt
   FROM issues i
   LEFT JOIN devices d ON d.id = i.device_id
   GROUP BY label
   ORDER BY cnt DESC"
);

$issuesLabels = [];
$issuesValues = [];

foreach ($st->fetchAll() as $r) {
  $issuesLabels[] = $r['label'];
  $issuesValues[] = (int)$r['cnt'];
}

json_out([
  'guides' => [
    'labels' => $guidesLabels,
    'values' => $guidesValues
  ],
  'issues' => [
    'labels' => $issuesLabels,
    'values' => $issuesValues
  ]
]);
