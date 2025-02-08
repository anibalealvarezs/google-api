# Google API

## Instructions

Require the package in the `composer.json` file of your project, and map the package in the `repositories` section.
You must also map the `api-skeleton` package.

```json
{
    "require": {
        "php": ">=8.1",
        "anibalealvarezs/google-api": "@dev"
    },
  "repositories": [
    {
      "type": "composer", "url": "https://satis.anibalalvarez.com/"
    }
  ]
}
```

Note: In order to use the package from GitLab, you need to have a valid SSH key configured in your GitLab account.

## Services

- [Drive](docs/Services/Drive/README.md)
- [Gmail](docs/Services/Gmail/README.md)
- [Sheets](docs/Services/Sheets/README.md)
- [Slides](docs/Services/Slides/README.md)
