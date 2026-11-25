<section class="panel narrow"><h1><?= e($title) ?></h1>
<form method="post" action="<?= $c ? e(url('customers/edit', ['id' => $c['customer_id']])) : e(url('customers/create')) ?>" data-validate novalidate>
  <?= csrf_field() ?>
  <?= field('Full name', 'full_name', $err, 'text', 'required minlength="2" maxlength="100"', $c['full_name'] ?? null) ?>
  <?= field('Phone', 'phone', $err, 'tel', 'required pattern="\+?[0-9 ]{7,15}" data-msg="Enter a valid phone number (7–15 digits)."', $c['phone'] ?? null) ?>
  <?= field('Address', 'address', $err, 'text', 'required minlength="5" maxlength="255"', $c['address'] ?? null) ?>
  <button class="btn">Save</button> <a href="<?= e(url('customers/index')) ?>">Cancel</a>
</form></section>
