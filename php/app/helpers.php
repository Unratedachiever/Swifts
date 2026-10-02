<?php
declare(strict_types=1);

/** Escape for HTML output. */
function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/** Format a dollar amount the way the original UI does. */
function money(float $amount): string
{
    return '$' . number_format($amount, 2);
}

/** Absolute path for an app route. */
function url(string $path = '/'): string
{
    return $path === '' ? '/' : $path;
}

/** Versioned asset URL (cache-busting when the file changes). */
function asset(string $path): string
{
    $file = dirname(__DIR__) . '/public/' . ltrim($path, '/');
    $version = is_file($file) ? (string) filemtime($file) : '1';
    return '/' . ltrim($path, '/') . '?v=' . $version;
}

/** Absolute URL (for links that leave the app, e.g. in email). */
function absolute_url(string $path = '/'): string
{
    return app_url() . url($path);
}

/** Redirect and stop. */
function redirect(string $path): never
{
    header('Location: ' . url($path), true, 303);
    exit;
}

/** Render a view file to a string. */
function view(string $name, array $data = []): string
{
    $file = __DIR__ . '/views/' . $name . '.php';
    if (!is_file($file)) {
        throw new RuntimeException("Missing view: {$name}");
    }
    extract($data, EXTR_SKIP);
    ob_start();
    require $file;
    return (string) ob_get_clean();
}

/** Render a page inside the site chrome. */
function layout(string $content, array $data = []): string
{
    return view('layout', $data + ['content' => $content]);
}

// ── Sessions / flash / CSRF ─────────────────────────────────────────────────

function session_start_once(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_set_cookie_params([
            'lifetime' => 0,
            'path' => '/',
            'httponly' => true,
            'samesite' => 'Lax',
            'secure' => (($_SERVER['HTTPS'] ?? '') === 'on')
                || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https'),
        ]);
        session_start();
    }
}

function csrf_token(): string
{
    session_start_once();
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(16));
    }
    return (string) $_SESSION['csrf'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="_token" value="' . e(csrf_token()) . '">';
}

function csrf_ok(): bool
{
    session_start_once();
    $sent = (string) ($_POST['_token'] ?? '');
    return $sent !== '' && hash_equals((string) ($_SESSION['csrf'] ?? ''), $sent);
}

function flash(string $key, string $message): void
{
    session_start_once();
    $_SESSION['flash'][$key] = $message;
}

function take_flash(string $key): ?string
{
    session_start_once();
    $message = $_SESSION['flash'][$key] ?? null;
    unset($_SESSION['flash'][$key]);
    return $message;
}

function old(string $key, string $default = ''): string
{
    session_start_once();
    return (string) ($_SESSION['old'][$key] ?? $default);
}

function remember_old(array $values): void
{
    session_start_once();
    $_SESSION['old'] = $values;
}

function clear_old(): void
{
    session_start_once();
    unset($_SESSION['old']);
}

/** Current path, without query string. */
function current_path(): string
{
    return parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
}

/** True when the current path matches (or is nested under) $path. */
function nav_active(string $path): bool
{
    $current = current_path();
    return $path === '/' ? $current === '/' : str_starts_with($current, $path);
}

/** Label for a shipment status. */
function status_label(string $status): string
{
    return STATUS_LABELS[$status] ?? ucfirst(str_replace('_', ' ', $status));
}
