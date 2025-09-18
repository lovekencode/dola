<?php
declare(strict_types=1);

/**
 * Simple email service helper to prepare requests
 * for Send.com's Email API (usesend.com).
 *
 * This file does NOT perform any network calls.
 * It only builds the request payload and headers,
 * reading configuration from .env via the env() helper.
 */
class EmailService
{
    /**
     * Prepare a Welcome email request for Send.com's Email API.
     *
     * Docs: https://docs.usesend.com/api-reference/emails/send-email
     *
     * This function only returns a structured array containing:
     * - endpoint (string)
     * - headers (array)
     * - body (array)
     *
     * No HTTP request is made here.
     */
    public static function WelcomeEmail(string $toEmail, ?string $toName = null): array
    {
        // Ensure env() is available (defined in database/dbconnect.php)
        if (!function_exists('env')) {
            // Fallback minimal env() to avoid fatal if included standalone
            function env(string $key, mixed $default = null): mixed {
                $value = $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key);
                if ($value === false || $value === null || $value === '') {
                    return $default;
                }
                return $value;
            }
        }

        // Configuration from .env
        $endpoint = (string) env('SEND_EMAIL_ENDPOINT', '');
        $apiKey   = (string) env('SEND_API_KEY', '');

        // Optional defaults
        $fromEmail = (string) env('EMAIL_FROM', 'no-reply@dola.local');
        $fromName  = (string) env('EMAIL_FROM_NAME', 'Dola');

        // Compose a basic welcome email
        $subject = 'Bienvenue sur Dola 🎉';
        $html    = '<!DOCTYPE html><html><body>'
                 . '<h1>Bienvenue sur Dola</h1>'
                 . '<p>Merci de vous être inscrit. Nous sommes ravis de vous compter parmi nous.</p>'
                 . '</body></html>';
        $text    = "Bienvenue sur Dola\nMerci de vous être inscrit !";

        // Build request body as per Send.com style (generic structure)
        // Adapt keys to your exact provider schema if needed.
        $body = [
            'from' => [
                'email' => $fromEmail,
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

        // Typical Bearer header used by many providers
        $headers = [
            'Content-Type: application/json',
            $apiKey !== '' ? ('Authorization: Bearer ' . $apiKey) : null,
        ];
        // Remove nulls if api key is missing
        $headers = array_values(array_filter($headers, static fn($v) => $v !== null));

        return [
            'endpoint' => $endpoint,
            'headers'  => $headers,
            'body'     => $body,
        ];
    }
}

