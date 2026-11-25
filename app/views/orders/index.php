<div class="bar"><h1>Orders</h1>
  <?php if (in_array($u['role'], ['admin', 'customer'])): ?><a class="btn" href="<?= e(url('orders/create')) ?>">+ New order</a><?php endif ?></div>
<input type="search" id="q" placeholder="Live search: customer, status, item or order #…" aria-label="Search orders" autocomplete="off">
<div class="scroll"><table id="orders" data-role="<?= e($u['role']) ?>">
<thead><tr><th>#</th><th>Customer</th><th>Details</th><th>Total</th><th>Status</th><th>Rider</th><th></th></tr></thead>
<tbody>
<?php foreach ($orders as $o): ?>
<tr><td>#<?= (int)$o['order_id'] ?></td><td><?= e($o['full_name']) ?></td><td><?= e($o['details']) ?></td>
<td><?= e(number_format((float)$o['total'], 2, '.', '')) ?></td><td><?= badge($o['status']) ?></td><td><?= e($o['rider'] ?? '—') ?></td>
<td><a href="<?= e(url('orders/show', ['id' => $o['order_id']])) ?>">Track</a>
<?php if ($u['role'] === 'admin'): ?> <a href="<?= e(url('orders/edit', ['id' => $o['order_id']])) ?>">Edit</a><?php endif ?></td></tr>
<?php endforeach ?>
<?php if (!$orders): ?><tr><td colspan="7">No orders found.</td></tr><?php endif ?>
</tbody></table></div>
<p id="status" class="muted" aria-live="polite"></p>
