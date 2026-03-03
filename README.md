# Google API

## Instructions

Require the package in the `composer.json` file of your project, and map the package in the `repositories` section.

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
  ]
}
```

## Usage

### Instantiation and Token Persistence

The library supports automatic token persistence. When a refresh token is provided, the library will automatically fetch a new access token when needed and save it to a local JSON file.

```php
use Anibalealvarezs\GoogleApi\Services\Sheets\SheetsApi;

$config = app_config();

$sheets = new SheetsApi(
    redirectUrl: $config['google_redirect_uri'],
    clientId: $config['google_client_id'],
    clientSecret: $config['google_client_secret'],
    refreshToken: $config['google_refresh_token'],
    userId: $config['google_user_id'],
    tokenPath: $config['google_token_path'] // Optional: path to save/load tokens
);
```

### Token Storage Structure

If `tokenPath` is provided, tokens will be stored in a JSON file with the following structure:

```json
{
  "user@gmail.com": {
    "Services\\Sheets\\SheetsApi": "ya29.a0AfH6S...",
    "Services\\SearchConsole\\SearchConsoleApi": "ya29.a0AfH6S..."
  }
}
```

This allows multiple services to share the same token file while maintaining isolated tokens for different scopes.

## Services

- [BigQuery](docs/Services/BigQuery/README.md)
- [Drive](docs/Services/Drive/README.md)
- [Gmail](docs/Services/Gmail/README.md)
- [SearchConsole](docs/Services/SearchConsole/README.md)
- [Sheets](docs/Services/Sheets/README.md)
- [Slides](docs/Services/Slides/README.md)

## Testing

Instructions [here](docs/TESTS.md).
