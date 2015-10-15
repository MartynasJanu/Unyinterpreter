<?php

namespace unyInterpreter\machines;

require_once 'machines/Base/sql_machineBase.php';

class SqlMachine extends Base\sql_machineBase {
    private $selects = [];
    private $level = 0;

    public function selectBegin()
    {
        $this->selects[$this->level][] = [];
    }

    public function selectAddAllColumns()
    {
        $this->selects[$this->level][count($this->selects[$this->level])-1]['columns'] = 'ALL';
    }

    public function selectAddColumnPush($char)
    {
        if (!isset($this->selects[$this->level][count($this->selects[$this->level])-1]['column_buff'])) {
            $this->selects[$this->level][count($this->selects[$this->level])-1]['column_buff'] = '';
        }
        $this->selects[$this->level][count($this->selects[$this->level])-1]['column_buff'] .= $char;
    }

    public function selectAddColumn()
    {
        if (empty($this->selects[$this->level][count($this->selects[$this->level])-1]['columns'])) {
            $this->selects[$this->level][count($this->selects[$this->level])-1]['columns'] = [];
        }

        $this->selects[$this->level][count($this->selects[$this->level])-1]['columns'][]
            .= $this->selects[$this->level][count($this->selects[$this->level])-1]['column_buff'];

        $this->selects[$this->level][count($this->selects[$this->level])-1]['column_buff'] = '';
    }

    public function selectFromBegin()
    {
        $this->selects[$this->level][count($this->selects[$this->level])-1]['from'] = '';
    }

    public function selectFromPush($char)
    {
        $this->selects[$this->level][count($this->selects[$this->level])-1]['from'] .= $char;
    }
    public function selectEnd()
    {
        print_r($this->selects[$this->level][count($this->selects[$this->level])-1]);
        --$this->level;
    }

    public function selectNest()
    {
        ++$this->level;
    }
}
