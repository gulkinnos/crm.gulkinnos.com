<?php $this->start('head') ?>
<?php $this->end() ?>
<?php $this->start('body') ?>
    <div class="col-md-6 col-md-offset-3 well">
        <form action="<?= PROOT ?>register/login" class="form" method="post">
            <h3 class="text-center">Login</h3>
			<?php if($this->displayErrors) {
				echo FormHelpers::displayErrors($this->displayErrors);
			} ?>
			<?= FormHelpers::inputBlock('text', 'Username', 'username', $this->login->username, ['class' => 'form-control'], ['class' => 'form-group']) ?>
			<?= FormHelpers::inputBlock('password', 'Password', 'password', $this->login->password, ['class' => 'form-control'], ['class' => 'form-group']) ?>
			<?= FormHelpers::checkboxBlock('Remember Me', 'remember_me', $this->login->getRememberMeChecked(), [], ['class' => 'form-group']) ?>
			<?= FormHelpers::csrfInput() ?>
			<?= FormHelpers::submitBlock('Login', ['class' => 'btn btn-large btn-primary'], ['class' => 'form-group']) ?>
        </form>
        <div class="text-right">
            <a href="<?= PROOT ?>register/register" class="text-primary">Register</a>
        </div>
    </div>
<?php $this->end() ?>