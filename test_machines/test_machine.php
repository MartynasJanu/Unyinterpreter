<?php

namespace test_machines;

require_once 'test_machines/Base/test_machineBase.php';

class test_machine extends Base\test_machineBase {
private $selects = [];
    private $level = 0;

    public function selectBegin($param = null)
    {
        $this->selects[$this->level][] = [];
    }

    public function selectAddAllColumns($param = null)
    {
        $this->selects[$this->level][count($this->selects[$this->level])-1]['columns'] = 'ALL';
    }

    public function selectAddColumnPush($param = null)
    {
        if (!isset($this->selects[$this->level][count($this->selects[$this->level])-1]['column_buff'])) {
            $this->selects[$this->level][count($this->selects[$this->level])-1]['column_buff'] = '';
        }
        $this->selects[$this->level][count($this->selects[$this->level])-1]['column_buff'] .= $param;
    }

    public function selectAddColumn($param = null)
    {
        if (empty($this->selects[$this->level][count($this->selects[$this->level])-1]['columns'])) {
            $this->selects[$this->level][count($this->selects[$this->level])-1]['columns'] = [];
        }

        $this->selects[$this->level][count($this->selects[$this->level])-1]['columns'][]
            .= $this->selects[$this->level][count($this->selects[$this->level])-1]['column_buff'];

        $this->selects[$this->level][count($this->selects[$this->level])-1]['column_buff'] = '';
    }

    public function selectFromBegin($param = null)
    {
        $this->selects[$this->level][count($this->selects[$this->level])-1]['from'] = '';
    }

    public function selectFromPush($param = null)
    {
        $this->selects[$this->level][count($this->selects[$this->level])-1]['from'] .= $param;
    }
    public function selectEnd($param = null)
    {
        print_r($this->selects[$this->level][count($this->selects[$this->level])-1]);
        --$this->level;
    }

    public function selectNest($param = null)
    {
        ++$this->level;
    }
}
