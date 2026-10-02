<?php
header('Content-Type: text/html; charset=utf-8');

// Задание 6: автозагрузка классов и интерфейсов из папки classes
spl_autoload_register(function ($className) {
    require_once __DIR__ . '/classes/' . $className . '.php';
});

// Задание 2: объекты создаются через конструктор
$user1 = new User('ЧИНГИЗ', '123456', '12345');
$user2 = new User('ДАНИЭЛЬ', 'danielPRO', 'qwerty');
$user3 = new User('АРТЕМ', 'artem666', 'pass777');

echo '<h3>Обычные пользователи</h3>';
$user1->showInfo();
echo '<br />';
$user2->showInfo();
echo '<br />';
$user3->showInfo();

// Задание 3: класс-наследник
$user = new SuperUser('Админ Mansur', 'admin', 'root123', 'administrator');

echo '<h3>Супер-пользователь</h3>';
$user->showInfo();

// Задание 4: getInfo() и print_r()
echo '<h3>getInfo()</h3>';
echo '<pre>';
print_r($user->getInfo());
echo '</pre>';

// Задание 4: auth()
echo '<h3>auth()</h3>';
echo 'Верные данные: ';
var_dump($user->auth('admin', 'root123'));
echo '<br />Неверные данные: ';
var_dump($user->auth('admin', 'wrong'));

// Задание 5: статические счётчики
echo '<h3>Количество объектов</h3>';
echo 'Всего обычных пользователей: ' . User::$count . '<br />';
echo 'Всего супер-пользователей: ' . SuperUser::$count . '<br />';

echo '<h3>Деструкторы (в конце работы скрипта)</h3>';
