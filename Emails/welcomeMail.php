<?php
declare(strict_types=1);

/**
 * Standalone helper function to prepare a Welcome Email
 * request for Send.com's Email API (usesend.com).
 *
 * - Reads endpoint and API key from .env using env()
 * - Returns an array with 'endpoint', 'headers', and 'body'
 * - Does NOT perform any network request
 */
function welcomeEmail(string $toEmail, ?string $toName = null): array
{
    // Ensure env() is defined (dbconnect.php already provides it in the app)
    if (!function_exists('env')) {
        function env(string $key, mixed $default = null): mixed {
            $value = $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key);
            if ($value === false || $value === null || $value === '') {
                return $default;
            }
            return $value;
        }
    }

    // Build a base URL for absolute asset links (logo)
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
    $host   = $_SERVER['HTTP_HOST'] ?? 'localhost:8000';
    $base   = rtrim((string) env('APP_DOMAIN', "$scheme://$host"), '/');
    $logo   = $base . '/images/dola.png';

    // Config from .env
    $endpoint = (string) env('SEND_EMAIL_ENDPOINT', '');
    $apiKey   = (string) env('SEND_API_KEY', '');
    $from     = (string) env('EMAIL_FROM', 'no-reply@dola.local');
    $fromName = (string) env('EMAIL_FROM_NAME', 'Dola');

    // Content
    $subject = 'Bienvenue sur Dola';

    $text = "Bienvenue sur Dola\n\nMerci de vous être inscrit !";

    // Simple, clean HTML email (works well across email clients)
    $html = '<!DOCTYPE html>'
          . '<html lang="fr"><head><meta charset="UTF-8">'
          . '<meta name="viewport" content="width=device-width, initial-scale=1">'
          . '<title>Bienvenue sur Dola</title>'
          . '</head><body style="margin:0;padding:0;background:#f6f6f6;font-family:system-ui,-apple-system,Segoe UI,Roboto,Inter,Helvetica,Arial,sans-serif;color:#111;">'
          . '<table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f6f6f6;padding:24px 0;">'
          . '<tr><td align="center">'
          . '  <table role="presentation" width="560" cellspacing="0" cellpadding="0" style="max-width:560px;width:100%;background:#ffffff;border:1px solid #111;border-radius:16px;overflow:hidden;">'
          . '    <tr><td style="padding:32px 24px 0;text-align:center;">'
          . '      <img src="' . htmlspecialchars($logo, ENT_QUOTES, 'UTF-8') . '" alt="Dola" width="56" height="56" style="width:56px;height:56px;border-radius:12px;border:1px solid #111;object-fit:contain;" />'
          . '      <h1 style="margin:16px 0 0;font-size:22px;line-height:1.3;">Bienvenue sur Dola</h1>'
          . '      <p style="margin:8px 0 0;color:#444;">Nous sommes ravis de vous compter parmi nous.</p>'
          . '    </td></tr>'
          . '    <tr><td style="padding:24px 24px 0;">'
          . '      <p style="margin:0 0 8px;">Bonjour ' . htmlspecialchars($toName ?? $toEmail, ENT_QUOTES, 'UTF-8') . ',</p>'
          . '      <p style="margin:0 0 16px;color:#333;">Votre compte est prêt. Commencez à créer votre boutique en quelques secondes.</p>'
          . '      <div style="text-align:center;margin:24px 0;">'
          . '        <a href="' . htmlspecialchars($base, ENT_QUOTES, 'UTF-8') . '" '
          . '           style="display:inline-block;background:#000;color:#fff;border:1px solid #000;padding:12px 20px;border-radius:12px;text-decoration:none;font-weight:600">'
          . '           Ouvrir Dola'
          . '        </a>'
          . '      </div>'
          . '      <p style="margin:0;color:#666;font-size:13px;">Si vous n’êtes pas à l’origine de cette inscription, vous pouvez ignorer cet email.</p>'
          . '    </td></tr>'
          . '    <tr><td style="padding:24px;text-align:center;background:#fafafa;border-top:1px solid #111;">'
          . '      <p style="margin:0;color:#555;font-size:12px;">© ' . date('Y') . ' Dola</p>'
          . '    </td></tr>'
          . '  </table>'
          . '</td></tr>'
          . '</table>'
          . '</body></html>';

    // Request body compatible with many email APIs
    $body = [
        'from' => [
            'email' => $from,
            'name'  => $fromName,
        ],
        'to' => [[
            'email' => $toEmail,
            'name'  => $toName ?? $toEmail,
        ]],
        'subject' => $subject,
        'text'    => $text,
        'html'    => $html,
    ];

    $headers = [
        'Content-Type: application/json',
        $apiKey !== '' ? ('Authorization: Bearer ' . $apiKey) : null,
    ];
    $headers = array_values(array_filter($headers, static fn($v) => $v !== null));

    return [
        'endpoint' => $endpoint,
        'headers'  => $headers,
        'body'     => $body,
    ];
}

