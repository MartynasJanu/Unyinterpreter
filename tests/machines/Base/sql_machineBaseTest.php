<?php

namespace unyInterpreter\machines\Base;

require_once 'machines/sql_machine.php';

/**
 * Generated by PHPUnit_SkeletonGenerator on 2015-10-15 at 00:27:35.
 */
class sql_machineBaseTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var sql_machineBase
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
        $input = 'SELECT * FROM table';
        $this->object->execute($input);
    }

}
