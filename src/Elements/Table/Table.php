<?php
namespace Kubexia\HtmlBuilder\Elements\Table;

use Kubexia\HtmlBuilder\Html;
use Kubexia\HtmlBuilder\Element;
use Kubexia\HtmlBuilder\Elements\Table\Elements\Column;
use Kubexia\HtmlBuilder\Elements\Table\Elements\Cell;

class Table extends Element{
    
    protected $results;
    
    protected $footer;
    
    public function __construct() {
        parent::__construct('table');
    }
    
    public function columns($callback){
        call_user_func_array($callback, [$this]);
        return $this;
    }
    
    public function column($name, $text, $cellCallback = NULL){
        return $this->child(new Column(), $name, 'columns')
            ->text($text)
            ->setProperty('cellCallback', $cellCallback)
            ;
    }
    
    public function footer($callback){
        $this->footer = Html::element('tfoot')->text(function() use ($callback){
            return Html::element('tr')->text(function() use ($callback){
                return Html::element('td')->attribute('colspan', '100%')->text(Html::runCallback($callback,[$this]))->render();
            })->render();
        });
        
        return $this;
    }
    
    protected function getHead(){
        return Html::element('thead')->text(function(){
            $content = '';
            foreach($this->collections['columns'] as $element){
                $content .= $element->setTag('th')->render();
            }
            return Html::element('tr')->text($content)->render();
        })->render();
    }
    
    protected function getBody(){
        return Html::element('tbody')->text(function(){
            if(empty($this->results)){
                return Html::element('tr')->text(function(){
                    return Html::element('td')->attribute('colspan', '100%')->text('no results')->render();
                })->render();
            }
            $content = '';
            foreach($this->results as $item){
                $td = '';
                foreach($this->collections['columns'] as $col => $element){
                    $td .= $this->getCell($col,$item, $element);
                }
                $content .= Html::element('tr')->text($td)->render();
            }
            
            return $content;
        })->render();
    }
    
    protected function getFooter(){
        return $this->footer->render();
    }
    
    protected function getCell($col,$item, $element){
        if($element->cellCallback instanceof \Closure){
            $text = call_user_func_array($element->cellCallback, [$item, $element]);
            
            if($text instanceof \Closure){
                $cell = call_user_func_array($text,[$item, new Cell('td')]);
                return $cell->render();
            }
        }
        return $element->setTag('td')->text($this->getCellValue($col, $item, $element))->render();
    }
    
    protected function getCellValue($col,$item, $element){
        if($element->cellCallback instanceof \Closure){
            $text = call_user_func_array($element->cellCallback, [$item, $element]);
        }
        else{
            $text = NULL;
            if(is_array($item) && isset($item[$col])){
                $text = $item[$col];
            }
            
            if(is_object($item) && isset($item->{$col})){
                $text = $item->{$col};
            }
        }
        
        return (is_null($text) ? Html::element('i')->text(function(){
            return Html::element('small')->text('not defined')->addClass('text-muted')->render();
        })->render() : $text);
    }
    
    public function getContentString(){
        return $this->getHead().$this->getBody().$this->getFooter();
    }
    
    public function render(){
        return '<'.$this->getTag().$this->getAttributesString().'>'.$this->getContentString().'</'.$this->getTag().'>';
    }
    
}