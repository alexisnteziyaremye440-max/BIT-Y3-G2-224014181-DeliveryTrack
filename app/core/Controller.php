<?php
abstract class Controller {
    protected function view(string $v, array $d = []): void {
        extract($d);
        $title = $d['title'] ?? 'DeliveryTrack';
        ob_start();
        require __DIR__ . "/../views/$v.php";
        $content = ob_get_clean();
        require __DIR__ . '/../views/layout.php';
    }
    protected function json(array $d, int $code = 200): never {
        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($d, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
        exit;
    }
    protected function requireLogin(): array {
        if (!user()) { flash('Please log in first.', 'err'); redirect('auth/login'); }
        return user();
    }
    protected function role(string ...$roles): array {
        $u = $this->requireLogin();
        if (!in_array($u['role'], $roles, true)) { http_response_code(403); exit('403 Forbidden'); }
        return $u;
    }
    /** Call at the top of every state-changing action. */
    protected function post(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_ok()) { http_response_code(419); exit('Invalid or missing CSRF token.'); }
    }
    protected function isPost(): bool { return $_SERVER['REQUEST_METHOD'] === 'POST'; }
}
