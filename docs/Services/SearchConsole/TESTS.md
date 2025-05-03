# SearchConsoleApi - Testing Instructions

This document provides instructions for running unit tests for the `SearchConsoleApi` class in the `anibalealvarezs/google-api` package. The `SearchConsoleApi` class extends the `GoogleApi` class (which inherits from `OAuthV2Client` in the `anibalealvarezs/api-client-skeleton` package) to interact with the Google Search Console API, offering methods for managing sites, sitemaps, search analytics queries, and URL inspections. The tests, located in `tests/Unit/Services/SearchConsoleApiTest.php`, verify the constructor’s OAuth v2 configuration and the functionality of key public methods.

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
- **Mock Server**: Tests use mocked HTTP responses via Guzzle’s `MockHandler`. No external server is required, as tests use mock-friendly URLs (e.g., `https://www.googleapis.com/webmasters/v3/` for Search Console API and sitemap URL checks).

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

This will install the `anibalealvarezs/google-api` package, its dependencies (`anibalealvarezs/api-client-skeleton`, `anibalealvarezs/api-skeleton`), PHPUnit, Faker, Guzzle, and other required dependencies.

## Test Setup

The `SearchConsoleApiTest.php` test class, located in `tests/Unit/Services/SearchConsoleApiTest.php`, verifies the `SearchConsoleApi` class. The test class uses:

- **Faker**: Generates random test data (e.g., UUIDs for client IDs, tokens, site URLs).
- **Guzzle MockHandler**: Simulates HTTP responses for API calls (e.g., token refresh, Search Console API endpoints, sitemap URL checks).
- **PHPUnit**: Provides the testing framework for assertions and test execution.

The `setUp` method initializes the test environment by:
- Creating a Faker instance for generating test data.
- Setting a Search Console-specific base URL (`https://www.googleapis.com/webmasters/v3/`) and redirect URL (`https://example.com/callback`).
- Defining OAuth v2 parameters, including scopes (`https://www.googleapis.com/auth/webmasters`), client ID, client secret, refresh token, user ID, and token.
- Preparing mock site and sitemap URLs (e.g., `https://example.com`, `https://example.com/sitemap.xml`) for testing API responses.

No additional configuration is required, as the tests are self-contained and do not rely on external services.

## Running the Tests

To run the tests for the `SearchConsoleApi` class, use the following command from the root directory of your project:

```bash
./vendor/bin/phpunit --verbose tests/Unit/Services/SearchConsoleApiTest.php
```

### Command Breakdown
- `./vendor/bin/phpunit`: Executes the PHPUnit binary installed via Composer.
- `--verbose`: Enables verbose output, displaying detailed information about each test case, including test names and results.
- `tests/Unit/Services/SearchConsoleApiTest.php`: Specifies the test file for the `SearchConsoleApi` class.

### Expected Output
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

### Troubleshooting
If tests fail, check the following:
- **Dependencies**: Ensure all dependencies are installed (`composer install`) and match the required versions. Verify that `anibalealvarezs/google-api`, `anibalealvarezs/api-client-skeleton`, and `anibalealvarezs/api-skeleton` are correctly installed.
- **PHP Version**: Confirm PHP 8.1 or higher is used (`php -v`).
- **Composer Autoloader**: Run `composer dump-autoload` to regenerate the autoloader if classes (e.g., `ApiRequestException`) are not found.
- **File Path**: Ensure the test file is located at `tests/Unit/Services/SearchConsoleApiTest.php`. Adjust the PHPUnit command if the directory structure differs.
- **Verbose Output**: The `--verbose` flag provides detailed error messages, including stack traces for failed assertions or exceptions (e.g., `InvalidArgumentException` for invalid URLs, `ApiRequestException` for HTTP issues).
- **Mock Responses**: Ensure the `MockHandler` responses align with the expected request flow (e.g., token refresh, API calls, sitemap checks). Check test logs for response mismatches.

## Test Coverage

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

To generate a test coverage report:

```bash
./vendor/bin/phpunit --verbose --coverage-text tests/Unit/Services/SearchConsoleApiTest.php
```

For an HTML report:

```bash
./vendor/bin/phpunit --verbose --coverage-html coverage tests/Unit/Services/SearchConsoleApiTest.php
```

This generates an HTML report in the `coverage/` directory, detailing coverage for the `SearchConsoleApi` class (requires PHPUnit to be configured with coverage reporting).

## Additional Notes
- **Mocking**: Tests use Guzzle’s `MockHandler` to simulate API responses, including token refresh, Search Console API endpoints, and sitemap URL checks (via `Helpers::isSitemapUrl`), ensuring no real network calls are made.
- **Isolation**: Each test method is isolated, with the `setUp` method resetting the test environment to prevent state leakage.
- **Extending Tests**: To add tests for additional `SearchConsoleApi` methods or other services (e.g., Google Analytics, Drive), create new test classes in `tests/Unit/Services/` or extend `SearchConsoleApiTest.php`.
- **PHPUnit Configuration**: Ensure a `phpunit.xml` file in the project root includes the test directory:
  ```xml
  <phpunit>
      <testsuites>
          <testsuite name="SearchConsoleApi Tests">
              <directory>tests/Unit/Services</directory>
          </testsuite>
      </testsuites>
      <php>
          <includePath>vendor/autoload.php</includePath>
      </php>
  </phpunit>
  ```
- **Directory Structure**: The test file is located at `tests/Unit/Services/SearchConsoleApiTest.php` to match the namespace `Tests\Unit\Services`. Adjust the path in the PHPUnit command if your project uses a different structure.
- **Dependencies**: The `anibalealvarezs/api-client-skeleton` and `anibalealvarezs/api-skeleton` packages are automatically included as dependencies of `anibalealvarezs/google-api`. Ensure they are installed correctly via Composer.
- **Sitemap Validation**: The `addSitemap` tests use a separate mocked Guzzle client for sitemap URL validation (`Helpers::isSitemapUrl`), ensuring no real HTTP requests are made to external URLs.
- **Laravel Context**: The project appears to be a Laravel application (based on the path `D:\laragon\www\google-api-anibal`). Ensure the test environment is configured to prevent real HTTP requests and that all dependencies are properly integrated.