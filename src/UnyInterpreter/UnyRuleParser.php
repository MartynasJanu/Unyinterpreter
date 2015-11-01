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

            // special cases
            // LOOP
            if (substr($lines[$i], 0, 5) == 'LOOP ') {
                $loop_info = explode(' ', $lines[$i], 3);
                $loop_init_state = $loop_info[1];
                $loop_title = $loop_info[2];

                $enter = $lines[$i + 1];
                $repeat = $lines[$i + 2];
                $exit = $lines[$i + 3];

                // enter transition
                $this->transitions[$loop_init_state][$enter] = [
                    'target' => $loop_title,
                    'callback' => null,
                ];


                $this->transitions[$loop_title] = [
                    // repeat transition
                    $repeat => [
                        'target' => $loop_title,
                        'callback' => null,
                    ],
                    // exit transition
                    $exit => [
                        'target' => $loop_init_state,
                        'callback' => null,
                    ],
                ];

                $i += 4;

            // general case
            } else {
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

                $this->transitions[$transition['init']][$transition['condition']] = [
                    'target' => $transition['target'],
                    'callback' => $transition['callback'],
                ];
            }

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

        print_R($this->transitions);die;
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
