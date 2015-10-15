<?php

namespace UnyInterpreter;

require_once 'src/UnyInterpreter/UnyMachineBuilder.php';

$builder = new UnyMachineBuilder();
$builder->addRulesFromFile('rules/sql_machine.uny');

$builder->buildMachine('test_machines', 'test_machine');

require_once 'test_machines/test_machine.php';

$machine = new \test_machines\test_machine();
$machine->execute('SELECT * FROM table');