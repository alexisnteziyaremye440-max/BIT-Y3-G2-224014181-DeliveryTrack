<?php
class OrdersController extends Controller {
    function index() {
        $u = $this->requireLogin();
        $this->view('orders/index', ['title' => 'Orders', 'orders' => Order::search('', $u), 'u' => $u]);
    }
    function show() {
        $u = $this->requireLogin();
        $o = $this->owned((int)($_GET['id'] ?? 0), $u);
        $this->view('orders/show', ['title' => 'Order #' . $o['order_id'], 'o' => $o, 'd' => Delivery::byOrder((int)$o['order_id']), 'u' => $u]);
    }
    function create() {
        $u = $this->role('admin', 'customer'); $err = [];
        if ($u['role'] === 'customer' && !Customer::byUser($u['user_id'])) { flash('No customer profile found for your account.', 'err'); redirect('home/index'); }
        if ($this->isPost()) {
            $this->post();
            [$d, $err] = $this->validate($u, false);
            if (!$err) { $id = Order::create($d['customer_id'], $d['details'], $d['total']); flash("Order #$id placed."); redirect('orders/show', ['id' => $id]); }
        }
        $this->view('orders/form', ['title' => 'New order', 'o' => null, 'err' => $err, 'u' => $u, 'customers' => $u['role'] === 'admin' ? Customer::all() : []]);
    }
    function edit() {
        $u = $this->role('admin'); $err = [];
        $o = Order::find((int)($_GET['id'] ?? 0));
        if (!$o) { http_response_code(404); exit('Order not found.'); }
        if ($this->isPost()) {
            $this->post();
            [$d, $err] = $this->validate($u, true);
            if (!$err) { Order::update((int)$o['order_id'], $d['customer_id'], $d['details'], $d['total'], $d['status']); flash('Order updated.'); redirect('orders/show', ['id' => $o['order_id']]); }
        }
        $this->view('orders/form', ['title' => 'Edit order #' . $o['order_id'], 'o' => $o, 'err' => $err, 'u' => $u, 'customers' => Customer::all()]);
    }
    function delete() {
        $this->role('admin'); $this->post();
        Order::delete((int)($_POST['id'] ?? 0));
        flash('Order deleted.'); redirect('orders/index');
    }
    function cancel() {
        $u = $this->role('customer'); $this->post();
        $o = $this->owned((int)($_POST['id'] ?? 0), $u);
        if ($o['status'] === 'pending') { Order::setStatus((int)$o['order_id'], 'cancelled'); flash('Order cancelled.'); }
        else flash('Only pending orders can be cancelled.', 'err');
        redirect('orders/show', ['id' => $o['order_id']]);
    }
    private function owned(int $id, array $u): array {
        $o = Order::find($id);
        $ok = $o && ($u['role'] === 'admin'
            || ($u['role'] === 'customer' && (int)$o['cust_user'] === $u['user_id'])
            || ($u['role'] === 'rider' && (int)$o['rider_id'] === $u['user_id']));
        if (!$ok) { http_response_code(404); exit('Order not found.'); }
        return $o;
    }
    private function validate(array $u, bool $withStatus): array {
        $e = []; $details = trim($_POST['details'] ?? ''); $total = $_POST['total'] ?? ''; $status = $_POST['status'] ?? 'pending';
        if (mb_strlen($details) < 3 || mb_strlen($details) > 255) $e['details'] = 'Details must be 3–255 characters.';
        if (!is_numeric($total) || (float)$total <= 0 || (float)$total > 9999999) $e['total'] = 'Total must be a positive number.';
        if ($u['role'] === 'admin') {
            $cid = (int)($_POST['customer_id'] ?? 0);
            if (!Customer::find($cid)) $e['customer_id'] = 'Choose a customer.';
        } else $cid = (int)Customer::byUser($u['user_id'])['customer_id'];
        if ($withStatus && !in_array($status, Order::STATUSES, true)) $e['status'] = 'Invalid status.';
        return [['customer_id' => $cid, 'details' => $details, 'total' => round((float)$total, 2), 'status' => $status], $e];
    }
}
