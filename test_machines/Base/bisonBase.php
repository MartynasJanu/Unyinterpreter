<?php

namespace test_machines\Base;

class bisonBase {
    protected $input = '';
    protected $input_i = 0;
    protected $input_l = 0;

    protected $states = [
        'START',
        'C_DECL',
        'UNION',
        'UNION_STRUCT',
        'UNION_TYPE',
        'UNION_PREID',
        'UNION_ID',
        'TOKEN_PRE',
        'TOKEN',
        'TYPE_PRE',
        'TYPE',
        'TYPE_POST',
        'TYPE_SYML',
        'TYPE_SYM',
        'OTHER_TOKEN',
        'GRAM',
        'GRAM_RULE',
        'GRAM_RULE_AND',
        'GRAM_RULE_ACTION_PROC',
        'GRAM_RULE_SYMBOL',
        'GRAM_RULE_ACTION'
    ];

    protected $transitions;

    protected $state_id;
    protected $state_transitions;

    protected $end_state;

    public function execute($input)
    {
        $this->input = $input;

        $this->transitions = unserialize('a:30:{s:5:"START";a:8:{s:12:"[whitespace]";a:2:{s:6:"target";s:5:"START";s:8:"callback";N;}s:9:"string:/*";a:2:{s:6:"target";s:7:"COMMENT";s:8:"callback";s:12:"startComment";}s:9:"string:%{";a:2:{s:6:"target";s:6:"C_DECL";s:8:"callback";s:10:"startCdecl";}s:15:"string:%union {";a:2:{s:6:"target";s:5:"UNION";s:8:"callback";s:10:"startUnion";}s:13:"string:%token";a:2:{s:6:"target";s:9:"TOKEN_PRE";s:8:"callback";N;}s:12:"string:%type";a:2:{s:6:"target";s:8:"TYPE_PRE";s:8:"callback";N;}s:26:"regex:/\%([a-zA-Z0-9-_]+)/";a:2:{s:6:"target";s:11:"OTHER_TOKEN";s:8:"callback";s:15:"startOtherToken";}s:9:"string:%%";a:2:{s:6:"target";s:4:"GRAM";s:8:"callback";s:9:"startGram";}}s:7:"COMMENT";a:2:{s:9:"string:*/";a:2:{s:6:"target";s:5:"START";s:8:"callback";s:10:"endComment";}s:5:"[any]";a:2:{s:6:"target";s:7:"COMMENT";s:8:"callback";s:11:"pushComment";}}s:6:"C_DECL";a:4:{s:9:"string:%}";a:2:{s:6:"target";s:5:"START";s:8:"callback";s:9:"stopCdecl";}s:9:"string:/*";a:2:{s:6:"target";s:14:"C_DECL_COMMENT";s:8:"callback";s:9:"pushCdecl";}s:8:"string:"";a:2:{s:6:"target";s:13:"C_DECL_STRING";s:8:"callback";s:9:"pushCdecl";}s:5:"[any]";a:2:{s:6:"target";s:6:"C_DECL";s:8:"callback";s:9:"pushCdecl";}}s:14:"C_DECL_COMMENT";a:2:{s:9:"string:*/";a:2:{s:6:"target";s:6:"C_DECL";s:8:"callback";s:9:"pushCdecl";}s:5:"[any]";a:2:{s:6:"target";s:14:"C_DECL_COMMENT";s:8:"callback";s:9:"pushCdecl";}}s:13:"C_DECL_STRING";a:2:{s:8:"string:"";a:2:{s:6:"target";s:6:"C_DECL";s:8:"callback";s:9:"pushCdecl";}s:5:"[any]";a:2:{s:6:"target";s:13:"C_DECL_STRING";s:8:"callback";s:9:"pushCdecl";}}s:5:"UNION";a:5:{s:9:"string:/*";a:2:{s:6:"target";s:13:"UNION_COMMENT";s:8:"callback";N;}s:8:"string:}";a:2:{s:6:"target";s:5:"START";s:8:"callback";s:8:"endUnion";}s:25:"regex:/\s+struct(\s*?)\{/";a:2:{s:6:"target";s:12:"UNION_STRUCT";s:8:"callback";s:14:"startUnionType";}s:15:"[notwhitespace]";a:2:{s:6:"target";s:10:"UNION_TYPE";s:8:"callback";s:14:"startUnionType";}s:5:"[any]";a:2:{s:6:"target";s:5:"UNION";s:8:"callback";N;}}s:13:"UNION_COMMENT";a:2:{s:9:"string:*/";a:2:{s:6:"target";s:5:"UNION";s:8:"callback";N;}s:5:"[any]";a:2:{s:6:"target";s:13:"UNION_COMMENT";s:8:"callback";N;}}s:12:"UNION_STRUCT";a:2:{s:8:"string:}";a:2:{s:6:"target";s:11:"UNION_PREID";s:8:"callback";s:14:"endUnionStruct";}s:5:"[any]";a:2:{s:6:"target";s:12:"UNION_STRUCT";s:8:"callback";s:13:"pushUnionType";}}s:10:"UNION_TYPE";a:2:{s:15:"[notwhitespace]";a:2:{s:6:"target";s:10:"UNION_TYPE";s:8:"callback";s:13:"pushUnionType";}s:12:"[whitespace]";a:2:{s:6:"target";s:11:"UNION_PREID";s:8:"callback";s:12:"endUnionType";}}s:11:"UNION_PREID";a:2:{s:12:"[whitespace]";a:2:{s:6:"target";s:11:"UNION_PREID";s:8:"callback";N;}s:15:"[notwhitespace]";a:2:{s:6:"target";s:8:"UNION_ID";s:8:"callback";s:12:"startUnionId";}}s:8:"UNION_ID";a:2:{s:8:"string:;";a:2:{s:6:"target";s:5:"UNION";s:8:"callback";s:10:"endUnionId";}s:5:"[any]";a:2:{s:6:"target";s:8:"UNION_ID";s:8:"callback";s:11:"pushUnionId";}}s:9:"TOKEN_PRE";a:2:{s:12:"[whitespace]";a:2:{s:6:"target";s:9:"TOKEN_PRE";s:8:"callback";N;}s:15:"[notwhitespace]";a:2:{s:6:"target";s:5:"TOKEN";s:8:"callback";s:10:"startToken";}}s:5:"TOKEN";a:2:{s:12:"[whitespace]";a:2:{s:6:"target";s:5:"START";s:8:"callback";s:8:"endToken";}s:5:"[any]";a:2:{s:6:"target";s:5:"TOKEN";s:8:"callback";s:9:"pushToken";}}s:8:"TYPE_PRE";a:2:{s:12:"[whitespace]";a:2:{s:6:"target";s:8:"TYPE_PRE";s:8:"callback";N;}s:8:"string:<";a:2:{s:6:"target";s:4:"TYPE";s:8:"callback";s:9:"startType";}}s:4:"TYPE";a:2:{s:8:"string:>";a:2:{s:6:"target";s:9:"TYPE_POST";s:8:"callback";s:7:"endType";}s:5:"[any]";a:2:{s:6:"target";s:4:"TYPE";s:8:"callback";s:8:"pushType";}}s:9:"TYPE_POST";a:7:{s:12:"[whitespace]";a:2:{s:6:"target";s:9:"TYPE_POST";s:8:"callback";N;}s:9:"string:/*";a:2:{s:6:"target";s:17:"TYPE_POST_COMMENT";s:8:"callback";N;}s:8:"string:%";a:2:{s:6:"target";s:5:"START";s:8:"callback";s:22:"endTypeSymbolNoConsume";}s:8:"string:\'";a:2:{s:6:"target";s:9:"TYPE_SYML";s:8:"callback";s:22:"startTypeSymbolLiteral";}s:8:"string:;";a:2:{s:6:"target";s:5:"START";s:8:"callback";N;}s:15:"[notwhitespace]";a:2:{s:6:"target";s:8:"TYPE_SYM";s:8:"callback";s:15:"startTypeSymbol";}s:5:"[any]";a:2:{s:6:"target";s:5:"START";s:8:"callback";s:22:"endTypeSymbolNoConsume";}}s:17:"TYPE_POST_COMMENT";a:2:{s:9:"string:*/";a:2:{s:6:"target";s:9:"TYPE_POST";s:8:"callback";N;}s:5:"[any]";a:2:{s:6:"target";s:17:"TYPE_POST_COMMENT";s:8:"callback";N;}}s:9:"TYPE_SYML";a:2:{s:8:"string:\'";a:2:{s:6:"target";s:9:"TYPE_POST";s:8:"callback";s:20:"endTypeSymbolLiteral";}s:5:"[any]";a:2:{s:6:"target";s:9:"TYPE_SYML";s:8:"callback";s:21:"pushTypeSymbolLiteral";}}s:8:"TYPE_SYM";a:3:{s:23:"regex:/([A-Za-z0-9_]+)/";a:2:{s:6:"target";s:9:"TYPE_POST";s:8:"callback";s:14:"pushTypeSymbol";}s:12:"[whitespace]";a:2:{s:6:"target";s:9:"TYPE_POST";s:8:"callback";s:13:"endTypeSymbol";}s:5:"[any]";a:2:{s:6:"target";s:5:"START";s:8:"callback";s:22:"endTypeSymbolNoConsume";}}s:11:"OTHER_TOKEN";a:3:{s:9:"[newline]";a:2:{s:6:"target";s:5:"START";s:8:"callback";s:13:"endOtherToken";}s:24:"regex:/\s+\/\*(.*?)\*\//";a:2:{s:6:"target";s:11:"OTHER_TOKEN";s:8:"callback";N;}s:5:"[any]";a:2:{s:6:"target";s:11:"OTHER_TOKEN";s:8:"callback";s:14:"pushOtherToken";}}s:4:"GRAM";a:5:{s:12:"[whitespace]";a:2:{s:6:"target";s:4:"GRAM";s:8:"callback";N;}s:9:"string:/*";a:2:{s:6:"target";s:12:"GRAM_COMMENT";s:8:"callback";N;}s:17:"regex:/^\/\/(.*)/";a:2:{s:6:"target";s:20:"GRAM_COMMENT_ONELINE";s:8:"callback";N;}s:25:"regex:/([a-zA-Z0-9_]+)\:/";a:2:{s:6:"target";s:9:"GRAM_RULE";s:8:"callback";s:13:"startGramRule";}s:5:"[end]";a:2:{s:6:"target";s:4:"GRAM";s:8:"callback";s:3:"end";}}s:12:"GRAM_COMMENT";a:2:{s:9:"string:*/";a:2:{s:6:"target";s:4:"GRAM";s:8:"callback";N;}s:5:"[any]";a:2:{s:6:"target";s:12:"GRAM_COMMENT";s:8:"callback";N;}}s:20:"GRAM_COMMENT_ONELINE";a:2:{s:9:"[newline]";a:2:{s:6:"target";s:4:"GRAM";s:8:"callback";N;}s:5:"[any]";a:2:{s:6:"target";s:20:"GRAM_COMMENT_ONELINE";s:8:"callback";N;}}s:9:"GRAM_RULE";a:6:{s:9:"string:/*";a:2:{s:6:"target";s:17:"GRAM_RULE_COMMENT";s:8:"callback";N;}s:12:"[whitespace]";a:2:{s:6:"target";s:9:"GRAM_RULE";s:8:"callback";N;}s:8:"string:|";a:2:{s:6:"target";s:13:"GRAM_RULE_AND";s:8:"callback";s:17:"startGramRulePipe";}s:8:"string:{";a:2:{s:6:"target";s:21:"GRAM_RULE_ACTION_PROC";s:8:"callback";s:19:"startGramRuleAction";}s:8:"string:;";a:2:{s:6:"target";s:4:"GRAM";s:8:"callback";s:11:"endGramRule";}s:15:"[notwhitespace]";a:2:{s:6:"target";s:16:"GRAM_RULE_SYMBOL";s:8:"callback";s:19:"startGramRuleSymbol";}}s:17:"GRAM_RULE_COMMENT";a:2:{s:9:"string:*/";a:2:{s:6:"target";s:9:"GRAM_RULE";s:8:"callback";N;}s:5:"[any]";a:2:{s:6:"target";s:17:"GRAM_RULE_COMMENT";s:8:"callback";N;}}s:13:"GRAM_RULE_AND";a:3:{s:12:"[whitespace]";a:2:{s:6:"target";s:13:"GRAM_RULE_AND";s:8:"callback";N;}s:8:"string:{";a:2:{s:6:"target";s:21:"GRAM_RULE_ACTION_PROC";s:8:"callback";s:19:"startGramRuleAction";}s:15:"[notwhitespace]";a:2:{s:6:"target";s:16:"GRAM_RULE_SYMBOL";s:8:"callback";s:19:"startGramRuleSymbol";}}s:16:"GRAM_RULE_SYMBOL";a:5:{s:8:"string:|";a:2:{s:6:"target";s:13:"GRAM_RULE_AND";s:8:"callback";s:17:"startGramRulePipe";}s:8:"string:{";a:2:{s:6:"target";s:21:"GRAM_RULE_ACTION_PROC";s:8:"callback";s:19:"startGramRuleAction";}s:15:"[notwhitespace]";a:2:{s:6:"target";s:16:"GRAM_RULE_SYMBOL";s:8:"callback";s:18:"pushGramRuleSymbol";}s:12:"[whitespace]";a:2:{s:6:"target";s:9:"GRAM_RULE";s:8:"callback";s:17:"endGramRuleSymbol";}s:8:"string:;";a:2:{s:6:"target";s:4:"GRAM";s:8:"callback";s:11:"endGramRule";}}s:21:"GRAM_RULE_ACTION_PROC";a:3:{s:9:"string:/*";a:2:{s:6:"target";s:29:"GRAM_RULE_ACTION_PROC_COMMENT";s:8:"callback";s:18:"pushGramRuleAction";}s:28:"call:isGramRuleActionProcEnd";a:2:{s:6:"target";s:16:"GRAM_RULE_ACTION";s:8:"callback";N;}s:5:"[any]";a:2:{s:6:"target";s:21:"GRAM_RULE_ACTION_PROC";s:8:"callback";s:18:"pushGramRuleAction";}}s:29:"GRAM_RULE_ACTION_PROC_COMMENT";a:2:{s:9:"string:*/";a:2:{s:6:"target";s:21:"GRAM_RULE_ACTION_PROC";s:8:"callback";s:18:"pushGramRuleAction";}s:5:"[any]";a:2:{s:6:"target";s:29:"GRAM_RULE_ACTION_PROC_COMMENT";s:8:"callback";s:18:"pushGramRuleAction";}}s:16:"GRAM_RULE_ACTION";a:4:{s:12:"[whitespace]";a:2:{s:6:"target";s:16:"GRAM_RULE_ACTION";s:8:"callback";N;}s:8:"string:|";a:2:{s:6:"target";s:13:"GRAM_RULE_AND";s:8:"callback";s:17:"startGramRulePipe";}s:8:"string:;";a:2:{s:6:"target";s:4:"GRAM";s:8:"callback";s:11:"endGramRule";}s:15:"[notwhitespace]";a:2:{s:6:"target";s:16:"GRAM_RULE_SYMBOL";s:8:"callback";s:19:"startGramRuleSymbol";}}}');

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
                echo 'Input: \''.substr($this->input, $this->input_i, 50)."'\n";
                echo 'Expected: '.print_r(array_keys($this->state_transitions), true);
                die;
            }
        }

        // end state callback?
        if (isset($this->state_transitions['[end]'])) {
            $this->callTransitionCallback($this->state_transitions['[end]']);
        }

        //echo 'ENDED at state '.$this->state_id."\n\n";
    }

    protected function satisfyCondition($transition, $next = null, $inc_input = 1, $callback = true)
    {
        //echo $this->state_id.' => '.$transition['target'].': '.$next."\n\n";

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
    /**
     * Callback called when swithing states:
     * START => COMMENT; On condition: string:/*
    */
    public function startComment($param = null)
    {

    }

    /**
     * Callback called when swithing states:
     * START => C_DECL; On condition: string:%{
    */
    public function startCdecl($param = null)
    {

    }

    /**
     * Callback called when swithing states:
     * START => UNION; On condition: string:%union {
    */
    public function startUnion($param = null)
    {

    }

    /**
     * Callback called when swithing states:
     * START => OTHER_TOKEN; On condition: regex:/\%([a-zA-Z0-9-_]+)/
    */
    public function startOtherToken($param = null)
    {

    }

    /**
     * Callback called when swithing states:
     * START => GRAM; On condition: string:%%
    */
    public function startGram($param = null)
    {

    }

    /**
     * Callback called when swithing states:
     * COMMENT => START; On condition: string:* /
    */
    public function endComment($param = null)
    {

    }

    /**
     * Callback called when swithing states:
     * COMMENT => COMMENT; On condition: [any]
    */
    public function pushComment($param = null)
    {

    }

    /**
     * Callback called when swithing states:
     * C_DECL => START; On condition: string:%}
    */
    public function stopCdecl($param = null)
    {

    }

    /**
     * Callback called when swithing states:
     * C_DECL => C_DECL_COMMENT; On condition: string:/*
     * C_DECL => C_DECL_STRING; On condition: string:"
     * C_DECL => C_DECL; On condition: [any]
     * C_DECL_COMMENT => C_DECL; On condition: string:* /
     * C_DECL_COMMENT => C_DECL_COMMENT; On condition: [any]
     * C_DECL_STRING => C_DECL; On condition: string:"
     * C_DECL_STRING => C_DECL_STRING; On condition: [any]
    */
    public function pushCdecl($param = null)
    {

    }

    /**
     * Callback called when swithing states:
     * UNION => START; On condition: string:}
    */
    public function endUnion($param = null)
    {

    }

    /**
     * Callback called when swithing states:
     * UNION => UNION_STRUCT; On condition: regex:/\s+struct(\s*?)\{/
     * UNION => UNION_TYPE; On condition: [notwhitespace]
    */
    public function startUnionType($param = null)
    {

    }

    /**
     * Callback called when swithing states:
     * UNION_STRUCT => UNION_PREID; On condition: string:}
    */
    public function endUnionStruct($param = null)
    {

    }

    /**
     * Callback called when swithing states:
     * UNION_STRUCT => UNION_STRUCT; On condition: [any]
     * UNION_TYPE => UNION_TYPE; On condition: [notwhitespace]
    */
    public function pushUnionType($param = null)
    {

    }

    /**
     * Callback called when swithing states:
     * UNION_TYPE => UNION_PREID; On condition: [whitespace]
    */
    public function endUnionType($param = null)
    {

    }

    /**
     * Callback called when swithing states:
     * UNION_PREID => UNION_ID; On condition: [notwhitespace]
    */
    public function startUnionId($param = null)
    {

    }

    /**
     * Callback called when swithing states:
     * UNION_ID => UNION; On condition: string:;
    */
    public function endUnionId($param = null)
    {

    }

    /**
     * Callback called when swithing states:
     * UNION_ID => UNION_ID; On condition: [any]
    */
    public function pushUnionId($param = null)
    {

    }

    /**
     * Callback called when swithing states:
     * TOKEN_PRE => TOKEN; On condition: [notwhitespace]
    */
    public function startToken($param = null)
    {

    }

    /**
     * Callback called when swithing states:
     * TOKEN => START; On condition: [whitespace]
    */
    public function endToken($param = null)
    {

    }

    /**
     * Callback called when swithing states:
     * TOKEN => TOKEN; On condition: [any]
    */
    public function pushToken($param = null)
    {

    }

    /**
     * Callback called when swithing states:
     * TYPE_PRE => TYPE; On condition: string:<
    */
    public function startType($param = null)
    {

    }

    /**
     * Callback called when swithing states:
     * TYPE => TYPE_POST; On condition: string:>
    */
    public function endType($param = null)
    {

    }

    /**
     * Callback called when swithing states:
     * TYPE => TYPE; On condition: [any]
    */
    public function pushType($param = null)
    {

    }

    /**
     * Callback called when swithing states:
     * TYPE_POST => START; On condition: string:%
     * TYPE_POST => START; On condition: [any]
     * TYPE_SYM => START; On condition: [any]
    */
    public function endTypeSymbolNoConsume($param = null)
    {

    }

    /**
     * Callback called when swithing states:
     * TYPE_POST => TYPE_SYML; On condition: string:'
    */
    public function startTypeSymbolLiteral($param = null)
    {

    }

    /**
     * Callback called when swithing states:
     * TYPE_POST => TYPE_SYM; On condition: [notwhitespace]
    */
    public function startTypeSymbol($param = null)
    {

    }

    /**
     * Callback called when swithing states:
     * TYPE_SYML => TYPE_POST; On condition: string:'
    */
    public function endTypeSymbolLiteral($param = null)
    {

    }

    /**
     * Callback called when swithing states:
     * TYPE_SYML => TYPE_SYML; On condition: [any]
    */
    public function pushTypeSymbolLiteral($param = null)
    {

    }

    /**
     * Callback called when swithing states:
     * TYPE_SYM => TYPE_POST; On condition: regex:/([A-Za-z0-9_]+)/
    */
    public function pushTypeSymbol($param = null)
    {

    }

    /**
     * Callback called when swithing states:
     * TYPE_SYM => TYPE_POST; On condition: [whitespace]
    */
    public function endTypeSymbol($param = null)
    {

    }

    /**
     * Callback called when swithing states:
     * OTHER_TOKEN => START; On condition: [newline]
    */
    public function endOtherToken($param = null)
    {

    }

    /**
     * Callback called when swithing states:
     * OTHER_TOKEN => OTHER_TOKEN; On condition: [any]
    */
    public function pushOtherToken($param = null)
    {

    }

    /**
     * Callback called when swithing states:
     * GRAM => GRAM_RULE; On condition: regex:/([a-zA-Z0-9_]+)\:/
    */
    public function startGramRule($param = null)
    {

    }

    /**
     * Callback called when swithing states:
     * GRAM => GRAM; On condition: [end]
    */
    public function end($param = null)
    {

    }

    /**
     * Callback called when swithing states:
     * GRAM_RULE => GRAM_RULE_AND; On condition: string:|
     * GRAM_RULE_SYMBOL => GRAM_RULE_AND; On condition: string:|
     * GRAM_RULE_ACTION => GRAM_RULE_AND; On condition: string:|
    */
    public function startGramRulePipe($param = null)
    {

    }

    /**
     * Callback called when swithing states:
     * GRAM_RULE => GRAM_RULE_ACTION_PROC; On condition: string:{
     * GRAM_RULE_AND => GRAM_RULE_ACTION_PROC; On condition: string:{
     * GRAM_RULE_SYMBOL => GRAM_RULE_ACTION_PROC; On condition: string:{
    */
    public function startGramRuleAction($param = null)
    {

    }

    /**
     * Callback called when swithing states:
     * GRAM_RULE => GRAM; On condition: string:;
     * GRAM_RULE_SYMBOL => GRAM; On condition: string:;
     * GRAM_RULE_ACTION => GRAM; On condition: string:;
    */
    public function endGramRule($param = null)
    {

    }

    /**
     * Callback called when swithing states:
     * GRAM_RULE => GRAM_RULE_SYMBOL; On condition: [notwhitespace]
     * GRAM_RULE_AND => GRAM_RULE_SYMBOL; On condition: [notwhitespace]
     * GRAM_RULE_ACTION => GRAM_RULE_SYMBOL; On condition: [notwhitespace]
    */
    public function startGramRuleSymbol($param = null)
    {

    }

    /**
     * Callback called when swithing states:
     * GRAM_RULE_SYMBOL => GRAM_RULE_SYMBOL; On condition: [notwhitespace]
    */
    public function pushGramRuleSymbol($param = null)
    {

    }

    /**
     * Callback called when swithing states:
     * GRAM_RULE_SYMBOL => GRAM_RULE; On condition: [whitespace]
    */
    public function endGramRuleSymbol($param = null)
    {

    }

    /**
     * Callback called when swithing states:
     * GRAM_RULE_ACTION_PROC => GRAM_RULE_ACTION_PROC_COMMENT; On condition: string:/*
     * GRAM_RULE_ACTION_PROC => GRAM_RULE_ACTION_PROC; On condition: [any]
     * GRAM_RULE_ACTION_PROC_COMMENT => GRAM_RULE_ACTION_PROC; On condition: string:* /
     * GRAM_RULE_ACTION_PROC_COMMENT => GRAM_RULE_ACTION_PROC_COMMENT; On condition: [any]
    */
    public function pushGramRuleAction($param = null)
    {

    }

}
