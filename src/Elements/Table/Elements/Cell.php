<?php
namespace Kubexia\HtmlBuilder\Elements\Table\Elements;

use Kubexia\HtmlBuilder\Html;
use Kubexia\HtmlBuilder\Element;

class Cell extends Element{
    
    public $rowCallback;
    
    public function __construct($tag) {
        parent::__construct($tag);
    }
    
}