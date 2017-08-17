<?php
namespace Kubexia\HtmlBuilder\Elements\Table\Elements;

use Kubexia\HtmlBuilder\Html;
use Kubexia\HtmlBuilder\Element;

class Column extends Element{
    
    public $cellCallback;
    
    public function __construct() {
        parent::__construct(NULL);
    }
    
}