<!DOCTYPE html>
<html lang="en"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($title) ?> · DeliveryTrack</title>
<link rel="stylesheet" href="css/style.css"><script src="js/app.js" defer></script>
</head><body>
<?php $me = user(); ?>
<header class="nav">
  <a class="brand" href="<?= e(url()) ?>">🚚 DeliveryTrack</a>
  <button id="menu" type="button" aria-label="Toggle menu">☰</button>
  <nav id="links">
    <?php if ($me): ?>
      <a href="<?= e(url('home/index')) ?>">Dashboard</a>
      <a href="<?= e(url('orders/index')) ?>"><?= $me['role'] === 'customer' ? 'My orders' : 'Orders' ?></a>
      <?php if (in_array($me['role'], ['admin', 'customer'])): ?><a href="<?= e(url('orders/create')) ?>">New order</a><?php endif ?>
      <?php if ($me['role'] === 'admin'): ?>
        <a href="<?= e(url('customers/index')) ?>">Customers</a><a href="<?= e(url('users/index')) ?>">Users</a>
      <?php endif ?>
      <?php if (in_array($me['role'], ['admin', 'rider'])): ?><a href="<?= e(url('deliveries/index')) ?>">Deliveries</a><?php endif ?>
      <form method="post" action="<?= e(url('auth/logout')) ?>"><?= csrf_field() ?><button class="link"><?= e($me['username']) ?> · Log out</button></form>
    <?php else: ?>
      <a href="<?= e(url('auth/login')) ?>">Log in</a><a href="<?= e(url('auth/register')) ?>">Register</a>
    <?php endif ?>
  </nav>
</header>
<main>
  <?php if ($f = flash()): ?><div class="flash <?= e($f[1]) ?>" role="alert"><?= e($f[0]) ?></div><?php endif ?>
  <?= $content ?>
</main>
<footer>BIT Y3 · Online Delivery Tracking System</footer>
</body></html>
