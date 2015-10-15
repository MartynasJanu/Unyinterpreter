<?php

namespace %PATH%\Base;

class %NAME%Base {
    protected $input = '';
    protected $input_i = 0;
    protected $input_l = 0;

    protected $states = [
        %STATES_ARRAY%
    ];

    protected $transitions;

    protected $state_id;
    protected $state_transitions;

    protected $end_state;

    public function execute($input)
    {
        $this->input = $input;

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
        $this->{$transition['callback']}($param);
    }

    // Automatically generated methods that should be overriden in the child class
%METHODS%}
