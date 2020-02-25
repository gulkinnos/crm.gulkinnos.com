<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <title><?= $this->siteTitle() ?></title>

    <!-- Bootstrap -->
    <link href="<?= PROOT ?>css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= PROOT ?>css/custom.css" rel="stylesheet">
    <script src="<?= PROOT ?>js/jquery-3.2.1.min.js"></script>
    <script src="<?= PROOT ?>js/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

    <?= $this->content('head') ?>

</head>
<body>
<?php //include 'main_menu.php' ?>
<div class="container-fluid" style="min-height: calc(100% - 125px);">

</div>
<?= $this->content('body') ?>

</body>
</html>