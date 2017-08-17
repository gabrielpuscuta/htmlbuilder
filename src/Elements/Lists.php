<?php
namespace Kubexia\HtmlBuilder\Elements;

use Kubexia\HtmlBuilder\Element;

class Lists extends Element{
    
    protected $markers = ['none','disc','circle','square'];
    
    protected $types = ['1','A','a','I','i'];
    
    public function __construct($tag) {
        parent::__construct($tag);
    }
    
    static public function ul(){
        return new static('ul');
    }
    
    static public function ol(){
        return new static('ol');
    }
    
    public function marker($value){
        if($this->tag !== 'ul' || !in_array($value, $this->markers)){
            return $this;
        }
        
        return $this->addStyle('list-style-type', $value);
    }
    
    public function type($value){
        if($this->tag !== 'ol' || !in_array($value, $this->types)){
            return $this;
        }
        
        return $this->addAttribute('type', $value);
    }
    
    public function numberedWith($name){
        if($this->tag !== 'ol'){
            return $this;
        }
        
        switch($name){
            case "numbers":
                return $this->type('1');
                
            case "uppercaseLetters":
                return $this->type('A');
                
            case "lowercaseLetters":
                return $this->type('a');
                
            case "uppercaseRomanNumbers":
                return $this->type('I');
                
            case "lowercaseRomanNumbers":
                return $this->type('i');
        }
        
        return $this;
    }
    
    public function items($callable){
        call_user_func_array($callable,[$this]);
        return $this;
    }
    
    public function li(){
        return $this->child('li');
    }
    
}
