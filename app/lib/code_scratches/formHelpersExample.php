<?php $this->start('head') ?>
<?php $this->end() ?>
<?php $this->start('body') ?>
Welcome

<?= FormHelpers::inputBlock('text','Favorite Color:', 'favorite_color', '', ['class'=>'form-control'], ['class'=>'form-group'])?>
<?= FormHelpers::submitTag('Save',['class'=>'btn btn-primary'])?>
<?= FormHelpers::submitBlock('Save',['class'=>'btn btn-primary'], ['class'=> 'text-right'])?>
<?php $this->end() ?>
<?php //$this->setSiteTitle('Home')?>
<!---->
