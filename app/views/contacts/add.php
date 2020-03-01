<?php
//declaring datatype to get phpDocs
/** @var View $this */
?>
<?php $this->setSiteTitle("Add a contact"); ?>
<?php $this->start('body'); ?>
<div class="col-md-12 col-md-offset-2 well">
    <h2 class="text-center">Add a Contact</h2>
    <hr>
	<?php $this->partial('contacts', 'form'); ?>
</div>
<?php $this->end(); ?>
