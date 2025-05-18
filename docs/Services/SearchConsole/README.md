# Google Search Console

## Index

- [Google Search Console](#google-search-console)
  - [Index](#index)
  - [Testing](TESTS.md)
  - [Raw Requests](#raw-requests)
    - [Raw: getSearchQueryResults request](#raw-getsearchqueryresults-request)
      - [Enums included](#enums-included)
    - [Raw: addSite request](#raw-addsite-request)
    - [Raw: removeSite request](#raw-removesite-request)
    - [Raw: getSitemaps request](#raw-getsitemaps-request)
    - [Raw: getSitemap request](#raw-getsitemap-request)
    - [Raw: addSitemap request](#raw-addsitemap-request)
    - [Raw: removeSitemap request](#raw-removesitemap-request)
    - [Raw: inspectUrl request](#raw-inspecturl-request)
  - [Request methods](#request-methods)
    - [Sites](#sites)
    - [Search Analytics](#search-analytics)
    - [Sitemaps](#sitemaps)
    - [URL Inspection](#url-inspection)

___

## Raw Requests

### Raw: getSearchQueryResults request

<details>
<summary><strong>Parameters</strong></summary>

Minimal Object

```json
{
  "startDate": "2023-01-01",
  "endDate": "2023-01-31"
}
```

Full Object

```json
{
  "startDate": "2023-01-01",
  "endDate": "2023-01-31",
  "rowLimit": 25000,
  "startRow": 0,
  "dataState": "ALL",
  "dimensions": ["query", "page"],
  "type": "web",
  "dimensionFilterGroups": [
    {
      "groupType": "AND",
      "filters": [
        {
          "dimension": "country",
          "operator": "EQUALS",
          "expression": "usa"
        }
      ]
    }
  ],
  "aggregationType": "AUTO"
}
```

#### Enums included

- [DataState](/src/Services/SearchConsole/Enums/DataState.php)
- [Dimension](/src/Services/SearchConsole/Enums/Dimension.php)
- [Operator](/src/Services/SearchConsole/Enums/Operator.php)
- [GroupType](/src/Services/SearchConsole/Enums/GroupType.php)
- [AggregationType](/src/Services/SearchConsole/Enums/AggregationType.php)

</details>

___

### Raw: addSite request

<details>
<summary><strong>Parameters</strong></summary>

**Note**: No request body is required for adding a site. The site URL is specified in the endpoint path.

</details>

___

### Raw: removeSite request

<details>
<summary><strong>Parameters</strong></summary>

**Note**: No request body is required for removing a site. The site URL is specified in the endpoint path.

</details>

___

### Raw: getSitemaps request

<details>
<summary><strong>Parameters</strong></summary>

**Note**: No request body is required for retrieving sitemaps. The site URL is specified in the endpoint path.

</details>

___

### Raw: getSitemap request

<details>
<summary><strong>Parameters</strong></summary>

**Note**: No request body is required for retrieving a specific sitemap. The site URL and sitemap URL are specified in the endpoint path.

</details>

___

### Raw: addSitemap request

<details>
<summary><strong>Parameters</strong></summary>

**Note**: No request body is required for adding a sitemap. The site URL and sitemap URL are specified in the endpoint path.

</details>

___

### Raw: removeSitemap request

<details>
<summary><strong>Parameters</strong></summary>

**Note**: No request body is required for removing a sitemap. The site URL and sitemap URL are specified in the endpoint path.

</details>

___

### Raw: inspectUrl request

<details>
<summary><strong>Parameters</strong></summary>

Minimal Object

```json
{
  "inspectionUrl": "https://example.com/page",
  "siteUrl": "https://example.com"
}
```

Full Object

```json
{
  "inspectionUrl": "https://example.com/page",
  "siteUrl": "https://example.com",
  "languageCode": "en-US"
}
```

</details>

___

## Request methods

The following are non-static methods that can be called from instances (client instances) of the [SearchConsoleApi](/src/Services/SearchConsole/SearchConsoleApi.php) class.

___

### Sites

- ### getSites(): *Array*

  `Gets the list of sites the user has access to in Search Console.`

  <details>
    <summary><strong>Parameters</strong></summary>

  - None
  </details><br>

- ### getSite(): *Array*

  `Gets information about a specific site.`

  <details>
    <summary><strong>Parameters</strong></summary>

  - Required

    - `siteUrl`: *String*  
      The URL of the site (e.g., `https://example.com` or `sc-domain:example.com`).

  </details><br>

- ### addSite(): *Array*

  `Adds a site to the user's Search Console account.`

  <details>
    <summary><strong>Parameters</strong></summary>

  - Required

    - `siteUrl`: *String*  
      The URL of the site to add (e.g., `https://example.com` or `sc-domain:example.com`).

  </details><br>

- ### removeSite(): *Array*

  `Removes a site from the user's Search Console account.`

  <details>
    <summary><strong>Parameters</strong></summary>

  - Required

    - `siteUrl`: *String*  
      The URL of the site to remove (e.g., `https://example.com` or `sc-domain:example.com`).

  </details><br>

___

### Search Analytics

- ### getSearchQueryResults(): *Array*

  `Queries the Search Analytics data for a site.`

  <details>
    <summary><strong>Parameters</strong></summary>

  - Required

    - `siteUrl`: *String*  
      The URL of the site (e.g., `https://example.com` or `sc-domain:example.com`).

    - `startDate`: *String*  
      Start date of the query in `YYYY-MM-DD` format.

    - `endDate`: *String*  
      End date of the query in `YYYY-MM-DD` format.

  - Optional

    - `rowLimit`: *Integer*  
      Maximum number of rows to return (default: 25000).

    - `startRow`: *Integer*  
      Zero-based index of the first row to return (default: 0).

    - `dataState`: [*DataState*](/src/Services/SearchConsole/Enums/DataState.php)  
      Data state for the query (e.g., `ALL`, `FINALIZED`, `FRESH`; default: `ALL`).

    - `dimensions`: *Array|null*  
      List of dimensions to include (e.g., `["query", "page"]`).

    - `type`: *String|null*  
      Search type (e.g., `web`, `image`, `video`).

    - `dimensionFilterGroups`: [*DimensionFilterGroup[]*](/src/Services/SearchConsole/Classes/DimensionFilterGroup.php)|null  
      Array of dimension filter groups to apply.

    - `aggregationType`: [*AggregationType*](/src/Services/SearchConsole/Enums/AggregationType.php)  
      Aggregation type for the query (e.g., `AUTO`, `BY_PROPERTY`; default: `AUTO`).

  </details><br>

- ### getAllSearchQueryResults(): *Array*

  `Queries all Search Analytics data for a site, fetching all rows by paginating through results.`

  <details>
    <summary><strong>Parameters</strong></summary>

  - Required

    - `siteUrl`: *String*  
      The URL of the site (e.g., `https://example.com` or `sc-domain:example.com`).

    - `startDate`: *String*  
      Start date of the query in `YYYY-MM-DD` format.

    - `endDate`: *String*  
      End date of the query in `YYYY-MM-DD` format.

  - Optional

    - `rowLimit`: *Integer*  
      Maximum number of rows to return per request (default: 25000).

    - `startRow`: *Integer*  
      Zero-based index of the first row to return (default: 0).

    - `dataState`: [*DataState*](/src/Services/SearchConsole/Enums/DataState.php)  
      Data state for the query (e.g., `ALL`, `FINALIZED`, `FRESH`; default: `ALL`).

    - `dimensions`: *Array|null*  
      List of dimensions to include (e.g., `["query", "page"]`).

    - `type`: *String|null*  
      Search type (e.g., `web`, `image`, `video`).

    - `dimensionFilterGroups`: [*DimensionFilterGroup[]*](/src/Services/SearchConsole/Classes/DimensionFilterGroup.php)|null  
      Array of dimension filter groups to apply.

    - `aggregationType`: [*AggregationType*](/src/Services/SearchConsole/Enums/AggregationType.php)  
      Aggregation type for the query (e.g., `AUTO`, `BY_PROPERTY`; default: `AUTO`).

  </details><br>

___

### Sitemaps

- ### getSitemaps(): *Array*

  `Gets the list of sitemaps for a site.`

  <details>
    <summary><strong>Parameters</strong></summary>

  - Required

    - `siteUrl`: *String*  
      The URL of the site (e.g., `https://example.com` or `sc-domain:example.com`).

  </details><br>

- ### getSitemap(): *Array*

  `Gets information about a specific sitemap for a site.`

  <details>
    <summary><strong>Parameters</strong></summary>

  - Required

    - `siteUrl`: *String*  
      The URL of the site (e.g., `https://example.com` or `sc-domain:example.com`).

    - `sitemap`: *String*  
      The URL of the sitemap (e.g., `https://example.com/sitemap.xml`).

  </details><br>

- ### addSitemap(): *Array*

  `Submits a sitemap for a site.`

  <details>
    <summary><strong>Parameters</strong></summary>

  - Required

    - `siteUrl`: *String*  
      The URL of the site (e.g., `https://example.com` or `sc-domain:example.com`).

    - `sitemap`: *String*  
      The URL of the sitemap to submit (e.g., `https://example.com/sitemap.xml`).

  - Optional

    - `client`: [*GuzzleHttp\Client*]|null  
      A custom Guzzle client for validating the sitemap URL. If not provided, a default client is used.

  </details><br>

- ### removeSitemap(): *Array*

  `Removes a sitemap from a site.`

  <details>
    <summary><strong>Parameters</strong></summary>

  - Required

    - `siteUrl`: *String*  
      The URL of the site (e.g., `https://example.com` or `sc-domain:example.com`).

    - `sitemap`: *String*  
      The URL of the sitemap to remove (e.g., `https://example.com/sitemap.xml`).

  </details><br>

___

### URL Inspection

- ### inspectUrl(): *Array*

  `Inspects a URL to retrieve indexing and crawling information.`

  <details>
    <summary><strong>Parameters</strong></summary>

  - Required

    - `siteUrl`: *String*  
      The URL of the site (e.g., `https://example.com` or `sc-domain:example.com`).

    - `url`: *String*  
      The URL to inspect (e.g., `https://example.com/page`).

  - Optional

    - `languageCode`: *String*  
      Language code for the inspection (e.g., `en-US`; default: `en-US`).

  </details><br>