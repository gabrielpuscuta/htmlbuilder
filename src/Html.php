<?php
namespace Kubexia\HtmlBuilder;

use Kubexia\HtmlBuilder\Element;
use Kubexia\HtmlBuilder\Page;

use Kubexia\HtmlBuilder\Elements\Lists;
use Kubexia\HtmlBuilder\Elements\Form\Form;
use Kubexia\HtmlBuilder\Elements\Table\Table;

class Html extends Element{
    
    static protected $bootstrap = TRUE;
    
    static protected $defaults = [];
    
    static protected $templates = [];
    
    public function __construct($tag){
        parent::__construct($tag);
    }
    
    static public function setBootstrap($bool){
        static::$bootstrap = $bool;
    }
    
    static public function isBootstrap(){
        return static::$bootstrap;
    }
    
    static public function defaults($method, $callback){
        static::$defaults[$method] = $callback;
    }
    
    static public function getDefault($method){
        return isset(static::$defaults[$method]) ? static::$defaults[$method] : NULL;
    }
    
    static public function runDefault($method, array $args = []){
        if(!static::hasDefault($method)){
            return NULL;
        }
        
        return call_user_func_array(static::getDefault($method), $args);
    }
    
    static public function hasDefault($method){
        return isset(static::$defaults[$method]) ? TRUE : FALSE;
    }
    
    static public function template($name, $callback){
        static::$templates[$name] = $callback;
    }
    
    static public function getTemplate($name){
        return isset(static::$templates[$name]) ? static::$templates[$name] : NULL;
    }
    
    static public function runTemplate($name, array $args = []){
        if(!static::hasTemplate($name)){
            return NULL;
        }
        
        return call_user_func_array(static::getTemplate($name), $args);
    }
    
    static public function runCallback($callback, array $args = []){
        return call_user_func_array($callback, $args);
    }
    
    static public function hasTemplate($name){
        return isset(static::$templates[$name]) ? TRUE : FALSE;
    }
    
    static public function generateElementsFromArray($tag, $commonAttributes, $data, $toret = 'string', $render = TRUE){
        if($data instanceof \Closure){
            $data = call_user_func($data);
        }
        $content = [];
        foreach($data as $attributes){
            if(isset($attributes['text'])){
                $text = $attributes['text'];
                unset($attributes['text']);
            }
            $el = Html::element($tag)->attributes(array_merge($attributes,$commonAttributes));
            if(isset($text)){
                $el->text($text);
            }
            if($render){
                $el = $el->render();
            }
            $content[] = $el;
        }
        
        if($toret === 'array'){
            return $content;
        }
        
        return join('', $content);
    }
    
    static public function element($tag){
        return new Html($tag);
    }
    
    static public function paragraph($content){
        return Html::element('p')->text($content);
    }
    
    static public function link($url = '', $anchor = ''){
        return Html::element('a')->addAttribute('href', $url)->text($anchor);
    }
    
    static public function image($url = ''){
        return Html::element('img')->addAttribute('src', $url);
    }
    
    static public function ul(){
        return Lists::ul();
    }
    
    static public function ol(){
        return Lists::ol();
    }
    
    static public function button(){
        return Html::element('button');
    }
    
    static public function form($method = '', $action = ''){
        return new Form($method, $action);
    }
    
    static public function table($results = []){
        $table = new Table();
        $table->setProperty('results', $results);
        return $table;
    }
    
    static public function page(){
        return new Page();
    }
    
}
