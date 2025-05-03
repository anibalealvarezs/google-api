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
  - Search Console scope (`https://www.googleapis.com/auth/webmasters`)
    These should be configured in your application's configuration (e.g., `config/app.php` in Laravel).
- **Internet Access (Integration Tests)**: Integration tests make real HTTP requests to Google APIs (e.g., `https://oauth2.googleapis.com/token` for token refresh).

## Installation

To set up the tests, add PHPUnit to the development dependencies in your project's `composer.json`:

```json
{
    "require": {
        "php": ">=8.1",
        "anibalealvarezs/google-api": "@dev"
    },
    "repositories": [
        {
            "type": "composer",
            "url": "https://satis.anibalalvarez.com/"
        }
    ],
    "require-dev": {
        "phpunit/phpunit": "^9.5"
    }
}
```

Run the following command to install the package, its dependencies, and PHPUnit:

```bash
composer install
```

This will install the `anibalealvarezs/google-api` package, its dependency `anibalealvarezs/api-client-skeleton`, PHPUnit, and other required dependencies (e.g., Guzzle).

For integration tests, ensure your application's configuration includes valid Google API credentials. In a Laravel project, these might be defined in `config/app.php` or a custom configuration file, accessible via the `app_config()` helper. Example configuration:

```php
return [
    'google_client_id' => env('GOOGLE_CLIENT_ID'),
    'google_client_secret' => env('GOOGLE_CLIENT_SECRET'),
    'google_refresh_token' => env('GOOGLE_REFRESH_TOKEN'),
    'google_user_id' => env('GOOGLE_USER_ID'),
    'google_redirect_uri' => env('GOOGLE_REDIRECT_URI'),
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
```

## Test Setup

### Unit Tests
The `GoogleApiTest.php` test class, located in `tests/Unit/GoogleApiTest.php`, verifies the `GoogleApi` class. The test class uses:

- **Faker**: Generates random test data (e.g., UUIDs for client IDs, refresh tokens, tokens).
- **Guzzle MockHandler**: Available for simulating HTTP responses, though not currently used as tests focus on the constructor.
- **PHPUnit**: Provides the testing framework for assertions and test execution.

The `setUp` method initializes the test environment by:
- Creating a Faker instance for generating test data.
- Setting a Google-specific base URL (`https://www.googleapis.com`) and redirect URL (`https://example.com/callback`).
- Defining OAuth v2 parameters, including a sample Google scope (`https://www.googleapis.com/auth/drive.readonly`), client ID, client secret, refresh token, user ID, and token.

No additional configuration is required, as the unit tests are self-contained and do not rely on external services.

### Integration Tests
The `GoogleApiLiveTest.php` test class, located in `tests/Integration/GoogleApiLiveTest.php`, verifies live interactions with Google APIs. The test class uses:

- **PHPUnit**: Provides the testing framework for assertions and test execution.
- **Application Configuration**: Loads Google API credentials via the `app_config()` helper (assumed to be a Laravel helper or custom function).
- **Guzzle**: Makes real HTTP requests to Google APIs (e.g., token refresh endpoint).

The `setUp` method initializes the test environment by:
- Loading configuration from `app_config()` (e.g., client ID, client secret, refresh token, user ID, redirect URI, scope).
- Instantiating a `GoogleApi` object with a Search Console-specific base URL (`https://www.googleapis.com/webmasters/v3/`) and the loaded credentials.

Integration tests require valid API credentials and internet access to communicate with Google APIs.

## Running the Tests

### Unit Tests
To run the unit tests for the `GoogleApi` class, use the following command from the root directory of your project:

```bash
./vendor/bin/phpunit --verbose tests/Unit/GoogleApiTest.php
```

#### Command Breakdown
- `./vendor/bin/phpunit`: Executes the PHPUnit binary installed via Composer.
- `--verbose`: Enables verbose output, displaying detailed information about each test case, including test names and results.
- `tests/Unit/GoogleApiTest.php`: Specifies the test file for the `GoogleApi` class.

#### Expected Output
When running the command, you will see output similar to the following (assuming all 10 tests pass):

```
PHPUnit 9.6.22 by Sebastian Bergmann and contributors.

Runtime:       PHP 8.3.12

..........  10 / 10 (100%)

Time: 00:00.044, Memory: 6.00 MB

OK (10 tests, 51 assertions)
```

- The dots (`.`) represent successful test cases.
- The summary indicates the total number of tests (10), execution time, memory usage, and any failures or errors.
- With `--verbose`, additional details about each test method (e.g., `testConstructorWithValidParameters`, `testConstructorWithEmptyBaseUrl`) will be displayed.

### Integration Tests
To run the integration tests for the `GoogleApi` class, use the following command from the root directory of your project:

```bash
./vendor/bin/phpunit --verbose tests/Integration/GoogleApiLiveTest.php
```

#### Command Breakdown
- `./vendor/bin/phpunit`: Executes the PHPUnit binary installed via Composer.
- `--verbose`: Enables verbose output, displaying detailed information about each test case.
- `tests/Integration/GoogleApiLiveTest.php`: Specifies the test file for the `GoogleApi` live tests.

#### Expected Output
When running the command with valid API credentials, you will see output similar to the following (assuming the single test passes):

```
PHPUnit 9.6.22 by Sebastian Bergmann and contributors.

Runtime:       PHP 8.3.12

.  1 / 1 (100%)

Time: 00:00.150, Memory: 8.00 MB

OK (1 test, 3 assertions)
```

- The dot (`.`) represents the successful test case.
- The summary indicates the total number of tests (1), execution time, memory usage, and any failures or errors.
- With `--verbose`, details about the `testItRefreshesAccessToken` method will be displayed.

### Troubleshooting
If tests fail, check the following:

#### Unit Tests
- **Dependencies**: Ensure all dependencies are installed (`composer install`) and match the required versions. Verify that `anibalealvarezs/google-api` and `anibalealvarezs/api-client-skeleton` are correctly installed.
- **PHP Version**: Confirm PHP 8.1 or higher is used (`php -v`).
- **Composer Autoloader**: Run `composer dump-autoload` to regenerate the autoloader if classes are not found.
- **File Path**: Ensure the test file is located at `tests/Unit/GoogleApiTest.php`. Adjust the PHPUnit command if the directory structure differs.
- **Verbose Output**: The `--verbose` flag provides detailed error messages, including stack traces for failed assertions or exceptions (e.g., `InvalidArgumentException` for invalid URLs or missing credentials).

#### Integration Tests
- **API Credentials**: Verify that Google API credentials (client ID, client secret, refresh token, user ID, redirect URI, scope) are correctly set in your configuration (e.g., `.env` file). Check for typos or missing values.
- **Internet Access**: Ensure the test environment has internet access to reach Google APIs (e.g., `https://oauth2.googleapis.com/token`).
- **Configuration Access**: Confirm the `app_config()` helper returns the expected configuration array. If using Laravel, ensure the configuration file (e.g., `config/app.php`) is correctly set up.
- **Guzzle Errors**: If a `GuzzleException` is thrown (e.g., 401 Unauthorized, 403 Forbidden), check the API credentials and ensure the refresh token is valid and not revoked.
- **Verbose Output**: The `--verbose` flag provides detailed error messages, including stack traces for exceptions (e.g., `GuzzleException` for HTTP issues or `Exception` for configuration errors).
- **Laravel Context**: If using Laravel, ensure the application is bootstrapped correctly for tests (e.g., `php artisan config:cache` may be needed if configuration is cached).

## Test Coverage

### Unit Tests
The `GoogleApiTest.php` class, located in `tests/Unit/GoogleApiTest.php`, includes 10 test methods that cover the `GoogleApi` class's constructor, which is the only public method. The tests ensure proper initialization and validation of OAuth v2 authentication parameters, including:
- **Valid Configuration**: Verifies that the constructor correctly sets the base URL, authentication URLs (auth URL, token URL, refresh auth URL), redirect URL, client ID, client secret, refresh token, user ID, scopes, token, authentication type, settings, and headers (e.g., `testConstructorWithValidParameters`).
- **Error Handling**: Tests for invalid or empty inputs, including:
  - Empty base URL (`testConstructorWithEmptyBaseUrl`).
  - Invalid base URL (`testConstructorWithInvalidBaseUrl`).
  - Empty client ID (`testConstructorWithEmptyClientId`).
  - Empty client secret (`testConstructorWithEmptyClientSecret`).
  - Empty refresh token (`testConstructorWithEmptyRefreshToken`).
  - Invalid redirect URL (`testConstructorWithInvalidRedirectUrl`).
- **Optional Parameters**: Tests for handling empty scopes, token, and user ID (e.g., `testConstructorWithEmptyScopes`, `testConstructorWithEmptyToken`, `testConstructorWithEmptyUserId`).

### Integration Tests
The `GoogleApiLiveTest.php` class, located in `tests/Integration/GoogleApiLiveTest.php`, includes 1 test method that verifies live API interactions:
- **Token Refresh**: The `testItRefreshesAccessToken` method tests the `getNewToken` method by making a real HTTP request to refresh an access token. It verifies that:
  - The returned token is a string.
  - The token is not empty.
  - The token does not contain error-related substrings (e.g., "error").

To generate a test coverage report for unit tests:

```bash
./vendor/bin/phpunit --verbose --coverage-text tests/Unit/GoogleApiTest.php
```

For an HTML report:

```bash
./vendor/bin/phpunit --verbose --coverage-html coverage tests/Unit/GoogleApiTest.php
```

For integration tests, coverage can be generated similarly:

```bash
./vendor/bin/phpunit --verbose --coverage-text tests/Integration/GoogleApiLiveTest.php
```

This generates reports detailing coverage for the `GoogleApi` class (requires PHPUnit to be configured with coverage reporting). Note that integration test coverage may be limited due to external API dependencies.

## Additional Notes
- **Mocking (Unit Tests)**: The unit tests focus on the constructor and do not require HTTP requests. Future unit tests for API endpoints can use Guzzle's `MockHandler` to simulate responses.
- **Live API Calls (Integration Tests)**: The integration tests make real HTTP requests to Google APIs, requiring valid credentials and internet access. Use these tests cautiously in CI/CD environments to avoid rate limiting or credential exposure.
- **Isolation**: Each test method (unit and integration) is isolated, with the `setUp` method resetting the test environment to prevent state leakage.
- **Extending Tests**:
  - For unit tests, extend `GoogleApiTest.php` with methods that mock `performRequest` calls for additional API endpoints (e.g., Drive, Sheets).
  - For integration tests, extend `GoogleApiLiveTest.php` with methods to test other live API interactions (e.g., `performRequest` for specific endpoints). Ensure credentials support the required scopes.
- **PHPUnit Configuration**: Ensure a `phpunit.xml` file in the project root includes both unit and integration test directories:
  ```xml
  <phpunit>
      <testsuites>
          <testsuite name="GoogleApi Tests">
              <directory>tests/Unit</directory>
              <directory>tests/Integration</directory>
          </testsuite>
      </testsuites>
      <php>
          <includePath>vendor/autoload.php</includePath>
      </php>
  </phpunit>
  ```
- **Directory Structure**: Unit tests are located at `tests/Unit/GoogleApiTest.php`, and integration tests at `tests/Integration/GoogleApiLiveTest.php`, matching the namespaces `Tests\Unit` and `Tests\Integration`. Adjust paths in the PHPUnit command if your project uses a different structure.
- **Dependency**: The `anibalealvarezs/api-client-skeleton` package is automatically included as a dependency of `anibalealvarezs/google-api`. Ensure it is installed correctly via Composer.
- **Laravel Context**: The integration tests use a Laravel-specific `app_config()` helper, suggesting a Laravel application. Ensure the Laravel test environment is configured (e.g., `php artisan config:clear` if configuration issues arise).
- **Security**: Avoid committing sensitive API credentials (e.g., refresh token) to version control. Use environment variables and secure storage for `.env` files.