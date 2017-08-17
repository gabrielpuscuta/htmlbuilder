<?php
include 'vendor/autoload.php';

use Kubexia\HtmlBuilder\Html;

Html::template('tableOptionsButtons', function($item){
    return Html::element('div')->addClass('btn-group')->role('group')->text(function() use ($item){
        return 
            Html::link('/edit/'.$item['id'], Html::element('i')->addClass('fa fa-edit')->render())->addClass('btn btn-default btn-sm')->render()
            .Html::link('/delete/'.$item['id'], Html::element('i')->addClass('fa fa-times')->render())->addClass('btn btn-default btn-sm')->render();
    })->render();
});

Html::template('tableOptionsLinks', function($item){
    return Html::ul()
        ->addClass('list-inline')
        ->items(function($ul) use ($item){
            $ul->li()->text(Html::link('/edit/'.$item['id'], Html::element('i')->addClass('fa fa-edit')->render())->render());
            $ul->li()->text(Html::link('/delete/'.$item['id'], Html::element('i')->addClass('fa fa-times')->render())->render());
        })
        ->render();
});

Html::template('pagination', function(){
    return Html::element('nav')->text(function(){
        return Html::ul()
            ->addClass('pagination')
            ->items(function($ul){
                $ul->li()->addClass('disabled')->text(Html::link('#', Html::element('span')->text('&laquo;')->render())->render());
                for($i=1; $i<=5; $i++){
                    $li = $ul->li()->text(Html::link('#', $i)->render());
                    if($i === 1){
                        $li->addClass('active');
                        $li->text(Html::element('span')->text($i)->render());
                    }
                }
                $ul->li()->text(Html::link('#', Html::element('span')->text('&raquo;')->render())->render());
            })
            ->render();
    })->render();
});

Html::template('pager', function(){
    return Html::element('nav')->text(function(){
        return Html::ul()
            ->addClass('pager')
            ->items(function($ul){
                $ul->li()->addClass('disabled')->text(Html::link('#', 'Previous')->render());
                $ul->li()->text(Html::link('#', 'Next')->render());
            })
            ->render();
    })->render();
});

$results = [];
for($i = 0; $i<=10; $i++){
    $results[] = [
        'id' => $i,
        'name' => 'Name '.$i
    ];
}
$table = Html::table($results)
    ->addClass('table table-hover')
    ->columns(function($table){
        $table->column('id','Id');
        $table->column('name','Name');
        $table->column('nameCustom','Custom Name', function(){
            return function($item,$cell){
                if($item['id'] === 1){
                    $cell->addClass('text-success');
                }
                else{
                    $cell->addClass('text-danger');
                }
                return $cell->text($item['name'].' custom');
            };
        });
        
        $table->column('custom','Custom', function($item){
            return 'CUSTOM: '.$item['id'].'='.$item['name'];
        });
        
        $table->column('options', NULL, Html::getTemplate('tableOptionsButtons'))->addClass('text-right');
    })
    ->footer(function(){
        return 
            Html::runTemplate('pagination')
            .Html::runTemplate('pager');
    });
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
    <?php echo $table->render(); ?>
    </div>  
    
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</body>
</html>