<?php

namespace UnyInterpreter;

require_once 'src/UnyInterpreter/UnyRuleParser.php';

class UnyMachineBuilder
{
    protected $rules = null;

    public function addRulesFromFile($filename)
    {
        $parser = new UnyRuleParser();
        $parser->parseFile($filename);
        $this->addRulesFromParser($parser);
    }

    public function addRulesFromParser(\UnyInterpreter\UnyRuleParser $parser)
    {
        if ($this->rules === null) {
            $this->rules = ['states' => [], 'transitions' => []];
        }

        $this->rules['states'] = array_merge($this->rules['states'], $parser->states);
        $this->rules['transitions'] = array_merge($this->rules['transitions'], $parser->transitions);
    }

    public function addRulesFromString($string)
    {
        $parser = new UnyRuleParser();
        $parser->parse($string);
        $this->addRulesFromParser($parser);
    }

    public function buildMachine($path, $basename)
    {
        $path = trim($path, '/');

        $base_path = $path.'/Base';
        $base_file_path = $base_path.'/'.$basename.'Base.php';
        $child_file_path = $path.'/'.$basename.'.php';

        // create directories
        if (!is_dir($path) && !mkdir($path, 0640, true)) {
            die('Failed to create directory structure');
        }
        if (!is_dir($base_path) && !mkdir($path.'/Base', 0640, true)) {
            die('Failed to create directory structure');
        }

        $base = $this->buildBase($path, $basename);
        file_put_contents($base_file_path, $base);

        if (!file_exists($child_file_path)) {
            $base_child = $this->buildBaseChild($path, $basename);
            file_put_contents($child_file_path, $base_child);
        }
    }

    protected function buildBase($path, $basename)
    {
        $tab = str_pad(" ", 4);

        $replace = [];
        $replace['%NAME%'] = $basename;
        $replace['%PATH%'] = $path;

        // states
        $replace['%STATES_ARRAY%'] = "'".implode("', '", $this->rules['states'])."'";
        $replace['%STATES_ARRAY%'] = str_replace(', ', ",\n{$tab}{$tab}", $replace['%STATES_ARRAY%']);

        // transitions
        $replace['%TRANSITIONS%'] = serialize($this->rules['transitions']);

        // methods
        $methods = [];
        foreach ($this->rules['transitions'] as $init => $conditions) {
            foreach ($conditions as $condition => $transition) {
                if (!empty($transition['callback'])) {
                    $methods[$transition['callback']][] = [
                        'transition' => $init.' => '.$transition['target'],
                        'condition' => $condition,
                    ];
                }
            }
        }

        $replace['%METHODS%'] = '';

        foreach ($methods as $name => $method) {
            // method comment
            $replace['%METHODS%'] .= $tab."/**\n";
            $replace['%METHODS%'] .= $tab." * Callback called when swithing states:\n";
            foreach ($method as $trans) {
                $replace['%METHODS%'] .= $tab.' * '.$trans['transition'].'; ';
                $cond = str_replace('*/', '* /', $trans['condition']);
                $replace['%METHODS%'] .= 'On condition: '.$cond."\n";
            }
            $replace['%METHODS%'] .= $tab."*/\n";

            // method body
            $replace['%METHODS%'] .= "{$tab}public function {$name}(\$param = null)\n{$tab}{\n\n";
            $replace['%METHODS%'] .= "{$tab}}\n\n";
        }

        $tpl = file_get_contents(__DIR__.'/Base/machineBase.tpl.php');
        $tpl = str_replace(array_keys($replace), $replace, $tpl);

        return $tpl;
    }

    protected function buildBaseChild($path, $basename)
    {
        $tab = str_pad(" ", 4);

        $replace = [];
        $replace['%NAME%'] = $basename;
        $replace['%PATH%'] = $path;

        // methods
        $methods = [];
        foreach ($this->rules['transitions'] as $init => $conditions) {
            foreach ($conditions as $condition => $transition) {
                if (!empty($transition['callback'])) {
                    $methods[$transition['callback']][] = [
                        'transition' => $init.' => '.$transition['target'],
                        'condition' => $condition,
                    ];
                }
            }
        }

        $replace['%METHODS%'] = '';

        foreach ($methods as $name => $method) {
            // method comment
            $replace['%METHODS%'] .= $tab."/**\n";
            $replace['%METHODS%'] .= $tab." * Callback called when swithing states:\n";
            foreach ($method as $trans) {
                $replace['%METHODS%'] .= $tab.' * '.$trans['transition'].'; ';
                $replace['%METHODS%'] .= 'On condition: '.$trans['condition']."\n";
            }
            $replace['%METHODS%'] .= $tab."*/\n";

            // method body
            $replace['%METHODS%'] .= "{$tab}public function {$name}(\$param = null)\n{$tab}{\n\n";
            $replace['%METHODS%'] .= "{$tab}}\n\n";
        }

        $tpl = file_get_contents(__DIR__.'/machine.tpl.php');
        $tpl = str_replace(array_keys($replace), $replace, $tpl);

        return $tpl;
    }
}
