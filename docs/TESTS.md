# GoogleApi - Testing Instructions

This document provides instructions for running unit tests for the `GoogleApi` class in the `anibalealvarezs/google-api` package. The `GoogleApi` class extends the `OAuthV2Client` from the `anibalealvarezs/api-client-skeleton` package to configure OAuth v2 authentication for Google APIs. The tests, located in `tests/GoogleApiTest.php`, verify the constructor's behavior, including parameter validation and OAuth v2 configuration.

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
- **Mock Server**: Tests use mocked HTTP responses via Guzzle's `MockHandler`. No external server is required, as tests use mock-friendly URLs (e.g., `https://www.googleapis.com`).

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

This will install the `anibalealvarezs/google-api` package, its dependency `anibalealvarezs/api-client-skeleton`, PHPUnit, and other required dependencies (e.g., Guzzle, Faker).

## Test Setup

The `GoogleApiTest.php` test class, located in `tests/GoogleApiTest.php`, verifies the `GoogleApi` class. The test class uses:

- **Faker**: Generates random test data (e.g., UUIDs for client IDs, refresh tokens, tokens).
- **Guzzle MockHandler**: Available for simulating HTTP responses, though not currently used as tests focus on the constructor.
- **PHPUnit**: Provides the testing framework for assertions and test execution.

The `setUp` method initializes the test environment by:
- Creating a Faker instance for generating test data.
- Setting a Google-specific base URL (`https://www.googleapis.com`) and redirect URL (`https://example.com/callback`).
- Defining OAuth v2 parameters, including a sample Google scope (`https://www.googleapis.com/auth/drive.readonly`), client ID, client secret, refresh token, user ID, and token.

No additional configuration is required, as the tests are self-contained and do not rely on external services.

## Running the Tests

To run the tests for the `GoogleApi` class, use the following command from the root directory of your project:

```bash
./vendor/bin/phpunit --verbose tests/GoogleApiTest.php
```

### Command Breakdown
- `./vendor/bin/phpunit`: Executes the PHPUnit binary installed via Composer.
- `--verbose`: Enables verbose output, displaying detailed information about each test case, including test names and results.
- `tests/GoogleApiTest.php`: Specifies the test file for the `GoogleApi` class.

### Expected Output
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

### Troubleshooting
If tests fail, check the following:
- **Dependencies**: Ensure all dependencies are installed (`composer install`) and match the required versions. Verify that `anibalealvarezs/google-api` and `anibalealvarezs/api-client-skeleton` are correctly installed.
- **PHP Version**: Confirm PHP 8.1 or higher is used (`php -v`).
- **Composer Autoloader**: Run `composer dump-autoload` to regenerate the autoloader if classes are not found.
- **File Path**: Ensure the test file is located at `tests/GoogleApiTest.php`. Adjust the PHPUnit command if the directory structure differs.
- **Verbose Output**: The `--verbose` flag provides detailed error messages, including stack traces for failed assertions or exceptions (e.g., `InvalidArgumentException` for invalid URLs or missing credentials).

## Test Coverage

The `GoogleApiTest.php` class, located in `tests/GoogleApiTest.php`, includes 10 test methods that cover the `GoogleApi` class's constructor, which is the only public method. The tests ensure proper initialization and validation of OAuth v2 authentication parameters, including:
- **Valid Configuration**: Verifies that the constructor correctly sets the base URL, authentication URLs (auth URL, token URL, refresh auth URL), redirect URL, client ID, client secret, refresh token, user ID, scopes, token, authentication type, settings, and headers (e.g., `testConstructorWithValidParameters`).
- **Error Handling**: Tests for invalid or empty inputs, including:
  - Empty base URL (`testConstructorWithEmptyBaseUrl`).
  - Invalid base URL (`testConstructorWithInvalidBaseUrl`).
  - Empty client ID (`testConstructorWithEmptyClientId`).
  - Empty client secret (`testConstructorWithEmptyClientSecret`).
  - Empty refresh token (`testConstructorWithEmptyRefreshToken`).
  - Invalid redirect URL (`testConstructorWithInvalidRedirectUrl`).
- **Optional Parameters**: Tests for handling empty scopes, token, and user ID (e.g., `testConstructorWithEmptyScopes`, `testConstructorWithEmptyToken`, `testConstructorWithEmptyUserId`).

To generate a test coverage report:

```bash
./vendor/bin/phpunit --verbose --coverage-text tests/GoogleApiTest.php
```

This will display a coverage report in the terminal. For an HTML report:

```bash
./vendor/bin/phpunit --verbose --coverage-html coverage tests/GoogleApiTest.php
```

This generates an HTML report in the `coverage/` directory, detailing coverage for the `GoogleApi` class (requires PHPUnit to be configured with coverage reporting).

## Additional Notes
- **Mocking**: The current tests focus on the constructor and do not require HTTP requests. Future tests for API endpoints (e.g., Drive, Gmail) can use Guzzle's `MockHandler` to simulate responses.
- **Isolation**: Each test method is isolated, with the `setUp` method resetting the test environment to prevent state leakage.
- **Extending Tests**: To add tests for additional `GoogleApi` methods (e.g., for Drive or Sheets endpoints), extend `GoogleApiTest.php` with methods that mock `performRequest` calls.
- **PHPUnit Configuration**: Ensure a `phpunit.xml` file in the project root includes the test directory:
  ```xml
  <phpunit>
      <testsuites>
          <testsuite name="GoogleApi Tests">
              <directory>tests</directory>
          </testsuite>
      </testsuites>
      <php>
          <includePath>vendor/autoload.php</includePath>
      </php>
  </phpunit>
  ```
- **Directory Structure**: The test file is located at `tests/GoogleApiTest.php` to match the namespace `Tests`. Adjust the path in the PHPUnit command if your project uses a different structure.
- **Dependency**: The `anibalealvarezs/api-client-skeleton` package is automatically included as a dependency of `anibalealvarezs/google-api`. Ensure it is installed correctly via Composer.