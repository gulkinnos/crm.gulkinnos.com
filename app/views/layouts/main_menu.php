<?php
use App\Models\Users;
use Core\Helpers;
use Core\Router;

$menu        = Router::getMenu('menu_acl');
$currentPage = Helpers::currentPage();
?>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
	<a class="navbar-brand" href="<?= PROOT ?>"><?= MENU_BRAND ?></a>
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main_menu"
			aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>
	<div class="collapse navbar-collapse" id="main_menu">
		<ul class="navbar-nav mr-auto">
			<?php foreach ($menu as $title => $menuItem):
				$active = ''; ?>
				<?php if (is_array($menuItem) && !empty($menuItem)): ?>
				<?php foreach ($menuItem as $innerTitle => $innerMenuItem):
					$activeInner = ($innerMenuItem === $currentPage) ? 'active' : ''; ?>
					<?php if ($activeInner) {
					$active = 'active';
					break;
				} ?>
				<?php endforeach; ?>
				<li class="nav-item dropdown <?= $active ?>">
					<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
					   data-toggle="dropdown"
					   aria-haspopup="true" aria-expanded="false">
						<?= $title ?>
					</a>
					<div class="dropdown-menu" aria-labelledby="navbarDropdown">
						<?php foreach ($menuItem as $innerTitle => $innerMenuItem):
							$activeInner = ($innerMenuItem === $currentPage) ? 'active' : ''; ?>
							<?php if ($innerTitle === 'separator'): ?>
							<div class="dropdown-divider"></div>
						<?php else: ?>
							<a class="dropdown-item <?= $activeInner ?>"
							   href="<?= $innerMenuItem ?>"><?= $innerTitle ?></a>
						<?php endif; ?>
						<?php endforeach; ?>
					</div>
				</li>
			<?php else: ?>
				<?php $active = ($menuItem === $currentPage) ? 'active' : ''; ?>
				<li class="nav-item <?= $active ?>">
					<a class="nav-link" href="<?= $menuItem ?>"><?= $title ?></a>
				</li>
			<?php endif; ?>
			<?php endforeach; ?>
			<?php if (Users::currentUser()): ?>
				<li class="nav-item">
					<a class="nav-link" href="#">Hello <?= Users::currentUser()->data()->fname ?></a>
				</li>
			<?php endif; ?>
		</ul>
	</div>
</nav>