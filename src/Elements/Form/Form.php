<?php
namespace Kubexia\HtmlBuilder\Elements\Form;

use Kubexia\HtmlBuilder\Html;
use Kubexia\HtmlBuilder\Element;

use Kubexia\HtmlBuilder\Elements\Form\Group;
use Kubexia\HtmlBuilder\Elements\Form\Elements\Select\Select;
use Kubexia\HtmlBuilder\Elements\Form\Elements\Select\Datalist;
use Kubexia\HtmlBuilder\Elements\Form\Elements\Input;
use Kubexia\HtmlBuilder\Elements\Form\Elements\Textarea;
use Kubexia\HtmlBuilder\Elements\Form\Elements\Button;

class Form extends Element{
    
    protected $grid = ['type' => 'md','split' => '2:10'];
    
    protected $columns = 1;
    
    public function __construct($method = '', $action = '') {
        parent::__construct('form');
        
        $this->attribute('method', $method);
        $this->attribute('action', $action);
        
        $this->config('self_render', TRUE);
        $this->config('run_defaults', TRUE);
    }
    
    public function input($type = 'text',$name = NULL, $value = NULL){
        return $this->child(new Input(), $name)
            ->setProperty('form', $this)
            ->attribute('type', $type)
            ->attribute('name', $name)
            ->attribute('value', $value);
    }
    
    public function select($name){
        return $this->child(new Select(), $name)
            ->setProperty('form', $this)
            ->attribute('name', $name);
    }
    
    public function datalist($name){
        return $this->child(new Datalist(), $name)
            ->setProperty('form', $this)
            ->attribute('name', $name);
    }
    
    public function textarea($name, $value = ''){
        return $this->child(new Textarea(), $name)
            ->setProperty('form', $this)
            ->attribute('name', $name)
            ->text($value);
    }
    
    public function button($name = '', $text = ''){
        return $this->child(new Button(), $name, 'buttons')
            ->text($text);
    }
    
    public function group($name, $callback){
        return $this->child(new Group(), $name)
            ->setProperty('group', $callback)
            ->setProperty('form', $this);
    }
    
    public function elements($callable){
        call_user_func_array($callable,[$this]);
        return $this;
    }
    
    public function buttons($callable, $name = NULL){
        call_user_func_array($callable,[$this]);
        return $this;
    }
    
    public function getButtonsString(){
        if(!empty($this->collections['buttons'])){
            if(Html::isBootstrap()){
                $content = Html::ul()->addClass('form-buttons list-unstyled list-inline')->items(function($ul){
                    foreach($this->orderElements($this->collections['buttons']) as $element){
                        $ul->li()->text($element->render());
                    }
                })->render();
                
                if($this->hasClass('form-horizontal')){
                    $content = Html::element('div')->addClass('col-'.$this->getGridType().'-offset-'.$this->getGrid(0).' col-'.$this->getGridType().'-'.$this->getGrid(1))->text($content)->render();
                    return Html::element('div')->addClass('form-group')->text($content)->render();
                }
                else{
                    return $content;
                }
            }
            else{
                $content = '';
                foreach($this->orderElements($this->collections['buttons']) as $element){
                    $content .= $element->render();
                }

                return $content;
            }
        }
        return $this->getText();
    }
    
    public function setGrid($value){
        $this->grid = $value;
        return $this;
    }
    
    public function getGrid($key){
        $array = explode(':', $this->grid['split']);
        return $array[$key];
    }
    
    public function getGridType(){
        return $this->grid['type'];
    }
    
    public function columns($value){
        $this->columns = $value;
        return $this;
    }
    
    public function render(){
        if($this->getConfig('run_defaults') === TRUE){
            if(Html::hasDefault('form')){
                call_user_func_array(Html::getDefault('form'), [$this]);
            }
        }
        
        if($this->columns > 1){
            $this->setContent($this->splitContentInColumns());
        }
        
        if($this->getConfig('self_render') === TRUE){
            $this->setContent($this->getContentString().$this->getButtonsString());
            return parent::render();
        }
        else{
            return $this->getContentString();
        }
    }
    
    protected function splitContentInColumns(){
        return Html::element('div')->addClass('row')->text(function(){
            $total = count($this->collections['children']);
            $max = $total/$this->columns;
            
            $columns = '';
            for($i=1; $i<=$this->columns; $i++){
                $columns .= Html::element('div')->addClass('col-md-'.(12/$this->columns))->text(function() use ($max){
                    $elements = '';
                    $j = 1;
                    foreach($this->collections['children'] as $key => $el){
                        $elements .= $el->render();
                        unset($this->collections['children'][$key]);
                        if($max === $j){
                            break;
                        }
                        $j++;
                    }
                    
                    return $elements;
                })->render();
            }
            return $columns;
        })->render();
        
    }
    
}
