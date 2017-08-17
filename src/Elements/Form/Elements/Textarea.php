<?php
namespace Kubexia\HtmlBuilder\Elements\Form\Elements;

use Kubexia\HtmlBuilder\Element;
use Kubexia\HtmlBuilder\Html;

class Textarea extends Element{
    
    protected $form;
    
    public function __construct() {
        parent::__construct('textarea');
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
