<?php
header('Content-Type: text/html; charset=utf-8');
mysqli_report(MYSQLI_REPORT_OFF);

$hosts = ['localhost', '127.0.0.1', 'MySQL-8.0', 'MySQL-8.4', 'MySQL-5.7', 'MariaDB-11.4', 'MariaDB-10.11', 'MariaDB-10.6'];
$link = null; $host = null; $errors = [];
foreach ($hosts as $h) {
  $l = @mysqli_connect($h, 'root', '');
  if ($l) { $link = $l; $host = $h; break; }
  $errors[] = $h . ': ' . mysqli_connect_error();
}
if (!$link) {
  die('<h3>Не удалось подключиться к MySQL</h3><pre>' . htmlspecialchars(implode("\n", $errors)) . '</pre>');
}
mysqli_set_charset($link, 'utf8');

if (!mysqli_query($link, 'CREATE DATABASE IF NOT EXISTS gbook DEFAULT CHARACTER SET utf8')) {
  die('<h3>Ошибка создания базы: ' . htmlspecialchars(mysqli_error($link)) . '</h3>');
}
mysqli_select_db($link, 'gbook');

$file = __DIR__ . '/gbook/gbook.sql';
if (!is_file($file)) {
  die('<h3>Не найден gbook/gbook.sql. Файл install.php должен лежать рядом с index.php, а не в папке inc.</h3>');
}
$sql = file_get_contents($file);
if (mysqli_multi_query($link, $sql)) {
  do {
    if ($r = mysqli_store_result($link)) mysqli_free_result($r);
  } while (mysqli_more_results($link) && mysqli_next_result($link));
}
if (mysqli_errno($link)) {
  die('<h3>Ошибка импорта: ' . htmlspecialchars(mysqli_error($link)) . '</h3>');
}

echo '<h3>Готово! База gbook создана, таблица msgs загружена.</h3>';
echo '<p>Рабочий адрес MySQL: <b>' . htmlspecialchars($host) . '</b></p>';

if ($host !== 'localhost') {
  $g = __DIR__ . '/inc/gbook.inc.php';
  $c = file_get_contents($g);
  $n = str_replace("define('DB_HOST', 'localhost');", "define('DB_HOST', '" . $host . "');", $c, $cnt);
  if ($cnt && file_put_contents($g, $n) !== false) {
    echo '<p>В gbook.inc.php адрес DB_HOST заменён на ' . htmlspecialchars($host) . '.</p>';
  } else {
    echo '<p>Замените DB_HOST в inc/gbook.inc.php на ' . htmlspecialchars($host) . ' вручную.</p>';
  }
}
echo '<p><a href="index.php?id=gbook">Открыть гостевую книгу</a></p>';
