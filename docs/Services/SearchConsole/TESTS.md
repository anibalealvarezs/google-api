# SearchConsoleApi - Testing Instructions

This document provides instructions for running unit and integration tests for the `SearchConsoleApi` class in the `anibalealvarezs/google-api` package. The `SearchConsoleApi` class extends the `GoogleApi` class (which inherits from `OAuthV2Client` in the `anibalealvarezs/api-client-skeleton` package) to interact with the Google Search Console API, offering methods for managing sites, sitemaps, search analytics queries, and URL inspections. The unit tests, located in `tests/Unit/Services/SearchConsoleApiTest.php`, verify the constructor’s OAuth v2 configuration and the functionality of key public methods. The integration tests, located in `tests/Integration/Services/SearchConsoleApiLiveTest.php`, verify live API interactions, such as retrieving sites, sitemaps, search analytics, and URL inspection results.

---
**NOTE**:
- Information about the `GoogleApi` class can be found in the [GoogleApi](../../../README.md) documentation.
- Information about the `GoogleApiTest` class can be found in the [GoogleApiTest](../../TESTS.md) documentation.
- Information about the `SearchConsoleApi` class can be found in the [SearchConsoleApi](README.md) documentation.
---

## Prerequisites

Before running the tests, ensure the following requirements are met:

- **PHP**: Version 8.1 or higher, as required by the `anibalealvarezs/google-api` and `anibalealvarezs/api-client-skeleton` packages.
- **Composer**: Installed globally or locally to manage dependencies.
- **PHPUnit**: Installed via Composer as a development dependency.
- **Dependencies**: The `anibalealvarezs/google-api` package, its dependency `anibalealvarezs/api-client-skeleton`, and `anibalealvarezs/api-skeleton` (for `ApiRequestException`) must be installed via Composer.
- **Mock Server (Unit Tests)**: Unit tests use mocked HTTP responses via Guzzle’s `MockHandler`. No external server is required, as tests use mock-friendly URLs (e.g., `https://www.googleapis.com/webmasters/v3/` for Search Console API and sitemap URL checks).
- **API Credentials (Integration Tests)**: Integration tests require valid Google API credentials, including:
  - Google Client ID
  - Google Client Secret
  - Google Refresh Token
  - Google User ID
  - Google Redirect URI
    These should be configured in your application's configuration (e.g., `config/app.php` in Laravel).
- **Internet Access (Integration Tests)**: Integration tests make real HTTP requests to Google APIs (e.g., `https://www.googleapis.com/webmasters/v3/`, `https://searchconsole.googleapis.com/`).
- **Test Data (Integration Tests)**: Integration tests use specific site URLs (e.g., `sc-domain:anibalalvarez.com`, `sc-domain:loquesea.com`), sitemap URLs (e.g., `https://anibalalvarez.com/sitemap_index.xml`), and an inspection URL (e.g., `https://anibalalvarez.com/ecommerce-conversion-optimization-businesses/`). Ensure the account associated with the credentials has access to these sites in Google Search Console, or update the test data in `SearchConsoleApiLiveTest.php`.

## Installation

To set up the tests, add PHPUnit and required dependencies to your project’s `composer.json`:

```json
{
    "require": {
        "php": ">=8.1",
        "anibalealvarezs/google-api": "@dev",
        "anibalealvarezs/api-skeleton": "@dev"
    },
    "repositories": [
        {
            "type": "composer",
            "url": "https://satis.anibalalvarez.com/"
        }
    ],
    "require-dev": {
        "phpunit/phpunit": "^9.5",
        "fakerphp/faker": "^1.23"
    }
}
```

Run the following command to install the package, its dependencies, and PHPUnit:

```bash
composer install
```

For integration tests, ensure your application's configuration includes valid Google API credentials. In a Laravel project, these might be defined in `config/app.php` or a custom configuration file, accessible via the `app_config()` helper. Example configuration:

```php
return [
    'google_client_id' => env('GOOGLE_CLIENT_ID'),
    'google_client_secret' => env('GOOGLE_CLIENT_SECRET'),
    'google_refresh_token' => env('GOOGLE_REFRESH_TOKEN'),
    'google_user_id' => env('GOOGLE_USER_ID'),
    'google_redirect_uri' => env('GOOGLE_REDIRECT_URI'),
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
The `SearchConsoleApiTest.php` test class, located in `tests/Unit/Services/SearchConsoleApiTest.php`, verifies the `SearchConsoleApi` class. The test class uses:

- **Faker**: Generates random test data (e.g., UUIDs for client IDs, tokens, site URLs).
- **Guzzle MockHandler**: Simulates HTTP responses for API calls (e.g., token refresh, Search Console API endpoints, sitemap URL checks).
- **PHPUnit**: Provides the testing framework for assertions and test execution.

The `setUp` method initializes the test environment by:
- Creating a Faker instance for generating test data.
- Setting a Search Console-specific base URL (`https://www.googleapis.com/webmasters/v3/`) and redirect URL (`https://example.com/callback`).
- Defining OAuth v2 parameters, including scopes (`https://www.googleapis.com/auth/webmasters`), client ID, client secret, refresh token, user ID, and token.
- Preparing mock site and sitemap URLs (e.g., `https://example.com`, `https://example.com/sitemap.xml`) for testing API responses.

No additional configuration is required, as the unit tests are self-contained and do not rely on external services.

### Integration Tests
The `SearchConsoleApiLiveTest.php` test class, located in `tests/Integration/Services/SearchConsoleApiLiveTest.php`, verifies live interactions with the Google Search Console API. The test class uses:

- **PHPUnit**: Provides the testing framework for assertions and test execution.
- **Application Configuration**: Loads Google API credentials via the `app_config()` helper (assumed to be a Laravel helper or custom function).
- **Guzzle**: Makes real HTTP requests to Google APIs (e.g., Search Console API endpoints, sitemap submissions).

The `setUp` method initializes the test environment by:
- Loading configuration from `app_config()` (e.g., client ID, client secret, refresh token, user ID, redirect URI).
- Defining test data for site URLs (`sc-domain:anibalalvarez.com`, `sc-domain:loquesea.com`), sitemap URLs (`https://anibalalvarez.com/sitemap_index.xml`, `https://anibalalvarez.com/geo-sitemap.xml`), and an inspection URL (`https://anibalalvarez.com/ecommerce-conversion-optimization-businesses/`).
- Instantiating a `SearchConsoleApi` object with the loaded credentials and default Search Console settings.

Integration tests require valid API credentials, internet access, and access to the specified sites in Google Search Console.

## Running the Tests

### Unit Tests
To run the unit tests for the `SearchConsoleApi` class, use the following command from the root directory of your project:

```bash
./vendor/bin/phpunit --verbose tests/Unit/Services/SearchConsoleApiTest.php
```

#### Command Breakdown
- `./vendor/bin/phpunit`: Executes the PHPUnit binary installed via Composer.
- `--verbose`: Enables verbose output, displaying detailed information about each test case, including test names and results.
- `tests/Unit/Services/SearchConsoleApiTest.php`: Specifies the test file for the `SearchConsoleApi` class.

#### Expected Output
When running the command, you will see output similar to the following (assuming all 17 tests pass):

```
PHPUnit 9.6.22 by Sebastian Bergmann and contributors.

Runtime:       PHP 8.3.12

.................  17 / 17 (100%)

Time: 00:00.090, Memory: 12.00 MB

OK (17 tests, 78 assertions)
```

- The dots (`.`) represent successful test cases.
- The summary indicates the total number of tests (17), execution time, memory usage, and any failures or errors.
- With `--verbose`, additional details about each test method (e.g., `testConstructorWithValidParameters`, `testGetSitesSuccess`) will be displayed.

### Integration Tests
To run the integration tests for the `SearchConsoleApi` class, use the following command from the root directory of your project:

```bash
./vendor/bin/phpunit --verbose tests/Integration/Services/SearchConsoleApiLiveTest.php
```

#### Command Breakdown
- `./vendor/bin/phpunit`: Executes the PHPUnit binary installed via Composer.
- `--verbose`: Enables verbose output, displaying detailed information about each test case.
- `tests/Integration/Services/SearchConsoleApiLiveTest.php`: Specifies the test file for the `SearchConsoleApi` live tests.

#### Expected Output
When running the command with valid API credentials and access to the test sites, you will see output similar to the following (assuming all 8 tests pass):

```
PHPUnit 9.6.22 by Sebastian Bergmann and contributors.

Runtime:       PHP 8.3.12

........  8 / 8 (100%)

Time: 00:00.300, Memory: 10.00 MB

OK (8 tests, 15 assertions)
```

- The dots (`.`) represent successful test cases.
- The summary indicates the total number of tests (8), execution time, memory usage, and any failures or errors.
- With `--verbose`, details about each test method (e.g., `testGetSites`, `testAddAndRemoveSite`) will be displayed.

### Troubleshooting
If tests fail, check the following:

#### Unit Tests
- **Dependencies**: Ensure all dependencies are installed (`composer install`) and match the required versions. Verify that `anibalealvarezs/google-api`, `anibalealvarezs/api-client-skeleton`, and `anibalealvarezs/api-skeleton` are correctly installed.
- **PHP Version**: Confirm PHP 8.1 or higher is used (`php -v`).
- **Composer Autoloader**: Run `composer dump-autoload` to regenerate the autoloader if classes (e.g., `ApiRequestException`) are not found.
- **File Path**: Ensure the test file is located at `tests/Unit/Services/SearchConsoleApiTest.php`. Adjust the PHPUnit command if the directory structure differs.
- **Verbose Output**: The `--verbose` flag provides detailed error messages, including stack traces for failed assertions or exceptions (e.g., `InvalidArgumentException` for invalid URLs, `ApiRequestException` for HTTP issues).
- **Mock Responses**: Ensure the `MockHandler` responses align with the expected request flow (e.g., token refresh, API calls, sitemap checks). Check test logs for response mismatches.

#### Integration Tests
- **API Credentials**: Verify that Google API credentials (client ID, client secret, refresh token, user ID, redirect URI) are correctly set in your configuration (e.g., `.env` file). Check for typos or missing values.
- **Site Access**: Ensure the Google account associated with the credentials has access to the test sites (`sc-domain:anibalalvarez.com`, `sc-domain:loquesea.com`) in Google Search Console. If not, update the test data in `SearchConsoleApiLiveTest.php` to use accessible sites.
- **Sitemap URLs**: Confirm that the sitemap URLs (`https://anibalalvarez.com/sitemap_index.xml`, `https://anibalalvarez.com/geo-sitemap.xml`) are valid and accessible. If they are not, update the test data or ensure the site supports these sitemaps.
- **Internet Access**: Ensure the test environment has internet access to reach Google APIs (e.g., `https://www.googleapis.com/webmasters/v3/`, `https://searchconsole.googleapis.com/`).
- **Configuration Access**: Confirm the `app_config()` helper returns the expected configuration array. If using Laravel, ensure the configuration file (e.g., `config/app.php`) is correctly set up.
- **Guzzle Errors**: If a `GuzzleException` is thrown (e.g., 401 Unauthorized, 403 Forbidden), check the API credentials, ensure the refresh token is valid and not revoked, and verify the account’s permissions for the test sites.
- **Verbose Output**: The `--verbose` flag provides detailed error messages, including stack traces for exceptions (e.g., `GuzzleException` for HTTP issues or `Exception` for configuration errors).
- **Laravel Context**: If using Laravel, ensure the application is bootstrapped correctly for tests (e.g., `php artisan config:cache` may be needed if configuration is cached).

## Test Coverage

### Unit Tests
The `SearchConsoleApiTest.php` class, located in `tests/Unit/Services/SearchConsoleApiTest.php`, includes 17 test methods that cover the `SearchConsoleApi` class’s constructor and key public methods for interacting with the Google Search Console API. The tests include:

- **Constructor Validation**:
  - `testConstructorWithValidParameters`: Verifies that the constructor sets the base URL (`https://www.googleapis.com/webmasters/v3/`), OAuth v2 URLs, client credentials, scopes, token, headers, and Guzzle client.
  - `testConstructorWithEmptyClientId`: Ensures an `InvalidArgumentException` is thrown for an empty client ID.
  - `testConstructorWithDefaultScopes`: Confirms the default scope is `https://www.googleapis.com/auth/webmasters` when none are provided.

- **Site Management**:
  - `testGetSitesSuccess`: Tests retrieving the list of sites via a `GET` request, including token refresh handling.
  - `testGetSiteSuccess`: Verifies retrieving a specific site’s details.
  - `testGetSiteInvalidSiteUrl`: Confirms an `InvalidArgumentException` for an invalid site URL.
  - `testAddSiteSuccess`: Tests adding a site with a valid URL.
  - `testRemoveSiteSuccess`: Verifies removing a site.

- **Search Analytics**:
  - `testGetSearchQueryResultsSuccess`: Tests querying search analytics data with dimensions and date ranges.
  - `testGetSearchQueryResultsInvalidSiteUrl`: Ensures an `InvalidArgumentException` for an invalid site URL.
  - `testGetAllSearchQueryResultsSuccess`: Verifies fetching all search analytics rows via pagination.

- **Sitemap Management**:
  - `testGetSitemapsSuccess`: Tests retrieving the list of sitemaps for a site.
  - `testGetSitemapSuccess`: Verifies retrieving a specific sitemap’s details.
  - `testAddSitemapSuccess`: Tests submitting a valid sitemap, with mocked sitemap URL validation.
  - `testAddSitemapInvalidSitemapUrl`: Ensures an `InvalidArgumentException` for an invalid sitemap URL.
  - `testRemoveSitemapSuccess`: Verifies removing a sitemap.

- **URL Inspection**:
  - `testInspectUrlSuccess`: Tests inspecting a URL for indexing and crawling information.
  - `testInspectUrlRestoresBaseUrl`: Ensures the base URL is restored after inspection (which uses a different endpoint).

- **Error Handling**:
  - `testGuzzleExceptionHandling`: Verifies that API errors (simulated via `GuzzleException`) are wrapped in `ApiRequestException`.

### Integration Tests
The `SearchConsoleApiLiveTest.php` class, located in `tests/Integration/Services/SearchConsoleApiLiveTest.php`, includes 8 test methods that verify live interactions with the Google Search Console API:

- **Site Management**:
  - `testGetSites`: Tests retrieving the list of sites, verifying the response is an array with a `siteEntry` key.
  - `testGetSite`: Tests retrieving details for a specific site (`sc-domain:anibalalvarez.com`), verifying the response is an array with a `siteUrl` key.
  - `testAddAndRemoveSite`: Tests adding and removing a test site (`sc-domain:loquesea.com`), verifying both operations return an empty response (indicating success).

- **Sitemap Management**:
  - `testGetSitemaps`: Tests retrieving the list of sitemaps for a site (`sc-domain:anibalalvarez.com`), verifying the response is an array.
  - `testGetSitemap`: Tests retrieving details for a specific sitemap (`https://anibalalvarez.com/sitemap_index.xml`), verifying the response is an array with a `path` key.
  - `testAddAndDeleteSitemap`: Tests adding and removing a test sitemap (`https://anibalalvarez.com/geo-sitemap.xml`), verifying both operations return an empty response.

- **Search Analytics**:
  - `testGetSearchQueryResults`: Tests querying search analytics data for a site with a 7-day date range and `query` dimension, verifying the response is an array with a `rows` key.

- **URL Inspection**:
  - `testGetInspectionResults`: Tests inspecting a URL (`https://anibalalvarez.com/ecommerce-conversion-optimization-businesses/`), verifying the response is an array with an `inspectionResult` key.

To generate a test coverage report for unit tests:

```bash
./vendor/bin/phpunit --verbose --coverage-text tests/Unit/Services/SearchConsoleApiTest.php
```

For an HTML report:

```bash
./vendor/bin/phpunit --verbose --coverage-html coverage tests/Unit/Services/SearchConsoleApiTest.php
```

For integration tests, coverage can be generated similarly:

```bash
./vendor/bin/phpunit --verbose --coverage-text tests/Integration/Services/SearchConsoleApiLiveTest.php
```

This generates reports detailing coverage for the `SearchConsoleApi` class (requires PHPUnit to be configured with coverage reporting). Note that integration test coverage may be limited due to external API dependencies.

## Additional Notes
- **Mocking (Unit Tests)**: Unit tests use Guzzle’s `MockHandler` to simulate API responses, including token refresh, Search Console API endpoints, and sitemap URL checks (via `Helpers::isSitemapUrl`), ensuring no real network calls are made.
- **Live API Calls (Integration Tests)**: Integration tests make real HTTP requests to Google APIs, requiring valid credentials, internet access, and access to the specified test sites. Use these tests cautiously in CI/CD environments to avoid rate limiting or credential exposure.
- **Isolation**: Each test method (unit and integration) is isolated, with the `setUp` method resetting the test environment to prevent state leakage.
- **Extending Tests**:
  - For unit tests, add tests for new `SearchConsoleApi` methods in `SearchConsoleApiTest.php` or create new test classes in `tests/Unit/Services/`.
  - For integration tests, extend `SearchConsoleApiLiveTest.php` with methods to test other live API interactions (e.g., `getAllSearchQueryResults`). Ensure credentials and site access support the new tests.
- **PHPUnit Configuration**: Ensure a `phpunit.xml` file in the project root includes both unit and integration test directories:
  ```xml
  <phpunit>
      <testsuites>
          <testsuite name="SearchConsoleApi Tests">
              <directory>tests/Unit/Services</directory>
              <directory>tests/Integration/Services</directory>
          </testsuite>
      </testsuites>
      <php>
          <includePath>vendor/autoload.php</includePath>
      </php>
  </phpunit>
  ```
- **Directory Structure**: Unit tests are located at `tests/Unit/Services/SearchConsoleApiTest.php`, and integration tests at `tests/Integration/Services/SearchConsoleApiLiveTest.php`, matching the namespaces `Tests\Unit\Services` and `Tests\Integration\Services`. Adjust paths in the PHPUnit command if your project uses a different structure.
- **Dependencies**: The `anibalealvarezs/api-client-skeleton` and `anibalealvarezs/api-skeleton` packages are automatically included as dependencies of `anibalealvarezs/google-api`. Ensure they are installed correctly via Composer.
- **Sitemap Validation**: The `addSitemap` unit tests use a mocked Guzzle client for sitemap URL validation, while integration tests make real requests to validate sitemap URLs.
- **Laravel Context**: The project appears to be a Laravel application (based on the path `D:\laragon\www\google-api-anibal` and use of `app_config()`). Ensure the Laravel test environment is configured (e.g., `php artisan config:clear` if configuration issues arise).
- **Security**: Avoid committing sensitive API credentials (e.g., refresh token) or test data (e.g., site URLs) to version control. Use environment variables and secure storage for `.env` files.
- **Test Data Customization**: If the test sites or sitemap URLs in `SearchConsoleApiLiveTest.php` are not accessible, update the protected properties (e.g., `$testSiteUrl`, `$testSitemapUrl`) to use sites and sitemaps your account can access.