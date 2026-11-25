<div class="bar"><h1><?= $u['role'] === 'admin' ? 'Deliveries' : 'My deliveries' ?></h1>
<?php if ($u['role'] === 'admin'): ?><a class="btn" href="<?= e(url('deliveries/assign')) ?>">+ Assign rider</a><?php endif ?></div>
<div class="scroll"><table><thead><tr><th>#</th><th>Order</th><th>Customer / address</th><th>Rider</th><th>Status</th><th>Picked</th><th>Delivered</th><th></th></tr></thead><tbody>
<?php foreach ($rows as $d): ?>
<tr><td><?= (int)$d['delivery_id'] ?></td><td><a href="<?= e(url('orders/show', ['id' => $d['order_id']])) ?>">#<?= (int)$d['order_id'] ?></a></td>
<td><?= e($d['full_name']) ?><br><small><?= e($d['address']) ?></small></td><td><?= e($d['rider']) ?></td><td><?= badge($d['status']) ?></td>
<td><?= e($d['picked_at'] ?? '—') ?></td><td><?= e($d['delivered_at'] ?? '—') ?></td>
<td class="actions">
<?php if ($u['role'] === 'rider'): foreach (Delivery::NEXT[$d['status']] ?? [] as $next): ?>
  <form method="post" action="<?= e(url('deliveries/advance')) ?>"><?= csrf_field() ?><input type="hidden" name="id" value="<?= (int)$d['delivery_id'] ?>"><input type="hidden" name="status" value="<?= e($next) ?>"><button class="btn <?= $next === 'failed' ? 'danger' : '' ?>"><?= e(['picked_up' => 'Mark picked up', 'delivered' => 'Mark delivered', 'failed' => 'Failed'][$next]) ?></button></form>
<?php endforeach; else: ?>
  <a href="<?= e(url('deliveries/edit', ['id' => $d['delivery_id']])) ?>">Reassign</a>
  <form method="post" action="<?= e(url('deliveries/delete')) ?>" data-confirm="Remove this delivery?"><?= csrf_field() ?><input type="hidden" name="id" value="<?= (int)$d['delivery_id'] ?>"><button class="link danger">Delete</button></form>
<?php endif ?></td></tr>
<?php endforeach ?>
<?php if (!$rows): ?><tr><td colspan="8">No deliveries yet.</td></tr><?php endif ?>
</tbody></table></div>
