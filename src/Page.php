<?php
namespace Kubexia\HtmlBuilder;

use Kubexia\HtmlBuilder\Element;

class Page extends Element{
    
    static protected $data = [];
    
    public function __construct() {
        parent::__construct('html');
        
        $this->attribute('lang', 'en');
    }
    
    public function hasData($name){
        return (isset(static::$data[$name]) ? TRUE : FALSE);
    }
    
    public function getData($name){
        return (isset(static::$data[$name]) ? static::$data[$name] : NULL);
    }
    
    public function content($callback, $args = []){
        static::$data['content'] = Html::runCallback($callback, $args);
        return $this;
    }
    
    public function js($callback){
        if(!isset(static::$data['js'])){
            static::$data['js'] = [];
        }
        
        $js = Html::runCallback($callback);
        foreach($js as $attributes){
            static::$data['js'][] = $attributes;
        }
        return $this;
    }
    
    public function css($callback){
        if(!isset(static::$data['css'])){
            static::$data['css'] = [];
        }
        
        $css = Html::runCallback($callback);
        foreach($css as $attributes){
            static::$data['css'][] = $attributes;
        }
        return $this;
    }
    
    public function plugin($name, $callback){
        if(!isset(static::$data['plugins'])){
            static::$data['plugins'] = [];
        }
        static::$data['plugins'][$name] = $callback;
        return $this;
    }
    
    public function head($callback){
        if(!isset(static::$data['head'])){
            static::$data['head'] = [];
        }
        
        static::$data['head'] = $callback;
        
        return $this;
    }
    
    static public function registerPlugin($name, $callback){
        if(!isset(static::$data['plugins'])){
            static::$data['plugins'] = [];
        }
        static::$data['plugins'][$name] = $callback;
    }
    
    public function usePlugins(array $array = []){
        static::$data['usePlugins'] = $array;
        return $this;
    }
    
    protected function getHead(){
        return Html::element('head')->text(function(){
            $content = [
                Html::element('meta')->charset('utf-8')->render(),
                Html::element('meta')->attributes(['name' => 'viewport','content' => 'width=device-width, initial-scale=1, shrink-to-fit=no'])->render(),
            ];
            
            if($this->hasData('head')){
                $head = (is_array($this->getData('head')) ? $this->getData('head') : Html::runCallback($this->getData('head')));
                $content = array_merge($content, $head);
            }
            
            if($this->hasData('css')){
                foreach($this->getData('css') as $attributes){
                    $content[] = Html::element('link')->rel('stylesheet')->attributes($attributes)->render();
                }
            }
            
            return join('',$content);
        })->render();
    }
    
    protected function getBody(){
        return Html::element('body')->text(function(){
            $content = [];
            if($this->hasData('content')){
                $content[] = $this->getData('content');
            }
            
            if($this->hasData('js')){
                foreach($this->getData('js') as $attributes){
                    $content[] = Html::element('script')->type('text/javascript')->attributes($attributes)->render();
                }
            }

            return join('',$content);
        })->render();
    }
    
    protected function appendPlugins($usePlugins){
        $plugins = $this->getData('plugins');
        foreach($usePlugins as $item){
            if(isset($plugins[$item])){
                $plugin = Html::runCallback($plugins[$item]);
                
                if(isset($plugin['dependencies'])){
                    $this->appendPlugins($plugin['dependencies']);
                }
                
                if(isset($plugin['css'])){
                    $this->css(function() use ($plugin){
                        return $plugin['css'];
                    });
                }

                if(isset($plugin['js'])){
                    $this->js(function() use ($plugin){
                        return $plugin['js'];
                    });
                }
            }
        }
    }
    
    public function render(){
        $this->appendPlugins($this->getData('usePlugins'));
        return '<!DOCTYPE html><'.$this->getTag().$this->getAttributesString().'>'.$this->getHead().$this->getBody().'</'.$this->getTag().'>';
    }
    
}