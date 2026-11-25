<section class="panel narrow"><h1><?= e($title) ?></h1>
<form method="post" action="<?= $x ? e(url('users/edit', ['id' => $x['user_id']])) : e(url('users/create')) ?>" data-validate novalidate>
  <?= csrf_field() ?>
  <?= field('Username', 'username', $err, 'text', 'required pattern="[A-Za-z0-9_]{3,30}" data-msg="3–30 letters, digits or underscores."', $x['username'] ?? null) ?>
  <?= field('Email', 'email', $err, 'email', 'required maxlength="100"', $x['email'] ?? null) ?>
  <?= field($x ? 'New password (leave blank to keep)' : 'Password', 'password', $err, 'password', ($x ? '' : 'required ') . 'pattern="(?=.*[A-Za-z])(?=.*\d).{8,72}" data-msg="Min 8 characters, with a letter and a digit." autocomplete="new-password"') ?>
  <?= select_field('Role', 'role', ['admin' => 'admin', 'customer' => 'customer', 'rider' => 'rider'], $_POST['role'] ?? $x['role'] ?? '', $err) ?>
  <button class="btn">Save</button> <a href="<?= e(url('users/index')) ?>">Cancel</a>
</form></section>
