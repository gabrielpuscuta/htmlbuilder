<?php
namespace Kubexia\HtmlBuilder\Elements\Form\Elements\Select;

use Kubexia\HtmlBuilder\Element;

class Option extends Element{
    
    protected $select;
    
    public function __construct($select) {
        parent::__construct('option');
        
        $this->select = $select;
    }
    
    public function render(){
        if($this->select->selected === $this->getAttribute('value')){
            $this->attribute('selected', true);
        }
        
        if($this->select->tag === 'datalist'){
            return '<'.$this->getTag().$this->getAttributesString().' />';
        }
        else{
            return parent::render();
        }
    }
    
}
