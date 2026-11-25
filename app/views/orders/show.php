<div class="bar"><h1>Order #<?= (int)$o['order_id'] ?> <?= badge($o['status']) ?></h1>
<div class="actions">
<?php if ($u['role'] === 'admin'): ?>
  <a class="btn alt" href="<?= e(url('orders/edit', ['id' => $o['order_id']])) ?>">Edit</a>
  <form method="post" action="<?= e(url('orders/delete')) ?>" data-confirm="Delete this order permanently?"><?= csrf_field() ?><input type="hidden" name="id" value="<?= (int)$o['order_id'] ?>"><button class="btn danger">Delete</button></form>
<?php elseif ($u['role'] === 'customer' && $o['status'] === 'pending'): ?>
  <form method="post" action="<?= e(url('orders/cancel')) ?>" data-confirm="Cancel this order?"><?= csrf_field() ?><input type="hidden" name="id" value="<?= (int)$o['order_id'] ?>"><button class="btn danger">Cancel order</button></form>
<?php endif ?></div></div>
<div class="panel">
  <p><b>Customer:</b> <?= e($o['full_name']) ?> · <?= e($o['phone']) ?></p>
  <p><b>Address:</b> <?= e($o['address']) ?></p>
  <p><b>Items:</b> <?= e($o['details']) ?> — <b>Total:</b> <?= e(number_format((float)$o['total'], 2)) ?></p>
  <p><b>Placed:</b> <?= e($o['order_date']) ?> · <b>Rider:</b> <?= e($d['rider'] ?? 'not assigned yet') ?></p>
</div>
<?php if ($o['status'] === 'cancelled'): ?><p class="flash err">This order was cancelled.</p><?php else:
  $steps = [['Order placed', $o['order_date']], ['Rider assigned', $d ? 'assigned' : null], ['Picked up', $d['picked_at'] ?? null], ['Delivered', $d['delivered_at'] ?? null]]; ?>
<ol class="timeline"><?php foreach ($steps as [$label, $when]): ?>
  <li class="<?= $when ? 'done' : '' ?>"><b><?= e($label) ?></b><small><?= e($when && $when !== 'assigned' ? $when : ($when ? '✓' : 'pending')) ?></small></li>
<?php endforeach ?></ol>
<?php if ($d && $d['status'] === 'failed'): ?><p class="flash err">Last delivery attempt failed — admin will reassign.</p><?php endif ?>
<?php endif ?>
<p><a href="<?= e(url('orders/index')) ?>">← Back to orders</a></p>
