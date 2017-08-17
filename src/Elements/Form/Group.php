<?php
namespace Kubexia\HtmlBuilder\Elements\Form;

use Kubexia\HtmlBuilder\Element;
use Kubexia\HtmlBuilder\Html;
use Kubexia\HtmlBuilder\Elements\Form\Form;

class Group extends Element{
    
    protected $form;
    
    protected $group;
    
    protected $grid = [
        'type' => 'md',
        'split' => 'default'
    ];
    
    public function __construct() {
        parent::__construct('div');
    }
    
    public function setGrid($value){
        $this->grid = $value;
        return $this;
    }
    
    protected function renderGroup($form){
        if(!empty($form->collections['children'])){
            $content = '';
            $children = $form->orderElements($form->collections['children']);
            $slice = ($this->grid['split'] === 'default' ? (12/count($children)) : $this->grid['split']);
            
            foreach($children as $element){
                $element->config('isGroup',true);
                $content .= Html::element('div')->addClass('col-'.$this->grid['type'].'-'.$slice)->text($element->render())->render();
            }
            
            $string = Html::element('div')->addClass('row')->text($content)->render();
            
            if($this->form->hasClass('form-horizontal')){
                $label = ($this->getConfig('label') ? 
                    $this->getLabel()->addClass('col-'.$this->form->getGridType().'-'.$this->form->getGrid(0))->render() : 
                    Html::element('div')->addClass('col-'.$this->form->getGridType().'-'.$this->form->getGrid(0))->render()
                );
                $string = Html::element('div')->addClass('col-'.$this->form->getGridType().'-'.$this->form->getGrid(1))->text($string)->render();
                $string = $label.$string;
            }
            
            return $string;
        }
        return $form->getText();
    }
    
    public function render(){
        $form = new Form();
        $form->config('self_render', FALSE);
        $form->config('run_defaults', FALSE);
        $group = call_user_func_array($this->group, [$form]);
        
        $form->setContent($this->renderGroup($form));
        
        $this->addClass('form-group');
        $this->text($group->render());
        $string = parent::render();
        
        return $string;
    }
}
