<?php

namespace test_machines\Base;

class test_machineBase {
    protected $input = '';
    protected $input_i = 0;
    protected $input_l = 0;

    protected $states = [
        'START',
        'SELECT',
        'SELECT NEST',
        'SELECT ALL',
        'SELECT FROM',
        'SELECT COL',
        'SELECT UNEST',
        'SELECT ACOL',
        'SELECT TABLE COL',
        'SELECT FROM TABLE'
    ];

    protected $transitions;

    protected $state_id;
    protected $state_transitions;

    protected $end_state;

    public function execute($input)
    {
        $this->input = $input;

        $this->transitions = json_decode('{"START":{"string:select":{"target":"SELECT","callback":"selectBegin"}},"SELECT":{"[whitespace]":{"target":"SELECT","callback":null},"string:(":{"target":"SELECT NEST","callback":"selectNest"},"string:*":{"target":"SELECT ALL","callback":"selectAddAllColumns"},"string:from":{"target":"SELECT FROM","callback":"selectFromBegin"},"[alfanum]":{"target":"SELECT COL","callback":"selectAddColumnPush"}},"SELECT NEST":{"string:select":{"target":"SELECT","callback":"selectBegin"}},"SELECT UNEST":{"[epsilon]":{"target":"SELECT ACOL","callback":null}},"SELECT ALL":{"string:,":{"target":"SELECT","callback":null},"string:from":{"target":"SELECT FROM","callback":"selectFromBegin"},"[whitespace]":{"target":"SELECT ALL","callback":null}},"SELECT COL":{"string:,":{"target":"SELECT","callback":"selectAddColumn"},"[alfanum]":{"target":"SELECT COL","callback":"selectAddColumnPush"},"string:.":{"target":"SELECT TABLE COL","callback":"selectAddColumnTablePush"},"[whitespace]":{"target":"SELECT ACOL","callback":"selectAddColumn"}},"SELECT TABLE COL":{"[epsilon]":{"target":"SELECT COL","callback":null}},"SELECT ACOL":{"string:,":{"target":"SELECT","callback":null},"string:from":{"target":"SELECT FROM","callback":"selectFromBegin"},"[whitespace]":{"target":"SELECT ACOL","callback":null}},"SELECT FROM":{"[whitespace]":{"target":"SELECT FROM","callback":null},"[alfanum]":{"target":"SELECT FROM TABLE","callback":"selectFromPush"}},"SELECT FROM TABLE":{"[alfanum]":{"target":"SELECT FROM TABLE","callback":"selectFromPush"},"string:)":{"target":"SELECT UNEST","callback":"selectEnd"},"[end]":{"target":"SELECT FROM TABLE","callback":"selectEnd"}}}', true);

        // set initial (first) state
        $this->setState();
        $this->end_state = false;

        $this->input_i = 0;
        $this->input_l = strlen($this->input);

        while ($this->input_i < $this->input_l && !$this->end_state) {
            // epsilon?
            if (isset($this->state_transitions['[epsilon]'])) {
                $target = $this->state_transitions['[epsilon]']['target'];
                $this->callTransitionCallback($this->state_transitions['[epsilon]']);
                $this->setState($target);
                continue;
            }

            $error = true;
            foreach ($this->state_transitions as $condition => $transition) {
                // string condition
                if (substr($condition, 0, 7) == 'string:') {
                    $string = substr($condition, 7);
                    $next = $this->isNextString($string);
                    if ($next != false) {
                        $this->input_i += strlen($string);

                        $this->callTransitionCallback($transition, $next);
                        $this->setState($transition['target']);
                        $error = false;
                        break;
                    }
                // alfanumeric condition
                } elseif ($condition == '[alfanum]') {
                    $next = $this->isNextAlfanum();
                    if ($next !== false) {
                        ++$this->input_i;

                        $this->callTransitionCallback($transition, $next);
                        $this->setState($transition['target']);
                        $error = false;
                        break;
                    }

                // whitespace condition
                } elseif ($condition == '[whitespace]') {
                    $next = $this->isNextWhitespace();
                    if ($next !== false) {
                        ++$this->input_i;

                        $this->callTransitionCallback($transition, $next);
                        $this->setState($transition['target']);
                        $error = false;
                        break;
                    }
                }
            }

            if ($error) {
                echo "\nAt state {$this->state_id}\n";
                echo 'Unexpected '.$this->input[$this->input_i]."\n";
                echo 'Expected: '.print_r(array_keys($this->state_transitions), true);
                die;
            }
        }

        // end state callback?
        if (isset($this->state_transitions['[end]'])) {
            $this->callTransitionCallback($this->state_transitions['[end]']);
        }
    }

    protected function setState($id = null)
    {
        if ($id === null) {
            $this->state_id = reset($this->states);
        } else {
            $this->state_id = $id;
        }

        if (!empty($this->transitions[$this->state_id])) {
            $this->state_transitions = $this->transitions[$this->state_id];
        } else {
            $this->state_transitions = [];
            $this->end_state = true;
        }
    }

    protected function isNextString($string)
    {
        $len = strlen($string);
        $next_string = substr($this->input, $this->input_i, $len);

        if (strtolower($string) === strtolower($next_string)) {
            return $next_string;
        } else {
            return false;
        }
    }

    protected function isNextWhitespace()
    {
        $next = trim($this->input[$this->input_i]);
        if (strlen($next) === 0) {
            return $next;
        } else {
            return false;
        }
    }

    protected function isNextAlfanum()
    {
        $next = $this->input[$this->input_i];
        if (preg_match('/[A-Za-z0-9]/', $next) > 0) {
            return $next;
        } else {
            return false;
        }
    }

    protected function callTransitionCallback($transition, $param = null)
    {
        if (!empty($transition['callback'])) {
            $this->{$transition['callback']}($param);
        }
    }

    // Automatically generated methods that should be overriden in the child class
    /**
     * Callback called when swithing states:
     * START => SELECT; On condition: string:select
     * SELECT NEST => SELECT; On condition: string:select
    */
    public function selectBegin($param = null)
    {

    }

    /**
     * Callback called when swithing states:
     * SELECT => SELECT NEST; On condition: string:(
    */
    public function selectNest($param = null)
    {

    }

    /**
     * Callback called when swithing states:
     * SELECT => SELECT ALL; On condition: string:*
    */
    public function selectAddAllColumns($param = null)
    {

    }

    /**
     * Callback called when swithing states:
     * SELECT => SELECT FROM; On condition: string:from
     * SELECT ALL => SELECT FROM; On condition: string:from
     * SELECT ACOL => SELECT FROM; On condition: string:from
    */
    public function selectFromBegin($param = null)
    {

    }

    /**
     * Callback called when swithing states:
     * SELECT => SELECT COL; On condition: [alfanum]
     * SELECT COL => SELECT COL; On condition: [alfanum]
    */
    public function selectAddColumnPush($param = null)
    {

    }

    /**
     * Callback called when swithing states:
     * SELECT COL => SELECT; On condition: string:,
     * SELECT COL => SELECT ACOL; On condition: [whitespace]
    */
    public function selectAddColumn($param = null)
    {

    }

    /**
     * Callback called when swithing states:
     * SELECT COL => SELECT TABLE COL; On condition: string:.
    */
    public function selectAddColumnTablePush($param = null)
    {

    }

    /**
     * Callback called when swithing states:
     * SELECT FROM => SELECT FROM TABLE; On condition: [alfanum]
     * SELECT FROM TABLE => SELECT FROM TABLE; On condition: [alfanum]
    */
    public function selectFromPush($param = null)
    {

    }

    /**
     * Callback called when swithing states:
     * SELECT FROM TABLE => SELECT UNEST; On condition: string:)
     * SELECT FROM TABLE => SELECT FROM TABLE; On condition: [end]
    */
    public function selectEnd($param = null)
    {

    }

}
