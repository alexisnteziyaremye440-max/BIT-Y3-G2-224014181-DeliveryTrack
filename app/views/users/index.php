<div class="bar"><h1>Users</h1><a class="btn" href="<?= e(url('users/create')) ?>">+ New user</a></div>
<div class="scroll"><table><thead><tr><th>ID</th><th>Username</th><th>Email</th><th>Role</th><th>Created</th><th></th></tr></thead><tbody>
<?php foreach ($rows as $x): ?>
<tr><td><?= (int)$x['user_id'] ?></td><td><?= e($x['username']) ?></td><td><?= e($x['email']) ?></td><td><?= e($x['role']) ?></td><td><?= e($x['created_at']) ?></td>
<td class="actions"><a href="<?= e(url('users/edit', ['id' => $x['user_id']])) ?>">Edit</a>
<?php if ((int)$x['user_id'] !== $me): ?><form method="post" action="<?= e(url('users/delete')) ?>" data-confirm="Delete this user?"><?= csrf_field() ?><input type="hidden" name="id" value="<?= (int)$x['user_id'] ?>"><button class="link danger">Delete</button></form><?php endif ?></td></tr>
<?php endforeach ?></tbody></table></div>
