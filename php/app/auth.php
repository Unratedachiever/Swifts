<?php
declare(strict_types=1);

require_once __DIR__ . '/db.php';
require_once __DIR__ . '/helpers.php';

const SESSION_COOKIE = 'swiftship_session';
const SESSION_DAYS = 30;

function current_user(): ?array
{
    static $cached = null;
    static $loaded = false;
    if ($loaded) {
        return $cached;
    }
    $loaded = true;

    $token = $_COOKIE[SESSION_COOKIE] ?? '';
    if ($token === '') {
        return null;
    }

    $stmt = db()->prepare(
        "select u.* from sessions s
         join users u on u.id = s.user_id
         where s.id = ? and s.expires_at > datetime('now')"
    );
    $stmt->execute([$token]);
    $user = $stmt->fetch();

    $cached = $user ?: null;
    return $cached;
}

/** Load one user row by id (used right after sign-up, when the session cookie
 *  is set but not yet visible in $_COOKIE for this request). */
function find_user(int $id): ?array
{
    $stmt = db()->prepare('select * from users where id = ?');
    $stmt->execute([$id]);
    $user = $stmt->fetch();
    return $user ?: null;
}

function require_user(): array
{
    $user = current_user();
    if (!$user) {
        flash('error', 'Please sign in to continue.');
        redirect('/login');
    }
    return $user;
}

function login_user(int $userId): void
{
    $token = bin2hex(random_bytes(32));
    $expires = date('Y-m-d H:i:s', strtotime('+' . SESSION_DAYS . ' days'));
    $stmt = db()->prepare('insert into sessions (id, user_id, expires_at) values (?, ?, ?)');
    $stmt->execute([$token, $userId, $expires]);

    setcookie(SESSION_COOKIE, $token, [
        'expires' => strtotime($expires),
        'path' => '/',
        'httponly' => true,
        'samesite' => 'Lax',
        'secure' => (($_SERVER['HTTPS'] ?? '') === 'on')
            || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https'),
    ]);
}

function logout_user(): void
{
    $token = $_COOKIE[SESSION_COOKIE] ?? '';
    if ($token !== '') {
        $stmt = db()->prepare('delete from sessions where id = ?');
        $stmt->execute([$token]);
    }
    setcookie(SESSION_COOKIE, '', ['expires' => time() - 3600, 'path' => '/']);
}

/**
 * Create the account and send the welcome email.
 *
 * @return array{0: ?int, 1: array<string, string>} [userId, errors]
 */
function register_user(string $name, string $email, string $password): array
{
    $errors = [];

    if (mb_strlen(trim($name)) < 2) {
        $errors['name'] = 'Please enter your full name.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Please enter a valid email address.';
    }
    if (strlen($password) < 8) {
        $errors['password'] = 'Use at least 8 characters.';
    }

    if (!$errors) {
        $stmt = db()->prepare('select id from users where lower(email) = lower(?)');
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $errors['email'] = 'That email already has an account.';
        }
    }

    if ($errors) {
        return [null, $errors];
    }

    $stmt = db()->prepare('insert into users (name, email, password_hash) values (?, ?, ?)');
    $stmt->execute([trim($name), mb_strtolower(trim($email)), password_hash($password, PASSWORD_DEFAULT)]);

    return [(int) db()->lastInsertId(), []];
}

function authenticate(string $email, string $password): ?array
{
    $stmt = db()->prepare('select * from users where lower(email) = lower(?)');
    $stmt->execute([trim($email)]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, (string) $user['password_hash'])) {
        return null;
    }
    return $user;
}
