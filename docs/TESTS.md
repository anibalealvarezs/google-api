# GoogleApi - Testing Instructions

This document provides instructions for running unit and integration tests for the `GoogleApi` class in the `anibalealvarezs/google-api` package. The `GoogleApi` class extends the `OAuthV2Client` from the `anibalealvarezs/api-client-skeleton` package to configure OAuth v2 authentication for Google APIs. The unit tests, located in `tests/Unit/GoogleApiTest.php`, verify the constructor's behavior, including parameter validation and OAuth v2 configuration. The integration tests, located in `tests/Integration/GoogleApiLiveTest.php`, verify live API interactions, such as refreshing access tokens.

---

Tests for specific services are documented here:

- [BigQuery](Services/BigQuery/TESTS.md)
- [Drive](Services/Drive/TESTS.md)
- [Gmail](Services/Gmail/TESTS.md)
- [SearchConsole](Services/SearchConsole/TESTS.md)
- [Sheets](Services/Sheets/TESTS.md)
- [Slides](Services/Slides/TESTS.md)

---

## Prerequisites

Before running the tests, ensure the following requirements are met:

- **PHP**: Version 8.1 or higher, as required by the `anibalealvarezs/google-api` and `anibalealvarezs/api-client-skeleton` packages.
- **Composer**: Installed globally or locally to manage dependencies.
- **PHPUnit**: Installed via Composer as a development dependency.
- **Dependencies**: The `anibalealvarezs/google-api` package and its dependency `anibalealvarezs/api-client-skeleton` must be installed via Composer.
- **Mock Server (Unit Tests)**: Unit tests use mocked HTTP responses via Guzzle's `MockHandler`. No external server is required, as tests use mock-friendly URLs (e.g., `https://www.googleapis.com`).
- **API Credentials (Integration Tests)**: Integration tests require valid Google API credentials, including:
  - Google Client ID
  - Google Client Secret
  - Google Refresh Token
  - Google User ID
  - Google Redirect URI
  - Google Token Path (Optional: path to save/load tokens)
  - Search Console scope (`https://www.googleapis.com/auth/webmasters`)
    These should be configured in your application's configuration.

---

## Installation

...

```php
return [
    'google_client_id' => env('GOOGLE_CLIENT_ID'),
    'google_client_secret' => env('GOOGLE_CLIENT_SECRET'),
    'google_refresh_token' => env('GOOGLE_REFRESH_TOKEN'),
    'google_user_id' => env('GOOGLE_USER_ID'),
    'google_redirect_uri' => env('GOOGLE_REDIRECT_URI'),
    'google_token_path' => env('GOOGLE_TOKEN_PATH', './config/google_tokens.json'),
    'search_console_scope' => 'https://www.googleapis.com/auth/webmasters',
];
```

Set these values in your `.env` file:

```
GOOGLE_CLIENT_ID=your-client-id
GOOGLE_CLIENT_SECRET=your-client-secret
GOOGLE_REFRESH_TOKEN=your-refresh-token
GOOGLE_USER_ID=your-user-id
GOOGLE_REDIRECT_URI=https://your-app.com/callback
GOOGLE_TOKEN_PATH=./config/google_tokens.json
```

---

## Running the Tests

### Unit Tests

To run the unit tests for the `GoogleApi` class:

```bash
./vendor/bin/phpunit --verbose tests/Unit/GoogleApiTest.php
```

#### Expected Output

When running the command, you will see output similar to the following (assuming all 12 tests pass):

```
PHPUnit 9.6.34 by Sebastian Bergmann and contributors.

Runtime:       PHP 8.3.12

............  12 / 12 (100%)

Time: 00:00.087, Memory: 8.00 MB

OK (12 tests, 58 assertions)
```

### Integration Tests

To run the integration tests for the `GoogleApi` class:

```bash
./vendor/bin/phpunit --verbose tests/Integration/GoogleApiLiveTest.php
```

#### Expected Output

When running the command with valid API credentials, you will see output similar to the following (assuming both tests pass):

```
PHPUnit 9.6.34 by Sebastian Bergmann and contributors.

Runtime:       PHP 8.3.12

..  2 / 2 (100%)

Time: 00:00.605, Memory: 6.00 MB

OK (2 tests, 6 assertions)
```

---

## Test Coverage

### Unit Tests

The `GoogleApiTest.php` class includes 12 test methods that cover:

- **Valid Configuration**: Verifies constructor settings.
- **Error Handling**: Tests for invalid or empty inputs.
- **Token Persistence**: Verifies that tokens are correctly saved to and loaded from the JSON file.
- **Multi-Service Isolation**: Verifies that different services (e.g. Sheets vs Slides) don't overwrite each other's tokens.

### Integration Tests

The `GoogleApiLiveTest.php` class includes 2 test methods:

- **Token Refresh**: Tests the `getNewToken` method by making a real HTTP request.
- **Live Persistence**: Verifies that a live refreshed token is automatically written to the configured JSON file.
