<?php
declare(strict_types=1);

/**
 * SwiftShip Logistics — PHP port (front controller).
 *
 * Plain PHP + PDO: one router, one PDO connection, view files. Run with
 *   php -S 0.0.0.0:8080 -t public public/index.php
 */

require_once __DIR__ . '/../app/config.php';
require_once __DIR__ . '/../app/helpers.php';
require_once __DIR__ . '/../app/db.php';
require_once __DIR__ . '/../app/auth.php';
require_once __DIR__ . '/../app/mail.php';
require_once __DIR__ . '/../app/icons.php';

// Let the PHP dev server serve real files (CSS, JS, images) itself.
if (PHP_SAPI === 'cli-server') {
    $requested = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    $file = __DIR__ . urldecode($requested);
    if ($requested !== '/' && is_file($file)) {
        return false;
    }
}

session_start_once();

$method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
$path = rtrim(current_path(), '/');
if ($path === '') {
    $path = '/';
}

/** Render a page view wrapped in the site chrome. */
function page(string $viewName, array $data = [], int $status = 200): void
{
    http_response_code($status);
    echo layout(view('pages/' . $viewName, $data), $data);
}

function not_found(): void
{
    page('not-found', ['title' => 'Page not found'], 404);
}

/** Routes whose booking/account flows are still being ported. */
function coming_soon(string $what): void
{
    page('todo', ['title' => 'Coming next', 'what' => $what]);
}

switch (true) {
    case $path === '/':
        page('home', ['title' => '']);
        break;

    case $path === '/services':
        page('services', ['title' => 'Services', 'services' => array_values(SERVICES)]);
        break;

    case $path === '/pricing':
        page('pricing', ['title' => 'Pricing', 'services' => array_values(SERVICES)]);
        break;

    case $path === '/freight':
        page('freight', ['title' => 'Freight']);
        break;

    case $path === '/business':
        page('business', ['title' => 'Business']);
        break;

    case $path === '/about':
        page('about', ['title' => 'About']);
        break;

    case $path === '/help':
        page('help', ['title' => 'Help center']);
        break;

    case $path === '/returns':
        page('returns', ['title' => 'Returns']);
        break;

    case $path === '/contact' && $method === 'GET':
        page('contact', ['title' => 'Contact']);
        break;

    case $path === '/contact' && $method === 'POST':
        if (!csrf_ok()) {
            flash('error', 'Your session expired — please send the message again.');
            redirect('/contact');
        }
        $name = trim((string) ($_POST['name'] ?? ''));
        $email = trim((string) ($_POST['email'] ?? ''));
        $topic = (string) ($_POST['topic'] ?? 'shipment');
        $message = trim((string) ($_POST['message'] ?? ''));
        $errors = [];
        if ($name === '') {
            $errors['name'] = 'Please tell us your name.';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Please enter a valid email address.';
        }
        if (mb_strlen($message) < 10) {
            $errors['message'] = 'Please add a little more detail (10+ characters).';
        }
        if ($errors) {
            remember_old(['name' => $name, 'email' => $email, 'topic' => $topic, 'message' => $message]);
            page('contact', ['title' => 'Contact', 'errors' => $errors], 422);
            break;
        }
        $stmt = db()->prepare('insert into contact_messages (name, email, topic, message) values (?, ?, ?, ?)');
        $stmt->execute([$name, $email, $topic, $message]);
        clear_old();
        flash('success', 'Message received. A specialist will follow up.');
        redirect('/contact');
        break;

    case $path === '/locations':
        $q = trim((string) ($_GET['q'] ?? ''));
        $type = trim((string) ($_GET['type'] ?? ''));
        $sql = 'select * from facilities';
        $conditions = [];
        $params = [];
        if ($type !== '') {
            $conditions[] = 'type = ?';
            $params[] = $type;
        }
        if ($q !== '') {
            $conditions[] = "(name || ' ' || city || ' ' || state || ' ' || postal_code || ' ' || country) like ?";
            $params[] = '%' . str_replace(['%', '_'], ['\%', '\_'], $q) . '%';
        }
        if ($conditions) {
            $sql .= ' where ' . implode(' and ', $conditions);
        }
        $sql .= ' order by type, name';
        $stmt = db()->prepare($sql);
        $stmt->execute($params);
        page('locations', ['title' => 'Locations', 'facilities' => $stmt->fetchAll(), 'q' => $q, 'type' => $type]);
        break;

    case $path === '/track' && $method === 'GET':
        // The tracking form submits to /track?number=… — send visitors to the
        // shareable /track/{number} URL.
        $number = trim((string) ($_GET['number'] ?? ''));
        if ($number !== '') {
            redirect('/track/' . rawurlencode(mb_strtoupper($number)));
        }
        page('track', ['title' => 'Track a shipment']);
        break;

    case str_starts_with($path, '/track/'):
        $code = rawurldecode(substr($path, strlen('/track/')));
        $shipment = $code === '' ? null : find_shipment($code);
        page('track-show', [
            'title' => 'Tracking ' . mb_strtoupper($code),
            'code' => mb_strtoupper($code),
            'shipment' => $shipment,
        ], $shipment ? 200 : 404);
        break;

    case $path === '/register' && $method === 'GET':
        if (current_user()) {
            redirect('/account');
        }
        page('register', ['title' => 'Create account', 'errors' => []]);
        break;

    case $path === '/register' && $method === 'POST':
        if (!csrf_ok()) {
            flash('error', 'Your session expired — please try again.');
            redirect('/register');
        }
        $name = trim((string) ($_POST['name'] ?? ''));
        $email = trim((string) ($_POST['email'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');
        [$userId, $errors] = register_user($name, $email, $password);
        if ($errors || $userId === null) {
            remember_old(['name' => $name, 'email' => $email]);
            page('register', ['title' => 'Create account', 'errors' => $errors], 422);
            break;
        }
        clear_old();
        login_user($userId);
        // Read the row directly: setcookie() does not update $_COOKIE in this
        // same request, so current_user() would still be null here.
        $user = find_user($userId);
        // Welcome email with the branded template — never blocks the sign-up.
        $sent = $user ? send_welcome_email($user) : false;
        flash(
            'success',
            $sent
                ? 'Account created. We just emailed a welcome note to ' . $email . '.'
                : 'Account created — but the welcome email could not be sent. Check the mail server settings.'
        );
        redirect('/account');
        break;

    case $path === '/login' && $method === 'GET':
        if (current_user()) {
            redirect('/account');
        }
        page('login', ['title' => 'Log in']);
        break;

    case $path === '/login' && $method === 'POST':
        if (!csrf_ok()) {
            flash('error', 'Your session expired — please try again.');
            redirect('/login');
        }
        $email = trim((string) ($_POST['email'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');
        $user = authenticate($email, $password);
        if (!$user) {
            remember_old(['email' => $email]);
            page('login', ['title' => 'Log in', 'error' => 'Those credentials did not match an account.'], 422);
            break;
        }
        clear_old();
        login_user((int) $user['id']);
        redirect('/account');
        break;

    case $path === '/logout' && $method === 'POST':
        if (csrf_ok()) {
            logout_user();
        }
        flash('success', 'You have been signed out.');
        redirect('/');
        break;

    case $path === '/account':
        $user = require_user();
        $stmt = db()->prepare('select * from shipments where user_id = ? order by created_at desc');
        $stmt->execute([$user['id']]);
        page('account', ['title' => 'Account', 'user' => $user, 'shipments' => $stmt->fetchAll()]);
        break;

    case $path === '/ship':
        coming_soon('Booking a shipment');
        break;

    case $path === '/quote':
        coming_soon('Live rate quotes');
        break;

    case $path === '/checkout':
        coming_soon('Checkout');
        break;

    case $path === '/admin':
        coming_soon('The operations console');
        break;

    default:
        not_found();
}
