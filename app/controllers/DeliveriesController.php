<?php
class DeliveriesController extends Controller {
    function index() {
        $u = $this->role('admin', 'rider');
        $rows = $u['role'] === 'admin' ? Delivery::all() : Delivery::forRider($u['user_id']);
        $this->view('deliveries/index', ['title' => 'Deliveries', 'rows' => $rows, 'u' => $u]);
    }
    function assign() {
        $this->role('admin'); $err = [];
        $orders = Order::unassigned(); $riders = User::riders();
        if ($this->isPost()) {
            $this->post();
            $oid = (int)($_POST['order_id'] ?? 0); $rid = (int)($_POST['rider_id'] ?? 0);
            if (!in_array($oid, array_map('intval', array_column($orders, 'order_id')), true)) $err['order_id'] = 'Choose a pending, unassigned order.';
            if (!in_array($rid, array_map('intval', array_column($riders, 'user_id')), true)) $err['rider_id'] = 'Choose a rider.';
            if (!$err) { Delivery::assign($oid, $rid); flash("Order #$oid assigned."); redirect('deliveries/index'); }
        }
        $this->view('deliveries/form', ['title' => 'Assign rider', 'd' => null, 'err' => $err, 'orders' => $orders, 'riders' => $riders]);
    }
    function edit() {
        $this->role('admin'); $err = [];
        $d = Delivery::find((int)($_GET['id'] ?? 0));
        if (!$d) { http_response_code(404); exit('Delivery not found.'); }
        $riders = User::riders();
        if ($this->isPost()) {
            $this->post(); $rid = (int)($_POST['rider_id'] ?? 0);
            if (!in_array($rid, array_map('intval', array_column($riders, 'user_id')), true)) $err['rider_id'] = 'Choose a rider.';
            if (!$err) { Delivery::reassign($d, $rid); flash('Delivery reassigned and reset.'); redirect('deliveries/index'); }
        }
        $this->view('deliveries/form', ['title' => 'Edit delivery #' . $d['delivery_id'], 'd' => $d, 'err' => $err, 'orders' => [], 'riders' => $riders]);
    }
    function delete() {
        $this->role('admin'); $this->post();
        $d = Delivery::find((int)($_POST['id'] ?? 0));
        if ($d) { Delivery::delete($d); flash('Delivery removed; order is pending again.'); }
        redirect('deliveries/index');
    }
    function advance() {
        $u = $this->role('rider'); $this->post();
        $d = Delivery::find((int)($_POST['id'] ?? 0));
        if (!$d || (int)$d['rider_id'] !== $u['user_id']) { http_response_code(403); exit('Not your delivery.'); }
        Delivery::advance($d, (string)($_POST['status'] ?? '')) ? flash('Status updated.') : flash('Invalid status change.', 'err');
        redirect('deliveries/index');
    }
}
