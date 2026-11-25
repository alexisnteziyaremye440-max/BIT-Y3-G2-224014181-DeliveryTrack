<h1>Hello, <?= e($u['username']) ?> <span class="badge"><?= e($u['role']) ?></span></h1>
<p class="muted">Orders visible to you, by status:</p>
<div class="cards">
<?php foreach (Order::STATUSES as $s): ?><div class="card"><b><?= (int)($counts[$s] ?? 0) ?></b><?= badge($s) ?></div><?php endforeach ?>
</div>
<p><a class="btn" href="<?= e(url('orders/index')) ?>">Open orders &amp; live search</a></p>
