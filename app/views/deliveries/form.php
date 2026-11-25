<section class="panel narrow"><h1><?= e($title) ?></h1>
<form method="post" action="<?= $d ? e(url('deliveries/edit', ['id' => $d['delivery_id']])) : e(url('deliveries/assign')) ?>" data-validate novalidate>
  <?= csrf_field() ?>
  <?php if ($d): ?><p>Order <b>#<?= (int)$d['order_id'] ?></b> — <?= e($d['details']) ?> (<?= badge($d['status']) ?>)</p><p class="muted">Saving resets the delivery to “assigned”.</p>
  <?php else:
    $oo = []; foreach ($orders as $o) $oo[$o['order_id']] = '#' . $o['order_id'] . ' — ' . $o['details'];
    echo select_field('Order (pending only)', 'order_id', $oo, $_POST['order_id'] ?? '', $err);
  endif;
  $rr = []; foreach ($riders as $r) $rr[$r['user_id']] = $r['username'];
  echo select_field('Rider', 'rider_id', $rr, $_POST['rider_id'] ?? $d['rider_id'] ?? '', $err); ?>
  <button class="btn">Save</button> <a href="<?= e(url('deliveries/index')) ?>">Cancel</a>
</form></section>
