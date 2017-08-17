<?php
namespace Kubexia\HtmlBuilder\Elements\Form\Elements;

use Kubexia\HtmlBuilder\Element;
use Kubexia\HtmlBuilder\Html;

class Button extends Element{
    
    protected $icon = NULL;
    
    public function __construct() {
        parent::__construct('button');
    }
    
    public function icon($collection, $name){
        $this->icon = ['collection' => $collection, 'name' => $name];
        return $this;
    }
    
    public function getIconString(){
        if(is_null($this->icon)){
            return '';
        }
        switch($this->icon['collection']){
            //FontAwesome
            case "fa":
                $string = Html::element('i')->addClass('fa fa-'.$this->icon['name'].' fa-fw')->render();
                break;
            
            //Bootstrap Glyphicons
            case "bs":
                $string = Html::element('span')->addClass('glyphicon glyphicon-'.$this->icon['name'])->render();
                break;
                
        }
        
        return (isset($string) ? $string.' ' : '');
    }
    
    public function render(){
        if(!Html::isBootstrap()){
            return parent::render();
        }
        $this->addClass('btn');
        if(!$this->hasClassAttribute('btn-*')){
            $this->addClass('btn-default');
        }
        
        $this->text = $this->getIconString().$this->text;
        
        return parent::render();
    }
    
}
