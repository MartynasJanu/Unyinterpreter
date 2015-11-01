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

        $this->transitions = unserialize('%TRANSITIONS%');

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
                // method callback condition
                if (substr($condition, 0, 5) == 'call:') {
                    $f = substr($condition, 5);
                    $next = $this->input[$this->input_i];
                    if ($this->$f($next)) {
                        $this->satisfyCondition($transition, '', 0);
                        $error = false;
                        break;
                    }

                // string condition
                } elseif (substr($condition, 0, 7) == 'string:') {
                    $string = substr($condition, 7);
                    $next = $this->isNextString($string);
                    if ($next !== false) {
                        $this->satisfyCondition($transition, $next, strlen($string));
                        $error = false;
                        break;
                    }

                // regex condition
                } elseif (substr($condition, 0, 6) == 'regex:') {
                    $regex = substr($condition, 6);
                    $next = $this->isNextRegex($regex);
                    if ($next !== false) {
                        $this->satisfyCondition($transition, $next, strlen($next));
                        $error = false;
                        break;
                    }

                // alfanumeric condition
                } elseif ($condition == '[alfanum]') {
                    $next = $this->isNextAlfanum();
                    if ($next !== false) {
                        $this->satisfyCondition($transition, $next);
                        $error = false;
                        break;
                    }

                // newline condition
                } elseif ($condition == '[newline]') {
                    $next = $this->isNextNewline();
                    if ($next !== false) {
                        $this->satisfyCondition($transition, $next);
                        $error = false;
                        break;
                    }

                // whitespace condition
                } elseif ($condition == '[whitespace]') {
                    $next = $this->isNextWhitespace();
                    if ($next !== false) {
                        $this->satisfyCondition($transition, $next);
                        $error = false;
                        break;
                    }

                // notwhitespace condition
                } elseif ($condition == '[notwhitespace]') {
                    $next = $this->isNextNotWhitespace();
                    if ($next !== false) {
                        $this->satisfyCondition($transition, $next);
                        $error = false;
                        break;
                    }

                // any character condition
                } elseif ($condition == '[any]') {
                    $next = $this->isNextAny();
                    if ($next !== false) {
                        $this->satisfyCondition($transition, $next);
                        $error = false;
                        break;
                    }
                }
            }

            if ($error) {
                echo "\nAt state {$this->state_id}\n";
                echo 'Unexpected \''.$this->input[$this->input_i]."'\n";
                echo 'Input: \''.substr($this->input, $this->input_i, 10)."'\n";
                echo 'Expected: '.print_r(array_keys($this->state_transitions), true);
                die;
            }
        }

        // end state callback?
        if (isset($this->state_transitions['[end]'])) {
            $this->callTransitionCallback($this->state_transitions['[end]']);
        }
    }

    protected function satisfyCondition($transition, $next = null, $inc_input = 1, $callback = true)
    {
        if ($callback) {
            if ($this->callTransitionCallback($transition, $next) !== true) {
                $this->input_i += $inc_input;
            }
        } else {
            $this->input_i += $inc_input;
        }
        $this->setState($transition['target']);
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

    protected function isNextRegex($regex)
    {
        $next_line = substr($this->input, $this->input_i);
        if ($next_line[0] != "\n") {
            $next_line = strstr($next_line, "\n", true);
            if ($next_line == '') {
                $next_line = substr($this->input, $this->input_i);
            }
        } else {
            $next_line = '';
        }

        $matches = [];
        preg_match($regex, $next_line, $matches);

        if (count($matches) === 0) {
            return false;
        } else {
            return $matches[0];
        }
    }

    protected function isNextNewline()
    {
        $next = $this->input[$this->input_i];
        if ($next == "\n" || $next == "\r") {
            return $next;
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

    protected function isNextNotWhitespace()
    {
        $next = $this->input[$this->input_i];
        $next_trimmed = trim($next);
        if (strlen($next_trimmed) === 0) {
            return false;
        } else {
            return $next;
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

    protected function isNextAny()
    {
        if ($this->input_i == $this->input_l) {
            return false;
        }

        return $this->input[$this->input_i];
    }

    protected function callTransitionCallback($transition, $param = null)
    {
        if (!empty($transition['callback'])) {
            return $this->{$transition['callback']}($param);
        }

        return false;
    }

    // Automatically generated methods that should be overriden in the child class
%METHODS%}
