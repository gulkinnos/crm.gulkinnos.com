<?php
//declaring datatype to get phpDocs
/** @var View $this */
?>
<?php $this->start('body'); ?>
<h2 class="text-center">My contacts</h2>
<table class="table-striped table-condensed table-bordered">
    <thead>
    <th>Name</th>
    <th>Email</th>
    <th>Cell phone</th>
    <th>Home phone</th>
    <th>Work phone</th>
    <th>TEMP_COLUMN</th>
    </thead>
    <tbody>
	<?php foreach ($this->contacts as $contact): ?>
        <tr>
            <td><?= $contact->displayName() ?></td>
            <td><?= $contact->email ?></td>
            <td><?= $contact->cell_phone ?></td>
            <td><?= $contact->home_phone ?></td>
            <td><?= $contact->work_phone ?></td>
            <td>&mdash;</td>
        </tr>
	<?php endforeach; ?>
    </tbody>
</table>
<?php $this->end(); ?>