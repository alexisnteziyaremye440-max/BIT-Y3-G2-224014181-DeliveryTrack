<?php
class ApiController extends Controller {
    /** GET index.php?r=api/orders&q=term  ->  {"count":n,"data":[...]} (role-scoped live search) */
    function orders() {
        $u = user();
        if (!$u) $this->json(['error' => 'Authentication required'], 401);
        $q = mb_substr(trim((string)($_GET['q'] ?? '')), 0, 50);
        $rows = Order::search($q, $u);
        $this->json(['count' => count($rows), 'data' => $rows]);
    }
}
