<?php

namespace test_machines;

require_once 'test_machines/Base/bisonBase.php';

class bison extends Base\bisonBase {
    public $comments = [];
    public $cdecl = '';
    public $unions = [];
    public $other_tokens = [];
    public $types = [];
    public $rules = [];

    public $openbraces = 0;

    /**
     * Callback called when swithing states:
     * START => COMMENT; On condition: string:/*
    */
    public function startComment($param = null)
    {
        $this->comments[] = '';
    }

    /**
     * Callback called when swithing states:
     * COMMENT => START; On condition: string:* /
    */
    public function endComment($param = null)
    {
        //echo "\nCOMMENT: ".$this->comment."\n";
    }

    /**
     * Callback called when swithing states:
     * COMMENT => COMMENT; On condition: [any]
    */
    public function pushComment($param = null)
    {
        $this->comments[count($this->comments)-1] .= $param;
    }

    /**
     * Callback called when swithing states:
     * START => C_DECL; On condition: %{
    */
    public function startCdecl($param = null)
    {
        $this->cdecl = '';
    }

    /**
     * Callback called when swithing states:
     * C_DECL => START; On condition: %}
    */
    public function stopCdecl($param = null)
    {
        //echo "\nCDECL: ".$this->cdecl."\n";
    }

    /**
     * Callback called when swithing states:
     * C_DECL => C_DECL; On condition: [any]
    */
    public function pushCdecl($param = null)
    {
        $this->cdecl .= $param;
    }

    /**
     * Callback called when swithing states:
     * UNION => UNION_TYPE; On condition: [notwhitespace]
    */
    public function startUnionType($param = null)
    {
        //echo "\nSTART TYPE: $param\n";
        $this->unions[] = [
            'type' => trim($param),
            'id' => ''
        ];
    }

    /**
     * Callback called when swithing states:
     * UNION_TYPE => UNION_TYPE; On condition: [notwhitespace]
    */
    public function pushUnionType($param = null)
    {
        //echo "\nPUSH TYPE: $param\n";
        $this->unions[count($this->unions)-1]['type'] .= $param;
    }

    /**
     * Callback called when swithing states:
     * UNION_TYPE => UNION_PREID; On condition: [whitespace]
    */
    public function endUnionType($param = null)
    {
        //echo "\nUNION TYPE:".$this->unions[count($this->unions)-1]['type']."\n";
    }

    /**
     * Callback called when swithing states:
     * UNION_STRUCT => UNION_PREID; On condition: string:}
    */
    public function endUnionStruct($param = null)
    {
        $this->pushUnionType($param);
        $this->endUnionType();
    }

    /**
     * Callback called when swithing states:
     * UNION_PREID => UNION_ID; On condition: [notwhitespace]
    */
    public function startUnionId($param = null)
    {
        $this->unions[count($this->unions)-1]['id'] .= $param;
    }

    /**
     * Callback called when swithing states:
     * UNION_ID => UNION_ID; On condition: [any]
    */
    public function pushUnionId($param = null)
    {
        $this->unions[count($this->unions)-1]['id'] .= $param;
    }

    /**
     * Callback called when swithing states:
     * UNION_ID => UNION; On condition: string:}
    */
    public function endUnionId($param = null)
    {
        //echo "\nUNION:\n";
        //print_r($this->unions[count($this->unions)-1]);
        //echo "\n";
    }

    /**
     * Callback called when swithing states:
     * UNION => START; On condition: string:}
    */
    public function endUnion($param = null)
    {
        //echo "\nUNIONS:\n";
        //print_r($this->unions);
    }

    /**
     * Callback called when swithing states:
     * START => OTHER_TOKEN; On condition: regex:/\%([a-zA-Z0-9-]+)/
    */
    public function startOtherToken($param = null)
    {
        $this->other_tokens[] = [
            'key' => trim($param),
            'value' => '',
        ];
    }

    /**
     * Callback called when swithing states:
     * OTHER_TOKEN => START; On condition: [newline]
    */
    public function endOtherToken($param = null)
    {
        $this->other_tokens[count($this->other_tokens)-1]['value'] =
            trim($this->other_tokens[count($this->other_tokens)-1]['value']);
        //print_R($this->other_tokens);die;
        //print_r($this->other_tokens[count($this->other_tokens)-1]);
    }

    /**
     * Callback called when swithing states:
     * OTHER_TOKEN => OTHER_TOKEN; On condition: [any]
    */
    public function pushOtherToken($param = null)
    {
        //echo 'push '.$param."\n";
        $this->other_tokens[count($this->other_tokens)-1]['value'] .= $param;
    }

    /**
     * Callback called when swithing states:
     * START => TOKEN; On condition: string:%token
    */
    public function startToken($param = null)
    {
        $this->tokens[] = $param;
    }
    /**
     * Callback called when swithing states:
     * TOKEN => TOKEN; On condition: [any]
    */
    public function pushToken($param = null)
    {
        $this->tokens[count($this->tokens)-1] .= trim($param);
    }
    /**
     * Callback called when swithing states:
     * TOKEN => START; On condition: [whitespace]
    */
    public function endToken($param = null)
    {
        //echo 'TOKEN: '.$this->tokens[count($this->tokens)-1]."\n";
    }


    /**
     * Callback called when swithing states:
     * TYPE_PRE => TYPE; On condition: string:<
    */
    public function startType($param = null)
    {
        $this->types[] = [
            'type' => '',
            'symbols' => [],
        ];
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
        $this->types[count($this->types)-1]['type'] .= $param;
    }

    /**
     * Callback called when swithing states:
     * TYPE_POST => TYPE_SYM; On condition: [notwhitespace]
    */
    public function startTypeSymbol($param = null)
    {
        $this->types[count($this->types)-1]['symbols'][] = $param;
    }

    /**
     * Callback called when swithing states:
     * TYPE_SYM => TYPE_SYM; On condition: regex:/([A-Za-z0-9_]+)/
    */
    public function pushTypeSymbol($param = null)
    {
        if (trim($param) == 'ngine_control_command') {
            $a = 1;
        }
        $this->types[count($this->types)-1]['symbols']
            [count($this->types[count($this->types)-1]['symbols'])-1]
            .= $param;
        //print_r($this->types);
    }

    /**
     * Callback called when swithing states:
     * TYPE_SYM => TYPE_POST; On condition: [whitespace]
    */
    public function endTypeSymbol($param = null)
    {
        //print_r($this->types);
    }

    /**
     * Callback called when swithing states:
     * TYPE_SYM => START; On condition: [any]
    */
    public function endTypeSymbolNoConsume($param = null)
    {
        return true;
    }

    /**
     * Callback called when swithing states:
     * TYPE_POST => TYPE_SYML; On condition: string:'
    */
    public function startTypeSymbolLiteral($param = null)
    {
        $this->types[count($this->types)-1]['literals'][] = '';
    }

    /**
     * Callback called when swithing states:
     * TYPE_SYML => TYPE_POST; On condition: string:'
    */
    public function endTypeSymbolLiteral($param = null)
    {
        //print_r($this->types);
    }

    /**
     * Callback called when swithing states:
     * TYPE_SYML => TYPE_SYML; On condition: [any]
    */
    public function pushTypeSymbolLiteral($param = null)
    {
        $this->types[count($this->types)-1]['literals']
            [count($this->types[count($this->types)-1]['literals'])-1]
            .= $param;
    }

    /**
     * Callback called when swithing states:
     * START => GRAM; On condition: string:%%
    */
    public function startGram($param = null)
    {

    }

    public function isGramRuleActionProcEnd($param = null)
    {
        if ($param == '{') {
            ++$this->openbraces;
        } elseif ($param == '}') {
            --$this->openbraces;
        }

        if ($this->openbraces == 0) {
            ++$this->input_i;
            return true;
        }

        //echo 'braces: '.$this->openbraces."\n";
        return false;
    }

    /**
     * Callback called when swithing states:
     * GRAM_RULE_ACTION_PROC => GRAM_RULE_ACTION_PROC_COMMENT; On condition: string:/*
     * GRAM_RULE_ACTION_PROC_COMMENT => GRAM_RULE_ACTION_PROC; On condition: string:* /
     * GRAM_RULE_ACTION_PROC_COMMENT => GRAM_RULE_ACTION_PROC_COMMENT; On condition: [any]
    */
    public function pushGramRuleAction($param = null)
    {
        $ci = count($this->components) - 1;
        $si = count($this->components[$ci]['symbols']) - 1;
        $this->components[$ci]['symbols'][$si]['action'] .= $param;
    }

    /**
     * Callback called when swithing states:
     * GRAM_RULE => GRAM_RULE_ACTION_PROC; On condition: string:{
     * GRAM_RULE_AND => GRAM_RULE_ACTION_PROC; On condition: string:{
     * GRAM_RULE_SYMBOL => GRAM_RULE_ACTION_PROC; On condition: string:{
    */
    public function startGramRuleAction($param = null)
    {
        $this->openbraces = 1;

        if (empty($this->components)) {
            $this->components = [[]];
        }

        $ci = count($this->components) - 1;
        if (empty($this->components[$ci]['symbols'])) {
            $this->components[$ci]['symbols'][] = [
                'id' => '',
                'action' => '',
            ];
        }
    }

    /**
     * Callback called when swithing states:
     * GRAM => GRAM_RULE; On condition: regex:/([a-zA-Z0-9_]+)\:/
    */
    public $rule = '';
    public $components = [];
    public function startGramRule($param = null)
    {
        //echo "\nSTART RULE $param\n\n";
        $param = strstr($param, ':', true);
        $this->rule = $param;
        $this->components = null;
    }

    /**
     * Callback called when swithing states:
     * GRAM_RULE => GRAM; On condition: string:;
     * GRAM_RULE_SYMBOL => GRAM; On condition: string:;
     * GRAM_RULE_ACTION => GRAM; On condition: string:;
    */
    public function endGramRule($param = null)
    {
        $this->rules[$this->rule] = [];
        $this->rules[$this->rule]['components'] = $this->components;

        //print_r($this->components);
        //echo "\nEND RULE ".$this->rule."\n\n";
    }

    /**
     * Callback called when swithing states:
     * GRAM_RULE => GRAM_RULE_SYMBOL; On condition: [notwhitespace]
     * GRAM_RULE_AND => GRAM_RULE_SYMBOL; On condition: [notwhitespace]
     * GRAM_RULE_ACTION => GRAM_RULE_SYMBOL; On condition: [notwhitespace]
    */
    public function startGramRuleSymbol($param = null)
    {
        if (empty($this->components)) {
            $this->components = [[]];
        }

        $ci = count($this->components) - 1;
        $this->components[$ci]['symbols'][] = [
            'id' => '',
            'action' => '',
        ];

        $this->pushGramRuleSymbol($param);
    }

    /**
     * Callback called when swithing states:
     * GRAM_RULE_SYMBOL => GRAM_RULE_SYMBOL; On condition: [notwhitespace]
    */
    public function pushGramRuleSymbol($param = null)
    {
        $ci = count($this->components) - 1;
        $si = count($this->components[$ci]['symbols']) - 1;
        $this->components[$ci]['symbols'][$si]['id'] .= $param;
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
     * GRAM_RULE => GRAM_RULE_AND; On condition: string:|
     * GRAM_RULE_SYMBOL => GRAM_RULE_AND; On condition: string:|
     * GRAM_RULE_ACTION => GRAM_RULE_AND; On condition: string:|
    */
    public function startGramRulePipe($param = null)
    {
        // add empty symbol with no action if first symbol is pipe
        // probably: /* empty */ | symbol
        if (empty($this->components)) {
            $this->components[] = [
                'symbols' => [
                    [
                        'id' => '',
                        'action' => '',
                    ],
                ],
            ];
        }

        $this->components[] = [];
    }

    /**
     * Callback called when swithing states:
     * GRAM => GRAM; On condition: [end]
    */
    public function end($param = null)
    {
        //print_r($this->rules);
    }
}
