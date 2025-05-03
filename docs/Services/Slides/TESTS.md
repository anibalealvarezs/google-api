# SlidesApi - Testing Instructions

This document provides instructions for running unit tests for the `SlidesApi` class in the `anibalealvarezs/google-api` package. The `SlidesApi` class extends the `GoogleApi` class (which inherits from `OAuthV2Client` in the `anibalealvarezs/api-client-skeleton` package) to interact with Google Slides, offering methods for managing presentations, slides, text, tables, charts, and images. The tests, located in `tests/Unit/Services/SlidesApiTest.php`, verify the constructor’s OAuth v2 configuration and the functionality of key public methods.

---
NOTE:  
Information about the `GoogleApi` class can be found in the [GoogleApi](../../../README.md) documentation.  
Information about the `GoogleApiTest` class can be found in the [GoogleApiTest](../../TESTS.md) documentation.  
Information about the `SlidesApi` class can be found in the [SlidesApi](README.md) documentation.  
---

## Prerequisites

Before running the tests, ensure the following requirements are met:

- **PHP**: Version 8.1 or higher, as required by the `anibalealvarezs/google-api` and `anibalealvarezs/api-client-skeleton` packages.
- **Composer**: Installed globally or locally to manage dependencies.
- **PHPUnit**: Installed via Composer as a development dependency.
- **Dependencies**: The `anibalealvarezs/google-api` package and its dependency `anibalealvarezs/api-client-skeleton` must be installed via Composer.
- **Mock Server**: Tests use mocked HTTP responses via Guzzle’s `MockHandler`. No external server is required, as tests use mock-friendly URLs (e.g., `https://slides.googleapis.com`).

## Installation

To set up the tests, add PHPUnit to the development dependencies in your project’s `composer.json`:

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

The `SlidesApiTest.php` test class, located in `tests/Unit/Services/SlidesApiTest.php`, verifies the `SlidesApi` class. The test class uses:

- **Faker**: Generates random test data (e.g., UUIDs for presentation IDs, object IDs, client IDs).
- **Guzzle MockHandler**: Simulates HTTP responses for API calls, including token refresh and Slides API endpoints.
- **PHPUnit**: Provides the testing framework for assertions and test execution.

The `setUp` method initializes the test environment by:
- Creating a Faker instance for generating test data.
- Setting a Slides-specific base URL (`https://slides.googleapis.com/v1/presentations/`) and redirect URL (`https://example.com/callback`).
- Defining OAuth v2 parameters, including scopes (`https://www.googleapis.com/auth/presentations`, `https://www.googleapis.com/auth/drive`), client ID, client secret, refresh token, user ID, and token.
- Preparing mock presentation data with slides and layouts for testing API responses.

No additional configuration is required, as the tests are self-contained and do not rely on external services.

## Running the Tests

To run the tests for the `SlidesApi` class, use the following command from the root directory of your project:

```bash
./vendor/bin/phpunit --verbose tests/Unit/Services/SlidesApiTest.php
```

### Command Breakdown
- `./vendor/bin/phpunit`: Executes the PHPUnit binary installed via Composer.
- `--verbose`: Enables verbose output, displaying detailed information about each test case, including test names and results.
- `tests/Unit/Services/SlidesApiTest.php`: Specifies the test file for the `SlidesApi` class.

### Expected Output
When running the command, you will see output similar to the following (assuming all 17 tests pass):

```
PHPUnit 9.6.22 by Sebastian Bergmann and contributors.

Runtime:       PHP 8.3.12

.................  17 / 17 (100%)

Time: 00:00.080, Memory: 10.00 MB

OK (17 tests, 72 assertions)
```

- The dots (`.`) represent successful test cases.
- The summary indicates the total number of tests (17), execution time, memory usage, and any failures or errors.
- With `--verbose`, additional details about each test method (e.g., `testConstructorWithValidParameters`, `testGetPresentationDataSuccess`) will be displayed.

### Troubleshooting
If tests fail, check the following:
- **Dependencies**: Ensure all dependencies are installed (`composer install`) and match the required versions. Verify that `anibalealvarezs/google-api` and `anibalealvarezs/api-client-skeleton` are correctly installed.
- **PHP Version**: Confirm PHP 8.1 or higher is used (`php -v`).
- **Composer Autoloader**: Run `composer dump-autoload` to regenerate the autoloader if classes are not found.
- **File Path**: Ensure the test file is located at `tests/Unit/Services/SlidesApiTest.php`. Adjust the PHPUnit command if the directory structure differs.
- **Verbose Output**: The `--verbose` flag provides detailed error messages, including stack traces for failed assertions or exceptions (e.g., `InvalidArgumentException` for invalid parameters or `GuzzleException` for HTTP issues).

## Test Coverage

The `SlidesApiTest.php` class, located in `tests/Unit/Services/SlidesApiTest.php`, includes 17 test methods that cover the `SlidesApi` class’s constructor and key public methods for interacting with Google Slides. The tests include:

- **Constructor Validation**:
    - `testConstructorWithValidParameters`: Verifies that the constructor sets the base URL (`https://slides.googleapis.com/v1/presentations/`), OAuth v2 URLs, client credentials, scopes, token, authentication type, settings, and headers.
    - `testConstructorWithEmptyClientId`: Ensures an `InvalidArgumentException` is thrown for an empty client ID.
    - `testConstructorWithDefaultScopes`: Confirms default scopes include `presentations`, `drive`, and `spreadsheets` when none are provided.

- **Presentation Management**:
    - `testGetPresentationDataSuccess`: Tests retrieving presentation data via a `GET` request, including token refresh handling.
    - `testGetLayoutsDataSuccess`: Verifies extraction of layout data from presentation data.
    - `testGetLayoutsSuccess`: Ensures layouts are returned as `Page` objects.
    - `testCreatePresentationSuccess`: Tests creating a presentation with a specified ID, title, and page size.
    - `testCreatePresentationWithInvalidPageSize`: Confirms an `InvalidArgumentException` for invalid page size input.

- **Slide Management**:
    - `testCreateSlideSuccess`: Tests creating a slide with a layout reference and insertion index.
    - `testCreateSlideWithCheckPresentationNoData`: Verifies slide creation with presentation data validation.
    - `testGetSlideSuccess`: Tests retrieving a slide by page ID.

- **Text Management**:
    - `testInsertTextSuccess`: Tests inserting text into a slide object.
    - `testDeleteTextSuccess`: Verifies deleting text within a specified range.
    - `testUpdateTextSuccess`: Tests updating text by deleting and inserting new text.
    - `testUpdateTextStyleSuccess`: Ensures text style updates (e.g., bold) are applied correctly.

- **Table Management**:
    - `testCreateTableSuccess`: Tests creating a table with specified rows and columns.
    - `testUpdateTableCellPropertiesSuccess`: Verifies updating table cell properties (e.g., background color).

- **Chart and Image Management**:
    - `testCreateSheetsChartSuccess`: Tests creating a chart linked to a Google Sheets spreadsheet.
    - `testCreateImageSuccess`: Verifies creating an image from a URL.

To generate a test coverage report:

```bash
./vendor/bin/phpunit --verbose --coverage-text tests/Unit/Services/SlidesApiTest.php
```

For an HTML report:

```bash
./vendor/bin/phpunit --verbose --coverage-html coverage tests/Unit/Services/SlidesApiTest.php
```

This generates an HTML report in the `coverage/` directory, detailing coverage for the `SlidesApi` class (requires PHPUnit to be configured with coverage reporting).

## Additional Notes
- **Mocking**: Tests use Guzzle’s `MockHandler` to simulate API responses, including token refresh and Slides API endpoints, ensuring no real network calls are made.
- **Isolation**: Each test method is isolated, with the `setUp` method resetting the test environment to prevent state leakage.
- **Extending Tests**: To add tests for additional `SlidesApi` methods (e.g., `updateTableRowProperties`, `updateTableColumnProperties`) or other services (e.g., Drive, Sheets), create new test classes in `tests/Services/` or extend `SlidesApiTest.php`.
- **PHPUnit Configuration**: Ensure a `phpunit.xml` file in the project root includes the test directory:
  ```xml
  <phpunit>
      <testsuites>
          <testsuite name="SlidesApi Tests">
              <directory>tests/Services</directory>
          </testsuite>
      </testsuites>
      <php>
          <includePath>vendor/autoload.php</includePath>
      </php>
  </phpunit>
  ```
- **Directory Structure**: The test file is located at `tests/Unit/Services/SlidesApiTest.php` to match the namespace `Tests\Services`. Adjust the path in the PHPUnit command if your project uses a different structure.
- **Dependency**: The `anibalealvarezs/api-client-skeleton` package is automatically included as a dependency of `anibalealvarezs/google-api`. Ensure it is installed correctly via Composer.