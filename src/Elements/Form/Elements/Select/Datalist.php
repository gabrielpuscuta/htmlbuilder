<?php
namespace Kubexia\HtmlBuilder\Elements\Form\Elements\Select;

use Kubexia\HtmlBuilder\Html;
use Kubexia\HtmlBuilder\Element;
use Kubexia\HtmlBuilder\Elements\Form\Elements\Select\Option;

class Datalist extends Element{
    
    protected $form;
    
    public $selected;
    
    public function __construct() {
        parent::__construct('datalist');
    }
    
    public function options($value){
        if($value instanceof \Closure){
            $value = call_user_func_array($value,[$this]);
        }
        
        if(empty($value)){
            return $this;
        }
        
        foreach($value as $item){
            $option = $this->option();
            foreach($item as $k => $v){
                if($k === 'text'){
                    $option->text($v);
                }
                else{
                    $option->attribute($k, $v);
                }
            }
        }
        
        return $this;
    }
    
    public function option(){
        return $this->child(new Option($this));
    }
    
    public function selected($value){
        $this->selected = $value;
        return $this;
    }
    
    public function render(){
        $input = new Element('input');
        if(Html::isBootstrap()){
            $input->addClass('form-control');
        }
        $string = $input->attribute('list', $this->getAttribute('name'))->render();
        $this->attribute('id', $this->getAttribute('name'));
        $this->removeAttribute('name');
        $string .= parent::render();
        if(!Html::isBootstrap()){
            return $string;
        }
        
        if($this->form->hasClass('form-horizontal')){
            $string = Html::element('div')->addClass('col-'.$this->form->getGridType().'-'.$this->form->getGrid(1))->text($string)->render();
            $label = $this->getLabel()->addClass('col-'.$this->form->getGridType().'-'.$this->form->getGrid(0))->render();
            return Html::element('div')->addClass('form-group')->text($label.$string)->render();
        }
        else{
            return Html::element('div')->addClass('form-group')->text($this->renderLabel().$string)->render();
        }
    }
}
