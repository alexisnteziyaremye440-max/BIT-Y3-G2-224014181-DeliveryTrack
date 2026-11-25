<section class="panel narrow"><h1><?= e($title) ?></h1>
<form method="post" action="<?= $o ? e(url('orders/edit', ['id' => $o['order_id']])) : e(url('orders/create')) ?>" data-validate novalidate>
  <?= csrf_field() ?>
  <?php if ($u['role'] === 'admin'):
    $opts = []; foreach ($customers as $c) $opts[$c['customer_id']] = $c['full_name'];
    echo select_field('Customer', 'customer_id', $opts, $_POST['customer_id'] ?? $o['customer_id'] ?? '', $err);
  endif ?>
  <?= field('Order details', 'details', $err, 'text', 'required minlength="3" maxlength="255"', $o['details'] ?? null) ?>
  <?= field('Total', 'total', $err, 'number', 'required min="0.01" max="9999999" step="0.01"', $o['total'] ?? null) ?>
  <?php if ($o) echo select_field('Status', 'status', array_combine(Order::STATUSES, Order::STATUSES), $_POST['status'] ?? $o['status'], $err) ?>
  <button class="btn">Save</button> <a href="<?= e(url('orders/index')) ?>">Cancel</a>
</form></section>
