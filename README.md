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

## Services

- [BigQuery](docs/Services/BigQuery/README.md)
- [Drive](docs/Services/Drive/README.md)
- [Gmail](docs/Services/Gmail/README.md)
- [SearchConsole](docs/Services/SearchConsole/README.md)
- [Sheets](docs/Services/Sheets/README.md)
- [Slides](docs/Services/Slides/README.md)

## Testing

Instructions [here](docs/TESTS.md).