<?php
class AuthController extends Controller {
    function login() {
        if ($this->isPost()) {
            $this->post();
            $l = trim($_POST['login'] ?? ''); $p = (string)($_POST['password'] ?? '');
            $u = $l !== '' ? User::byLogin($l) : null;
            if ($u && password_verify($p, $u['password_hash'])) {
                session_regenerate_id(true);
                $_SESSION['user'] = ['user_id' => (int)$u['user_id'], 'username' => $u['username'], 'role' => $u['role']];
                flash('Welcome back, ' . $u['username'] . '!');
                redirect('home/index');
            }
            flash('Invalid username/email or password.', 'err');
        }
        $this->view('auth/login', ['title' => 'Log in']);
    }
    function register() {
        $err = [];
        if ($this->isPost()) {
            $this->post();
            $err = User::validate($_POST) + Customer::validate($_POST);
            if (($_POST['password'] ?? '') !== ($_POST['password_confirm'] ?? '')) $err['password_confirm'] = 'Passwords do not match.';
            if (!$err) {
                $pdo = Database::pdo(); $pdo->beginTransaction();
                try {
                    $uid = User::create(trim($_POST['username']), trim($_POST['email']), $_POST['password'], 'customer');
                    Customer::create(trim($_POST['full_name']), trim($_POST['phone']), trim($_POST['address']), $uid);
                    $pdo->commit();
                } catch (Throwable $t) { $pdo->rollBack(); throw $t; }
                flash('Account created. Please log in.');
                redirect('auth/login');
            }
        }
        $this->view('auth/register', ['title' => 'Register', 'err' => $err]);
    }
    function logout() {
        $this->post();
        $_SESSION = [];
        session_regenerate_id(true);
        flash('You have been logged out.');
        redirect('auth/login');
    }
}
