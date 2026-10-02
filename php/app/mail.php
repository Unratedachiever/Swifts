<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/helpers.php';

/**
 * Minimal SMTP client (no Composer dependency).
 *
 * Supports plain, STARTTLS and implicit-TLS connections plus AUTH LOGIN, which
 * covers Mailpit locally and any hosted SMTP relay (Gmail, Zoho, cPanel) with
 * the same code path. Configured entirely through env vars — see
 * .env.base44-defaults for the local defaults.
 */
final class SmtpMailer
{
    public function __construct(private array $config)
    {
    }

    public function send(string $to, string $subject, string $html, string $text): void
    {
        $config = $this->config;
        $secure = $config['secure'];
        $remote = ($secure === 'ssl' ? 'ssl://' : '') . $config['host'] . ':' . $config['port'];

        $socket = @stream_socket_client($remote, $errno, $errstr, 15);
        if (!$socket) {
            throw new RuntimeException("Could not connect to SMTP server {$config['host']}:{$config['port']} ({$errstr})");
        }
        stream_set_timeout($socket, 15);

        try {
            $this->expect($socket, [220]);
            $this->command($socket, 'EHLO ' . $config['ehlo'], [250]);

            if ($secure === 'tls') {
                $this->command($socket, 'STARTTLS', [220]);
                if (!stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
                    throw new RuntimeException('SMTP STARTTLS negotiation failed.');
                }
                $this->command($socket, 'EHLO ' . $config['ehlo'], [250]);
            }

            if ($config['user'] !== '') {
                $this->command($socket, 'AUTH LOGIN', [334]);
                $this->command($socket, base64_encode($config['user']), [334]);
                $this->command($socket, base64_encode($config['pass']), [235]);
            }

            $this->command($socket, 'MAIL FROM:<' . $config['from'] . '>', [250]);
            $this->command($socket, 'RCPT TO:<' . $to . '>', [250, 251]);
            $this->command($socket, 'DATA', [354]);

            fwrite($socket, $this->buildMessage($to, $subject, $html, $text) . "\r\n.\r\n");
            $this->expect($socket, [250]);
            $this->command($socket, 'QUIT', [221]);
        } finally {
            fclose($socket);
        }
    }

    /** Assemble the MIME message (multipart/alternative: text + branded HTML). */
    private function buildMessage(string $to, string $subject, string $html, string $text): string
    {
        $boundary = 'swiftship_' . bin2hex(random_bytes(12));
        $fromName = $this->encodeHeader($this->config['from_name']);

        $headers = [
            'Date: ' . date('r'),
            'From: ' . $fromName . ' <' . $this->config['from'] . '>',
            'To: <' . $to . '>',
            'Subject: ' . $this->encodeHeader($subject),
            'Message-ID: <' . bin2hex(random_bytes(12)) . '@' . $this->config['message_host'] . '>',
            'MIME-Version: 1.0',
            'Content-Type: multipart/alternative; boundary="' . $boundary . '"',
        ];

        $body = [
            '--' . $boundary,
            'Content-Type: text/plain; charset=UTF-8',
            'Content-Transfer-Encoding: 8bit',
            '',
            $this->dotStuff($text),
            '--' . $boundary,
            'Content-Type: text/html; charset=UTF-8',
            'Content-Transfer-Encoding: 8bit',
            '',
            $this->dotStuff($html),
            '--' . $boundary . '--',
            '',
        ];

        return implode("\r\n", $headers) . "\r\n\r\n" . implode("\r\n", $body);
    }

    private function encodeHeader(string $value): string
    {
        return preg_match('/[^\x20-\x7E]/', $value) ? mb_encode_mimeheader($value, 'UTF-8') : $value;
    }

    /** A line consisting of a single "." would end DATA early. */
    private function dotStuff(string $body): string
    {
        $normalized = preg_replace("/\r\n|\r|\n/", "\r\n", $body) ?? $body;
        return preg_replace('/^\./m', '..', $normalized) ?? $normalized;
    }

    private function command($socket, string $command, array $expected): void
    {
        fwrite($socket, $command . "\r\n");
        $this->expect($socket, $expected);
    }

    private function expect($socket, array $expected): string
    {
        $response = '';
        while (($line = fgets($socket, 1024)) !== false) {
            $response .= $line;
            if (strlen($line) < 4 || $line[3] !== '-') {
                break;
            }
        }

        $code = (int) substr($response, 0, 3);
        if (!in_array($code, $expected, true)) {
            throw new RuntimeException('SMTP error: ' . trim($response));
        }
        return $response;
    }
}

function mail_config(): array
{
    $host = env('SMTP_HOST', 'mailpit') ?? 'mailpit';
    return [
        'transport' => strtolower(env('MAIL_TRANSPORT', 'smtp') ?? 'smtp'),
        'host' => $host,
        'port' => (int) (env('SMTP_PORT', '1025') ?? '1025'),
        'user' => env('SMTP_USER', '') ?? '',
        'pass' => env('SMTP_PASS', '') ?? '',
        'secure' => strtolower(env('SMTP_SECURE', '') ?? ''), // '', 'tls' or 'ssl'
        'from' => env('MAIL_FROM', 'no-reply@swiftship.test') ?? '',
        'from_name' => env('MAIL_FROM_NAME', BRAND_NAME) ?? BRAND_NAME,
        'ehlo' => parse_url(app_url(), PHP_URL_HOST) ?: 'localhost',
        'message_host' => parse_url(app_url(), PHP_URL_HOST) ?: 'swiftship.test',
    ];
}

/**
 * Send one message. With MAIL_TRANSPORT=log the message is written to
 * data/mail/ instead of sent — handy when no SMTP server is running.
 */
function send_mail(string $to, string $subject, string $html, string $text): void
{
    $config = mail_config();

    if ($config['transport'] === 'log') {
        $dir = dirname(db_path()) . '/mail';
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }
        file_put_contents(
            $dir . '/' . date('Ymd-His') . '-' . preg_replace('/[^a-z0-9]+/i', '-', $to) . '.html',
            $html
        );
        return;
    }

    if ($config['transport'] === 'mail') {
        $headers = implode("\r\n", [
            'MIME-Version: 1.0',
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . $config['from_name'] . ' <' . $config['from'] . '>',
        ]);
        mail($to, $subject, $html, $headers);
        return;
    }

    (new SmtpMailer($config))->send($to, $subject, $html, $text);
}

/**
 * The branded welcome email a new account receives.
 *
 * Never let a mail failure break sign-up: the account is already created, so we
 * report the problem in the log and let the request continue.
 */
function send_welcome_email(array $user): bool
{
    $data = [
        'name' => (string) $user['name'],
        'email' => (string) $user['email'],
        'first_name' => trim(explode(' ', (string) $user['name'])[0]),
        'account_url' => absolute_url('/account'),
        'track_url' => absolute_url('/track'),
        'logo_url' => absolute_url('/assets/brand/mark.png'),
        'home_url' => absolute_url('/'),
    ];

    try {
        send_mail(
            (string) $user['email'],
            'Welcome to SwiftShip — your account is ready',
            view('emails/welcome-html', $data),
            view('emails/welcome-text', $data)
        );
        return true;
    } catch (Throwable $error) {
        error_log('[mail] welcome email failed: ' . $error->getMessage());
        return false;
    }
}
