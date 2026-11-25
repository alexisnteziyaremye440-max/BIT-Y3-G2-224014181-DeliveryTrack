<?php
class UsersController extends Controller {
    function index() { $this->role('admin'); $this->view('users/index', ['title' => 'Users', 'rows' => User::all(), 'me' => user()['user_id']]); }
    function create() {
        $this->role('admin'); $err = [];
        if ($this->isPost()) {
            $this->post(); $err = User::validate($_POST, 0, true, true);
            if (!$err) { User::create(trim($_POST['username']), trim($_POST['email']), $_POST['password'], $_POST['role']); flash('User created.'); redirect('users/index'); }
        }
        $this->view('users/form', ['title' => 'New user', 'x' => null, 'err' => $err]);
    }
    function edit() {
        $this->role('admin'); $err = [];
        $x = User::find((int)($_GET['id'] ?? 0));
        if (!$x) { http_response_code(404); exit('User not found.'); }
        if ($this->isPost()) {
            $this->post(); $err = User::validate($_POST, (int)$x['user_id'], false, true);
            if (!$err) { User::update((int)$x['user_id'], trim($_POST['username']), trim($_POST['email']), $_POST['role'], (string)($_POST['password'] ?? '')); flash('User updated.'); redirect('users/index'); }
        }
        $this->view('users/form', ['title' => 'Edit user', 'x' => $x, 'err' => $err]);
    }
    function delete() {
        $u = $this->role('admin'); $this->post();
        $id = (int)($_POST['id'] ?? 0);
        if ($id === $u['user_id']) flash('You cannot delete your own account.', 'err');
        else {
            try { User::delete($id); flash('User deleted.'); }
            catch (PDOException $e) { flash('Cannot delete: this rider still has deliveries.', 'err'); }
        }
        redirect('users/index');
    }
}
