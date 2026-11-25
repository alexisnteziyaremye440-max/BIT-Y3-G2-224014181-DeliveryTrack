<?php
class CustomersController extends Controller {
    function index() { $this->role('admin'); $this->view('customers/index', ['title' => 'Customers', 'rows' => Customer::all()]); }
    function create() {
        $this->role('admin'); $err = [];
        if ($this->isPost()) {
            $this->post(); $err = Customer::validate($_POST);
            if (!$err) { Customer::create(trim($_POST['full_name']), trim($_POST['phone']), trim($_POST['address'])); flash('Customer created.'); redirect('customers/index'); }
        }
        $this->view('customers/form', ['title' => 'New customer', 'c' => null, 'err' => $err]);
    }
    function edit() {
        $this->role('admin'); $err = [];
        $c = Customer::find((int)($_GET['id'] ?? 0));
        if (!$c) { http_response_code(404); exit('Customer not found.'); }
        if ($this->isPost()) {
            $this->post(); $err = Customer::validate($_POST);
            if (!$err) { Customer::update((int)$c['customer_id'], trim($_POST['full_name']), trim($_POST['phone']), trim($_POST['address'])); flash('Customer updated.'); redirect('customers/index'); }
        }
        $this->view('customers/form', ['title' => 'Edit customer', 'c' => $c, 'err' => $err]);
    }
    function delete() {
        $this->role('admin'); $this->post();
        Customer::delete((int)($_POST['id'] ?? 0));
        flash('Customer (and their orders) deleted.'); redirect('customers/index');
    }
}
