<?php
class HomeController extends Controller {
    function index() {
        $u = $this->requireLogin();
        $counts = array_count_values(array_column(Order::search('', $u), 'status'));
        $this->view('home/index', compact('u', 'counts') + ['title' => 'Dashboard']);
    }
}
