<?php

namespace UnyInterpreter;

class UnyRuleParser
{
    public $states;

    public $transitions;

    public function parseFile($filename)
    {
        return $this->parse(file_get_contents($filename));
    }

    public function parse($string)
    {
        $this->states = [];
        $this->transitions = [];

        $lines = explode("\n", $string);

        $i = 0;
        $c = count($lines);
        $end = true;

        while ($i < $c) {
            $transition = [];

            // initial state
            if (empty($lines[$i])) {
                $this->error($lines[$i - 1], $i + 1, 'init state id');
            }
            $transition['init'] = trim($lines[$i]);
            ++$i;

            // target state
            if (empty($lines[$i])) {
                $this->error($lines[$i - 1], $i + 1, 'target state id');
            }
            $transition['target'] = trim($lines[$i]);
            ++$i;

            // condition
            if (empty($lines[$i])) {
                $this->error($lines[$i - 1], $i + 1, 'transition condition');
            }
            $transition['condition'] = trim($lines[$i]);
            ++$i;
            if ($i == $c) {
                break;
            }

            // callback
            if (empty($lines[$i])) {
                $transition['callback'] = null;
            } else {
                $transition['callback'] = trim($lines[$i]);
            }

            $this->states[$transition['init']] = 1;
            $this->states[$transition['target']] = 1;

            $this->transitions[$transition['init']][$transition['target']] = [
                'condition' => $transition['condition'],
                'callback' => $transition['callback'],
            ];


            // skip empty lines
            ++$i;
            if ($i == $c) {
                break;
            }
            $line = trim($lines[$i]);
            while (strlen($line) == 0 && $i + 1 < $c) {
                ++$i;
                $line = trim($lines[$i]);
            }
        }

        $this->states = array_keys($this->states);
    }

    protected function error($line, $number, $expected = '')
    {
        echo "UnyI: Syntax error after line ({$number}): {$line}\n";
        if (!empty($expected)) {
            echo "Expected: {$expected}\n";
        }
        die;
    }
}
