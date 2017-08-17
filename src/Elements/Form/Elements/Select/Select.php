<?php
namespace Kubexia\HtmlBuilder\Elements\Form\Elements\Select;

use Kubexia\HtmlBuilder\Html;
use Kubexia\HtmlBuilder\Element;
use Kubexia\HtmlBuilder\Elements\Form\Elements\Select\Option;

class Select extends Element{
    
    protected $form;
    
    public $selected;
    
    public function __construct() {
        parent::__construct('select');
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
        if(!Html::isBootstrap()){
            return parent::render();
        }
        
        $this->addClass('form-control');
        if($this->form->hasClass('form-horizontal')){
            $string = Html::element('div')->addClass('col-'.$this->form->getGridType().'-'.$this->form->getGrid(1))->text(parent::render())->render();
            $label = ($this->getConfig('label') ? 
                $this->getLabel()->addClass('col-'.$this->form->getGridType().'-'.$this->form->getGrid(0))->render() : 
                Html::element('div')->addClass('col-'.$this->form->getGridType().'-'.$this->form->getGrid(0))->render()
            );
            
            $div = Html::element('div')->text($label.$string);
        }
        else{
            $div = Html::element('div')->text($this->renderLabel().parent::render());
        }
        
        if(!$this->getConfig('isGroup')){
            $div->addClass('form-group');
        }
        
        return $div->render();
    }
    
}
