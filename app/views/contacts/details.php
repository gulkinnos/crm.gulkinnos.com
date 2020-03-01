<?php
/** @var View $this */
$this->setSiteTitle($this->contact->displayName()); ?>
<?php $this->start('body'); ?>

<div class="col-md-8 col-md-offset-2 well">
    <a href="<?= PROOT ?>contacts" class="btn btn-xs btn-default">Back</a>
    <h2 class="text-center"><?= $this->contact->displayName() ?></h2>
    <div class="row">
        <div class="col-md-6">
            <p><span class="font-weight-bold">Email:</span> <?= $this->contact->email ?></p>
            <p><span class="font-weight-bold">Cell Phone:</span> <?= $this->contact->cell_phone ?></p>
            <p><span class="font-weight-bold">Home Phone:</span> <?= $this->contact->home_phone ?></p>
            <p><span class="font-weight-bold">Work Phone:</span> <?= $this->contact->work_phone ?></p>
        </div>
        <div class="col-md-6">
			<?= $this->contact->displayAddressLabel() ?>
        </div>
    </div>
</div>
<?php $this->end(); ?>
