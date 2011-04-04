<?php
header('Content-type:text/html;charset=UTF-8');
mb_internal_encoding("UTF-8");
include ("./names.php");

$a = new RussianNameProcessor('Козлов Евгений Павлович');      // годится обычная форма
echo "".$a->fullName($a->gcaseRod);
$a = new RussianNameProcessor('Евгений Павлович Козлов');      // в таком виде тоже
echo "<br/>".$a->fullName($a->gcaseRod);
$a = new RussianNameProcessor('Козлов', 'Евгений');        // можно явно указать составляющие
echo "<br/>".$a->fullName($a->gcaseRod);
$a = new RussianNameProcessor('Кунтидия', 'Убиреко', '', 'f'); // можно явно указать пол ('m' или 'f')
echo "<br/>".$a->fullName($a->gcaseRod);
$a = new RussianNameProcessor('Козлова Евгения Павловна');
echo "<br/>".$a->fullName($a->gcaseRod);


?>