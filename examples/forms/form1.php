<?php
include 'vendor/autoload.php';

use Kubexia\HtmlBuilder\Html;

Html::defaults('form', function($form){
    if(!in_array($form->getAttribute('method'), ['POST','GET'])){
        $form->input('hidden','_method')->value($form->getAttribute('method'));
        $form->attribute('method','POST');
    }
    
    $form->input('hidden','_token')->value('csrf token');
});

Html::template('formButtons', function($form){
    $form->button('save','Save')->addClass('btn-primary')->icon('fa','save');
    $form->button('delete','Delete')->attribute('type','button')->addClass('btn-danger')->icon('fa','times');
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
    <?php
    echo Html::element('div')->addClass('well')->text(function(){
        $form = Html::form('POST','#')
        ->addClass('form-horizontal')
        ->elements(function($form){
            $form->input('hidden','referrer')->value('https://google.com');
            
            $form->group('name',function($group){
                $group->input('text','first_name')->id('firstname')->label('First name')->attribute('placeholder','enter first name');
                $group->input('text','last_name')->label('Last name')->attribute('placeholder','enter last name');

                $group->input('text','middle_name')->callback(function($input){
                    $input->placeholder('enter middle name');
                    $input->label('Middle name');
                })->before('last_name');
                return $group;
            })->label('Name');

            $form->group('asl',function($group){
                $group->input('text','age')->placeholder('enter age')->label('Age');
                $group->input('text','sex')->placeholder('enter sex')->label('Sex');
                $group->input('text','location')->placeholder('enter location')->label('Location');
                $group->select('category10')->options([
                    ['value' => '1', 'text' => 'Category 1'],
                    ['value' => '2', 'text' => 'Category 2']
                ])->label('Category 1')->selected('2');
                return $group;
            })->label('ASL')->setGrid(['type' => 'md','split' => 6]);

            $form->select('category1')->options([
                ['value' => '1', 'text' => 'Category 1'],
                ['value' => '2', 'text' => 'Category 2']
            ])->label('Category 1')->selected('2');

            $form->select('category2')->options(function(){
                return [
                    ['value' => '1', 'text' => 'Category 1'],
                    ['value' => '2', 'text' => 'Category 2']
                ];
            })->label('Category 2')->selected('2');

            $form->select('category3')->options(function($select){
                $select->option()->text('Category 1')->attribute('value', '1');
                $select->option()->text('Category 2')->attribute('value', '2');
            })->label('Category 3');

    //        $form->datalist('category4')->options(function($select){
    //            for($i=1; $i<=10; $i++){
    //                $select->option()->attribute('value', 'Category '.$i);
    //            }
    //        })->label('Category 4');

            $form->textarea('message')->placeholder('enter your message')->rows('5')->label('Message');
        })
        ->buttons(Html::getTemplate('formButtons'));

        $title = Html::element('h3')->text('Contact')->addClass('text-center')->render().Html::element('hr')->render();
        return $title.$form->render();
    })->render();
    
    ?>
      </div>  
        

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</body>
</html>