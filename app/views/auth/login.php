<section class="panel narrow"><h1>Log in</h1>
<form method="post" action="<?= e(url('auth/login')) ?>" data-validate novalidate>
  <?= csrf_field() ?>
  <?= field('Username or email', 'login', [], 'text', 'required maxlength="100" autocomplete="username"') ?>
  <?= field('Password', 'password', [], 'password', 'required autocomplete="current-password"') ?>
  <button class="btn">Log in</button>
</form>
<p class="muted">No account? <a href="<?= e(url('auth/register')) ?>">Register as a customer</a></p></section>
