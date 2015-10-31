<?php

namespace UnyInterpreter;

require_once 'test_machines/bison.php';

$machine = new \test_machines\bison();
$bison_file = file_get_contents('data/mysql.yy');
$machine->execute($bison_file);