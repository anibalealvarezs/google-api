<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Anibalealvarezs\GoogleApi\Google\Helpers\Helpers;
use Symfony\Component\Yaml\Yaml;

// ─── Config resolution ────────────────────────────────────────────────────────
// Priority:
//   1. CONFIG_FILE env var pointing to a YAML file
//   2. Default config/config.yaml (if it exists)
//   3. Individual GOOGLE_* environment variables (CI / deployment)
// ─────────────────────────────────────────────────────────────────────────────

$configFile = getenv('CONFIG_FILE') ?: __DIR__ . '/../config/config.yaml';

if (file_exists($configFile)) {
    $GLOBALS['app_config'] = Yaml::parseFile($configFile);
} else {
    // Build config from individual environment variables.
    // Scopes accept a space- or comma-separated string (e.g. set via a CI secret).
    $GLOBALS['app_config'] = [
        // ── Core Google OAuth ─────────────────────────────────────────────
        'google_client_id'      => getenv('GOOGLE_CLIENT_ID')      ?: null,
        'google_client_secret'  => getenv('GOOGLE_CLIENT_SECRET')  ?: null,
        'google_redirect_uri'   => getenv('GOOGLE_REDIRECT_URI')   ?: null,
        'google_token'          => getenv('GOOGLE_TOKEN')          ?: null,
        'google_refresh_token'  => getenv('GOOGLE_REFRESH_TOKEN')  ?: null,
        'google_user_id'        => getenv('GOOGLE_USER_ID')        ?: null,
        'google_token_path'     => getenv('GOOGLE_TOKEN_PATH')     ?: null,

        // ── Global scopes (space/comma-separated string or YAML list) ─────
        'google_scope' => Helpers::parseScopes(getenv('GOOGLE_SCOPE') ?: null),

        // ── Slides ────────────────────────────────────────────────────────
        'slides_scope'         => Helpers::parseScopes(getenv('SLIDES_SCOPE') ?: null),
        'slides_token'         => getenv('SLIDES_TOKEN')         ?: null,
        'slides_refresh_token' => getenv('SLIDES_REFRESH_TOKEN') ?: null,
        'slides_themes'        => getenv('SLIDES_THEMES')        ?: null,

        // ── Sheets ────────────────────────────────────────────────────────
        'sheets_scope'                => Helpers::parseScopes(getenv('SHEETS_SCOPE') ?: null),
        'sheets_token'                => getenv('SHEETS_TOKEN')                ?: null,
        'sheets_refresh_token'        => getenv('SHEETS_REFRESH_TOKEN')        ?: null,
        'sheets_themes'               => getenv('SHEETS_THEMES')               ?: null,
        'sheets_test_spreadsheet_id'  => getenv('SHEETS_TEST_SPREADSHEET_ID') ?: null,

        // ── Gmail ─────────────────────────────────────────────────────────
        'gmail_scope'         => Helpers::parseScopes(getenv('GMAIL_SCOPE') ?: null),
        'gmail_token'         => getenv('GMAIL_TOKEN')         ?: null,
        'gmail_refresh_token' => getenv('GMAIL_REFRESH_TOKEN') ?: null,

        // ── Drive ─────────────────────────────────────────────────────────
        'drive_scope'         => Helpers::parseScopes(getenv('DRIVE_SCOPE') ?: null),
        'drive_token'         => getenv('DRIVE_TOKEN')         ?: null,
        'drive_refresh_token' => getenv('DRIVE_REFRESH_TOKEN') ?: null,

        // ── BigQuery ──────────────────────────────────────────────────────
        'bigquery_scope'         => Helpers::parseScopes(getenv('BIGQUERY_SCOPE') ?: null),
        'bigquery_token'         => getenv('BIGQUERY_TOKEN')         ?: null,
        'bigquery_refresh_token' => getenv('BIGQUERY_REFRESH_TOKEN') ?: null,

        // ── Search Console ────────────────────────────────────────────────
        'search_console_scope'                  => Helpers::parseScopes(getenv('SEARCH_CONSOLE_SCOPE') ?: null),
        'search_console_token'                  => getenv('SEARCH_CONSOLE_TOKEN')                  ?: null,
        'search_console_refresh_token'          => getenv('SEARCH_CONSOLE_REFRESH_TOKEN')          ?: null,
        'search_console_test_site_url'          => getenv('SEARCH_CONSOLE_TEST_SITE_URL')          ?: null,
        'search_console_test_removable_site_url'=> getenv('SEARCH_CONSOLE_TEST_REMOVABLE_SITE_URL')?: null,
        'search_console_test_sitemap_url'       => getenv('SEARCH_CONSOLE_TEST_SITEMAP_URL')       ?: null,
        'search_console_test_removable_sitemap_url' => getenv('SEARCH_CONSOLE_TEST_REMOVABLE_SITEMAP_URL') ?: null,
        'search_console_test_inspect_url'       => getenv('SEARCH_CONSOLE_TEST_INSPECT_URL')       ?: null,
    ];

    // Warn so the developer knows which path was taken
    echo "ℹ️  config/config.yaml not found — loading credentials from environment variables.\n";
    echo "   Copy config/config-example.yaml to config/config.yaml for local testing.\n\n";
}

// ─── Helper ───────────────────────────────────────────────────────────────────

/**
 * Get a value from the resolved config array.
 *
 * @param string|null $key   Dot-notation NOT supported; use the flat key names.
 * @param mixed       $default
 * @return mixed
 */
function app_config(string $key = null, mixed $default = null): mixed
{
    $config = $GLOBALS['app_config'] ?? [];
    if ($key === null) {
        return $config;
    }
    return $config[$key] ?? $default;
}
