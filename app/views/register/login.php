<?php $this->start('head') ?>
<?php $this->end() ?>
<?php $this->start('body') ?>
    <div class="col-md-6 col-md-offset-3 well">
        <form action="<?= PROOT ?>register/login" class="form" method="post">
            <?php if($this->displayErrors): ?>
            <div class="alert alert-danger"><?= $this->displayErrors ?></div>
            <?php endif;?>
            <h3 class="text-center">Login</h3>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username"
                       class="form-control"
					   <?= (!empty($_POST['username'])) ? 'value="' . $_POST['username'] . '"' : '' ?>
                >
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password"
                       class="form-control">
            </div>
            <div class="form-group">
                <label for="remember_me">Remember Me
                    <input type="checkbox" name="remember_me" id="remember_me"
						<?= (!empty($_POST['remember_me'])) ? 'checked="checked"':'' ?>
                           value="on">
                </label>
            </div>
            <div class="form-group">
                <input type="submit" value="Login"
                       class="btn btn-large btn-primary">
            </div>
            <?=FormHelpers::csrfInput()?>
        </form>
        <div class="text-right">
            <a href="<?= PROOT ?>register/register" class="text-primary">Register</a>
        </div>
    </div>
<?php $this->end() ?>