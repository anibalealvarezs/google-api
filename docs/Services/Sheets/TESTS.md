# Google Sheets API - Testing Instructions

This document provides instructions for running unit and integration tests for the `SheetsApi` class.

## Unit Tests

Unit tests for `SheetsApi` are located in `tests/Unit/Services/SheetsApiTest.php`. These tests use mocked HTTP responses and do not require real API credentials.

### Running Unit Tests

To run the unit tests:

```bash
./vendor/bin/phpunit tests/Unit/Services/SheetsApiTest.php
```

These tests verify:

- Spreadsheet creation request structure.
- Cell appending logic and parameters.
- Batch reading of multiple ranges.
- Batch updating of values.
- Batch clearing of ranges.

## Integration Tests (Live)

Integration tests for `SheetsApi` are located in `tests/Integration/Services/SheetsApiLiveTest.php`. These tests perform real operations against the Google Sheets API.

**CAUTION:** These tests will create real spreadsheets in your Google account.

### Prerequisites

1.  Ensure you have a valid `config/config.yaml` file with Google API credentials.
2.  The user should have permission to manage spreadsheets (`https://www.googleapis.com/auth/spreadsheets` scope).

### Configuration

You can optionally specify a spreadsheet ID to use for tests in your `config.yaml`:

```yaml
sheets_test_spreadsheet_id: "your-spreadsheet-id"
```

If not provided, the test will create a new spreadsheet for the session.

### Running Integration Tests

To run the integration tests:

```bash
./vendor/bin/phpunit tests/Integration/Services/SheetsApiLiveTest.php
```

These tests verify:

- Real spreadsheet creation.
- Successful appending of data to a real sheet.
- Real-world batch reading and writing.
- Successful token refresh and persistence for the Sheets service.
