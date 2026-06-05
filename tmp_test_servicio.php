<?php
require __DIR__ . '/core.php';
require BASE_PATH . '/app/model/ServicioModel.php';
$pdo = getDB();
foreach ($pdo->query('DESCRIBE servicios') as $r) {
    echo $r['Field'] . "\t" . $r['Type'] . "\n";
}
echo "---\n";
$s = ServicioModel::getBySlug('desarrollo-de-software-a-medida');
print_r($s);
