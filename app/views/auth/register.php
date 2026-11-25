<section class="panel narrow"><h1>Create account</h1>
<form method="post" action="<?= e(url('auth/register')) ?>" data-validate novalidate>
  <?= csrf_field() ?>
  <?= field('Username', 'username', $err, 'text', 'required pattern="[A-Za-z0-9_]{3,30}" data-msg="3–30 letters, digits or underscores."') ?>
  <?= field('Email', 'email', $err, 'email', 'required maxlength="100"') ?>
  <?= field('Password', 'password', $err, 'password', 'required pattern="(?=.*[A-Za-z])(?=.*\d).{8,72}" data-msg="Min 8 characters, with a letter and a digit." autocomplete="new-password"') ?>
  <?= field('Confirm password', 'password_confirm', $err, 'password', 'required autocomplete="new-password"') ?>
  <?= field('Full name', 'full_name', $err, 'text', 'required minlength="2" maxlength="100"') ?>
  <?= field('Phone', 'phone', $err, 'tel', 'required pattern="\+?[0-9 ]{7,15}" data-msg="Enter a valid phone number (7–15 digits)."') ?>
  <?= field('Delivery address', 'address', $err, 'text', 'required minlength="5" maxlength="255"') ?>
  <button class="btn">Register</button>
</form></section>
