<?php

namespace unyInterpreter\machines;

require_once 'machines/sql_machine.php';
require_once 'src/UnyInterpreter/UnyRuleParser.php';

/**
 * Generated by PHPUnit_SkeletonGenerator on 2015-10-15 at 01:21:23.
 */
class SqlMachineTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var SqlMachine
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new SqlMachine;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {

    }

    /**
     * @covers unyInterpreter\machines\Base\sql_machineBase::execute
     * @todo   Implement testExecute().
     */
    public function testExecute()
    {
        $parser = new \UnyInterpreter\UnyRuleParser();
        $parser->parseFile('rules/sql_machine.uny');

        $this->object->transitions = $parser->transitions;

        $input = 'SELECT name, lastname, (SELECT role FROM userroles), (SELECT img FROM photos)'
            . ' FROM user';
        $this->object->execute($input);
    }
}
