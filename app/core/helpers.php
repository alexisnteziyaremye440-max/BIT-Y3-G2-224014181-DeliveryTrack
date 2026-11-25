<?php
function e($v): string { return htmlspecialchars((string)$v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }
function url(string $r = 'home/index', array $q = []): string { return 'index.php?' . http_build_query(['r' => $r] + $q); }
function redirect(string $r, array $q = []): never { header('Location: ' . url($r, $q)); exit; }
function csrf_token(): string { return $_SESSION['csrf'] ??= bin2hex(random_bytes(32)); }
function csrf_field(): string { return '<input type="hidden" name="csrf" value="' . e(csrf_token()) . '">'; }
function csrf_ok(): bool { return isset($_POST['csrf'], $_SESSION['csrf']) && hash_equals($_SESSION['csrf'], (string)$_POST['csrf']); }
function flash(?string $msg = null, string $type = 'ok') {
    if ($msg !== null) { $_SESSION['flash'] = [$msg, $type]; return null; }
    $f = $_SESSION['flash'] ?? null; unset($_SESSION['flash']); return $f;
}
function user(): ?array { return $_SESSION['user'] ?? null; }
function badge(string $s): string { return '<span class="badge s-' . e($s) . '">' . e(str_replace('_', ' ', $s)) . '</span>'; }
function field(string $label, string $name, array $err = [], string $type = 'text', string $attrs = '', $value = null): string {
    $v = $type === 'password' ? '' : e($_POST[$name] ?? $value ?? '');
    return '<label>' . e($label) . '<input type="' . $type . '" name="' . $name . '" value="' . $v . '" ' . $attrs . '><small class="err">' . e($err[$name] ?? '') . '</small></label>';
}
function select_field(string $label, string $name, array $opts, $sel, array $err = [], string $attrs = 'required'): string {
    $h = '<label>' . e($label) . '<select name="' . $name . '" ' . $attrs . '><option value="">— choose —</option>';
    foreach ($opts as $k => $t) $h .= '<option value="' . e($k) . '"' . ((string)$k === (string)$sel ? ' selected' : '') . '>' . e($t) . '</option>';
    return $h . '</select><small class="err">' . e($err[$name] ?? '') . '</small></label>';
}
