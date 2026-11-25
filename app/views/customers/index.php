<div class="bar"><h1>Customers</h1><a class="btn" href="<?= e(url('customers/create')) ?>">+ New customer</a></div>
<div class="scroll"><table><thead><tr><th>ID</th><th>Name</th><th>Phone</th><th>Address</th><th>Login?</th><th></th></tr></thead><tbody>
<?php foreach ($rows as $c): ?>
<tr><td><?= (int)$c['customer_id'] ?></td><td><?= e($c['full_name']) ?></td><td><?= e($c['phone']) ?></td><td><?= e($c['address']) ?></td>
<td><?= $c['user_id'] ? 'Yes' : 'Walk-in' ?></td>
<td class="actions"><a href="<?= e(url('customers/edit', ['id' => $c['customer_id']])) ?>">Edit</a>
<form method="post" action="<?= e(url('customers/delete')) ?>" data-confirm="Delete this customer and all their orders?"><?= csrf_field() ?><input type="hidden" name="id" value="<?= (int)$c['customer_id'] ?>"><button class="link danger">Delete</button></form></td></tr>
<?php endforeach ?></tbody></table></div>
