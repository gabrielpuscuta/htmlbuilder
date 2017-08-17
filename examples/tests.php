<?php
include 'vendor/autoload.php';

use Kubexia\HtmlBuilder\Html;

Html::template('dropdown', function($title){
    return Html::element('div')->addClass('dropdown')->text(function() use ($title){
        return 
            Html::button()->addClass('btn btn-default dropdown-toggle')->attributes(['type' => 'button','data-toggle' => 'dropdown','aria-haspopup' => 'true','aria-expanded' => 'true'])
                ->text(function() use ($title){
                    return $title.' '.Html::element('span')->addClass('caret')->render();
                })->render()
            .Html::ul()->addClass('dropdown-menu')->items(function($ul){
                $ul->li()->text(Html::link('#', 'Action')->render());
                $ul->li()->text(Html::link('#', 'Another Action')->render());
                $ul->li()->text(Html::link('#', 'Something else here')->render());
                $ul->li()->role('separator')->addClass('divider');
                $ul->li()->text(Html::link('#', 'Separated link')->render());
                $ul->li()->role('separator')->addClass('divider');
                $ul->li()->addClass('dropdown-header')->text('Dropdown header');
                $ul->li()->text(Html::link('#', 'Another Action')->render());
                $ul->li()->text(Html::link('#', 'Something else here')->render());
            })->render();
    })->render();
});

Html::template('dopup', function($title){
    return Html::element('div')->addClass('dropup')->text(function() use ($title){
        return 
            Html::button()->addClass('btn btn-default dropdown-toggle')->attributes(['type' => 'button','data-toggle' => 'dropdown','aria-haspopup' => 'true','aria-expanded' => 'true'])
                ->text(function() use ($title){
                    return $title.' '.Html::element('span')->addClass('caret')->render();
                })->render()
            .Html::ul()->addClass('dropdown-menu')->items(function($ul){
                $ul->li()->text(Html::link('#', 'Action')->render());
                $ul->li()->text(Html::link('#', 'Another Action')->render());
                $ul->li()->text(Html::link('#', 'Something else here')->render());
                $ul->li()->role('separator')->addClass('divider');
                $ul->li()->text(Html::link('#', 'Separated link')->render());
            })->render();
    })->render();
});

Html::template('btnGroup', function(){
    return Html::element('div')->addClass('btn-group')->role('group')->text(function(){
        //if same element format
        return Html::generateElementsFromArray('button', ['class' => 'btn btn-default','type' => 'button'], function(){
            return [
                ['text' => 'Left'],['text' => 'Middle'],['text' => 'Right']
            ];
        });
        /*
         * else:
         * 
        $buttons = [
            Html::button()->addClass('btn btn-default')->type('button')->text('Left')->render(),
            Html::button()->addClass('btn btn-primary')->type('button')->text('Middle')->render(),
            Html::button()->addClass('btn btn-danger')->type('button')->text('Right')->render(),
        ];
        return join('', $buttons);
         * 
         */
    })->render();
});


use Kubexia\HtmlBuilder\Page;

Page::registerPlugin('bootstrap', function(){
    return [
        'css' => [
            ['href' => 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css','integrity' => 'sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u','crossorigin' => 'anonymous']
        ],
        'js' => [
            ['src' => 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js','integrity' => 'sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa','crossorigin' => 'anonymous']
        ],
        'dependencies' => ['jquery','fontawesome']
    ];
});

Page::registerPlugin('jquery', function(){
    return [
        'js' => [
            ['src' => 'https://code.jquery.com/jquery-3.2.1.slim.min.js','integrity' => 'sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN','crossorigin' => 'anonymous'],
        ],
    ];
});

Page::registerPlugin('fontawesome', function(){
    return [
        'css' => [
            ['href' => 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css']
        ]
    ];
});

echo Html::page()
    ->content(function(){
        return Html::element('div')->addClass('container')->addStyle('margin-top','80px')->text(function(){
            return 
                Html::runTemplate('dropdown',['title' => 'Dropdown'])
                .Html::runTemplate('dopup',['title' => 'Dopup'])
                .Html::runTemplate('btnGroup');
        })->render();
    })
    ->usePlugins(['bootstrap'])
    ->render();

exit;
$html = Html::runTemplate('page', [function(){
    return [
        'head' => [
            'css' => [
                ['href' => 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css','integrity' => 'sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u','crossorigin' => 'anonymous'],
                ['href' => 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css']
            ],
        ],
        'body' => [
            'js' => [
                ['src' => 'https://code.jquery.com/jquery-3.2.1.slim.min.js','integrity' => 'sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN','crossorigin' => 'anonymous'],
                ['src' => 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js','integrity' => 'sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa','crossorigin' => 'anonymous']
            ],
            'content' => function(){
                return Html::element('div')->addClass('container')->addStyle('margin-top','80px')->text(function(){
                    return 
                        Html::runTemplate('dropdown',['title' => 'Dropdown'])
                        .Html::runTemplate('dopup',['title' => 'Dopup'])
                        .Html::runTemplate('btnGroup');
                })->render();
            }
        ]
    ];
}]);

echo $html;

//$dom = new \DOMDocument();
//$dom->loadXML($html);
//
//$dom->formatOutput = true;
//echo $dom->saveHTML();

exit;
?>

<!DOCTYPE html>
<html lang="en">
    <head>
      <!-- Required meta tags -->
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <!-- Bootstrap CSS -->
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    </head>
    <body>
    <div class="container" style="margin-top: 80px;">
        <?php echo Html::runTemplate('dropdown',['title' => 'Dropdown']); ?>
        <br>
        <?php echo Html::runTemplate('dopup',['title' => 'Dopup']); ?>
        <br>
        <?php echo Html::runTemplate('btnGroup'); ?>
    </div>  
    
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</body>
</html>