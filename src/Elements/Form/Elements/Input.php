<?php
namespace Kubexia\HtmlBuilder\Elements\Form\Elements;

use Kubexia\HtmlBuilder\Element;
use Kubexia\HtmlBuilder\Html;

class Input extends Element{
    
    protected $form;
    
    public function __construct() {
        parent::__construct('input');
    }
    
    public function render(){
        if(!Html::isBootstrap() || $this->getAttribute('type') === 'hidden'){
            return parent::render();
        }
        
        $this->addClass('form-control');
        $input = parent::render();
        
        if($this->form->hasClass('form-horizontal')){
            $string = Html::element('div')->addClass('col-'.$this->form->getGridType().'-'.$this->form->getGrid(1))->text($input)->render();
            $label = ($this->getConfig('label') ? 
                $this->getLabel()->addClass('col-'.$this->form->getGridType().'-'.$this->form->getGrid(0))->render() : 
                Html::element('div')->addClass('col-'.$this->form->getGridType().'-'.$this->form->getGrid(0))->render()
            );
            
            $div = Html::element('div')->text($label.$string);
            if(!$this->getConfig('isGroup')){
                $div->addClass('form-group');
            }
            return $div->render();
        }
        else{
            $div = Html::element('div')->text($this->renderLabel().$input);
            if(!$this->getConfig('isGroup')){
                $div->addClass('form-group');
            }
            return $div->render();
        }
    }
}
