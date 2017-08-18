<?php
namespace Kubexia\HtmlBuilder;

use Kubexia\HtmlBuilder\Html;

class Element{
    
    protected $tag = NULL;
    
    protected $attributes = [];
    
    protected $configs = [];
    
    protected $text = NULL;
    
    protected $content = NULL;
    
    protected $collections = [];
    
    protected $positioning = [];
    
    protected $callable = [];
    
    public function __construct($tag){
        $this->tag = $tag;
    }
    
    public function __set($name, $value) {
        return $this->addAttribute($name, $value);
    }
    
    public function __call($name, $arguments) {
        return $this->addAttribute($name, $arguments[0]);
    }
    
    public function setTag($value){
        $this->tag = $value;
        return $this;
    }
    
    public function setProperty($name, $value){
        $this->{$name} = $value;
        return $this;
    }
    
    public function id($name){
        $this->attributes['id'] = $name;
        return $this;
    }
    
    public function value($string = ''){
        $this->attribute('value', $string);
        return $this;
    }
    
    public function callback($callable){
        call_user_func_array($callable, [$this]);
        return $this;
    }
    
    public function setCallable($name, $callback){
        $this->callable[$name] = $callback;
        return $this;
    }
    
    public function getCallable($name){
        return isset($this->callable[$name]) ? $this->callable[$name] : NULL;
    }
    
    public function hasCallable($name){
        return isset($this->callable[$name]) ? TRUE : FALSE;
    }
    
    public function addClass($value = NULL){
        if(is_null($value) || strlen($value) === 0){
            return $this;
        }
        if (!isset($this->attributes['class']) || is_null($this->attributes['class'])) {
            $this->attributes['class'] = [];
        }
        
        if(in_array($value, $this->attributes['class'])){
            return $this;
        }
        
        $valuesArray = explode(' ', $value);
        $this->attributes['class'] = array_merge($this->attributes['class'], $valuesArray);
        return $this;
    }
    
    public function hasClass($value){
        if(!isset($this->attributes['class'])){
            return FALSE;
        }
        return in_array($value, $this->attributes['class']);
    }
    
    public function hasClassAttribute($name){
        if(is_null($this->getAttribute('class'))){
            return FALSE;
        }
        $array = $this->getAttribute('class');
        if(preg_match('#\*#', $name)){
            $regex = str_replace('*','(.*)',trim($name));
            foreach($array as $item){
                if(preg_match('#'.$regex.'#', trim($item))){
                    return TRUE;
                }
            }
            
            return FALSE;
        }
        else{
            return (in_array($name, $array) ? TRUE : FALSE);
        }
    }
    
    public function addStyle($name, $value){
        $this->attributes['style'][$name] = $value;
        return $this;
    }
    
    public function addAttribute($name, $value = NULL){
        $this->attributes[$this->slugify($name)] = $value;
        return $this;
    }
    
    public function getAttribute($name){
        return isset($this->attributes[$name]) ? $this->attributes[$name] : NULL;
    }
    
    public function removeAttribute($name){
        if(isset($this->attributes[$name])){
            unset($this->attributes[$name]);
        }
        
        return $this;
    }
    
    public function attribute($name, $value = NULL){
        return $this->addAttribute($name, $value);
    }
    
    public function attr($name, $value = NULL){
        return $this->addAttribute($name, $value);
    }
    
    public function attributes(array $array = []){
        foreach($array as $key => $value){
            $this->addAttribute($key, $value);
        }
        
        return $this;
    }
    
    public function addDataAttribute($name, $value = NULL){
        $this->attributes[$this->slugify('data-'.$name)] = $value;
        return $this;
    }
    
    public function dataAttribute($name, $value = NULL){
        return $this->addDataAttribute($name, $value);
    }
    
    public function addConfig($name, $value = NULL){
        $this->configs[$name] = $value;
        return $this;
    }
    
    public function config($name, $value = NULL){
        return $this->addConfig($name, $value);
    }
    
    public function getConfig($name){
        return (isset($this->configs[$name]) ? $this->configs[$name] : NULL);
    }
    
    public function text($value){
        $this->text = $this->getValue($value);
        return $this;
    }
    
    public function label($value){
        return $this->config('label', $this->getValue($value));
    }
    
    public function getAttributes(){
        return $this->attributes;
    }
    
    public function getText(){
        return $this->text;
    }
    
    public function getTag(){
        return $this->tag;
    }
    
    public function getAttributesString(){
        if(empty($this->attributes)){
            return '';
        }
        $array = [];
        foreach($this->getAttributes() as $key => $value){
            switch($key){
                case "class":
                    $value = (is_array($value) ? join(' ', $value) : $value);
                    break;
                
                case "style":
                    $newValue = [];
                    foreach($value as $sprop => $sval){
                        $newValue[] = $sprop.':'.$sval.';';
                    }
                    $value = join(' ', $newValue);
                    break;
            }
            $array[] = $key.'='.(is_array($value) ? "'".json_encode($value)."'" : '"'.$value.'"');
        }
        
        return ' '.join(' ', $array);
    }
    
    public function setContent($content){
        $this->content = $content;
        return $this;
    }
    
    public function getContentString(){
        if(!is_null($this->content)){
            return $this->content;
        }
        
        if(!empty($this->collections['children'])){
            $content = '';
            foreach($this->orderElements($this->collections['children']) as $element){
                $content .= $element->render();
            }
            
            return $content;
        }
        return $this->getText();
    }
    
    public function render(){
        $autoclose = ['img', 'br', 'hr', 'input', 'area', 'link', 'meta', 'param'];
        if(in_array($this->getTag(), $autoclose)){
            return '<'.$this->getTag().$this->getAttributesString().' />';
        }
        
        return '<'.$this->getTag().$this->getAttributesString().'>'.$this->getContentString().'</'.$this->getTag().'>';
    }
    
    public function renderLabel(){
        if(!$this->getConfig('label')){
            return '';
        }
        return $this->getLabel()->render();
    }
    
    public function getLabel(){
        if(is_null($this->getConfig('label'))){
            return '';
        }
        
        $label = Html::element('label');
        if($this->getAttribute('id')){
            $label->attribute('for', $this->getAttribute('id'));
        }
        if(Html::isBootstrap()){
            $label->addClass('control-label');
        }
        return $label->text($this->getConfig('label'));
    }
    
    public function child($tag, $name = NULL, $collection = 'children'){
        $child = ($tag instanceof \Closure ? call_user_func_array($tag, [$this]) : (is_object($tag) ? $tag : new Element($tag)));
        if(is_null($name)){
            $name = (isset($this->collections[$collection]) ? count($this->collections[$collection]) + 1 : 0);
        }
        $this->collections[$collection][$name] = $child;
        return $child;
    }
    
    public function required(){
        return $this;
    }
    
    protected function slugify($text, $replace = '-') {
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', $replace, $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, $replace);

        // remove duplicate -
        $text = preg_replace('~-+~', $replace, $text);

        // lowercase
        $text = strtolower($text);
        
        return strlen($text) === 0 ? NULL : $text;
    }
    
    protected function getValue($value){
        return ($value instanceof \Closure ? call_user_func_array($value, [$this]) : $value);
    }
    
    public function after($name){
        $this->positioning['after'] = $name;
        return $this;
    }
    
    public function before($name){
        $this->positioning['before'] = $name;
        return $this;
    }
    
    public function getPosition(){
        return empty($this->positioning) ? NULL : $this->positioning;
    }
    
    public function orderElements($elements){
        $positions = [];
        foreach($elements as $key => $item){
            if(!is_null($item->getPosition()) && in_array(array_keys($item->getPosition())[0],['before','after'])){
                $positions[$key] = $item;
                unset($elements[$key]);
            }
        }
        
        foreach($positions as $key => $item){
            $keys = array_keys($elements);
            $vals = array_values($elements);
            
            $position = $item->getPosition();
            if(isset($position['after'])){
                $insert = array_search($position['after'], $keys) + 1;
            }
            
            if(isset($position['before'])){
                $insert = array_search($position['before'], $keys);
            }
            
            $keys2 = array_splice($keys, $insert);
            $vals2 = array_splice($vals, $insert);

            $keys[] = $key;
            $vals[] = $item;

            $elements = array_merge(array_combine($keys, $vals), array_combine($keys2, $vals2));
        }
        
        return $elements;
    }
    
}
