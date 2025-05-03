<?php

namespace Anibalealvarezs\GoogleApi\Services\Drive;

use Anibalealvarezs\GoogleApi\Google\GoogleApi;
use Anibalealvarezs\GoogleApi\Services\Drive\Enums\Corpora;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class DriveApi extends GoogleApi
{
    /**
     * @param string $redirectUrl
     * @param string $clientId
     * @param string $clientSecret
     * @param string $refreshToken
     * @param string $userId
     * @param array $scopes
     * @param Client|null $guzzleClient
     * @throws Exception
     */
    public function __construct(
        string $redirectUrl,
        string $clientId,
        string $clientSecret,
        string $refreshToken,
        string $userId,
        array $scopes = [],
        ?Client $guzzleClient = null
    ) {
        parent::__construct(
            baseUrl: "https://www.googleapis.com/drive/v3/",
            redirectUrl: $redirectUrl,
            clientId: $clientId,
            clientSecret: $clientSecret,
            refreshToken: $refreshToken,
            userId: $userId,
            scopes: ($scopes ?: ["https://www.googleapis.com/auth/drive"]),
            guzzleClient: $guzzleClient,
        );
    }

    /**
     * @param string $fileId
     * @return array
     * @throws GuzzleException
     */
    public function getFileMetadata(
        string $fileId
    ): array {
        $fields = [
            'md5Checksum',
            'sha1Checksum',
            'sha256Checksum',
            'size',
            'id',
            'name',
            'thumbnailLink',
            'trashed',
            'explicitlyTrashed',
            'mimeType',
            'createdTime',
            'modifiedTime',
            'parents',
            'trashedTime',
        ];

        // Request the spreadsheet data
        $response = $this->performRequest(
            method: "GET",
            endpoint: "files/" . $fileId,
            query: [
                "fields" => implode(",", $fields),
                "supportsAllDrives" => "true",
            ],
        );
        // Return response
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @param string $fileId
     * @param string $path
     * @param bool $stream
     * @return bool
     * @throws GuzzleException
     */
    public function getFile(
        string $fileId,
        string $path,
        bool $stream = false
    ): bool {
        // Request the spreadsheet data
        $response = $this->performRequest(
            method: "GET",
            endpoint: "files/" . $fileId,
            query: [
                "supportsAllDrives" => "true",
                "alt" => "media",
            ],
            pathToSave: $path,
            stream: $stream,
        );
        // Return response
        return $response->getStatusCode() === 200;
    }

    /**
     * @param string $fileId
     * @param string $mimeType
     * @param string $path
     * @param bool $stream
     * @return bool
     * @throws GuzzleException
     */
    public function exportFile(
        string $fileId,
        string $mimeType,
        string $path,
        bool $stream = false
    ): bool {
        // Request the spreadsheet data
        $response = $this->performRequest(
            method: "GET",
            endpoint: "files/" . $fileId . '/export',
            query: [
                "mimeType" => $mimeType,
            ],
            pathToSave: $path,
            stream: $stream,
        );
        // Return response
        return $response->getStatusCode() === 200;
    }

    /**
     * @param string $fileId
     * @param string $fields
     * @param bool $ignoreDefaultVisibility
     * @param string|null $includeLabels
     * @param string $includePermissionsForView
     * @param bool $keepRevisionForever
     * @param string|null $ocrLanguage
     * @param bool $supportsAllDrives
     * @return array
     * @throws GuzzleException
     */
    public function copyFile(
        string $fileId,
        string $fields = "*",
        bool $ignoreDefaultVisibility = false,
        ?string $includeLabels = null,
        string $includePermissionsForView = 'published',
        bool $keepRevisionForever = false,
        ?string $ocrLanguage = null,
        bool $supportsAllDrives = true,
    ): array {
        // Request the spreadsheet data
        $response = $this->performRequest(
            method: "POST",
            endpoint: "files/" . $fileId . '/copy',
            query: [
                "fields" => $fields,
                "ignoreDefaultVisibility" => $ignoreDefaultVisibility,
                "includeLabels" => $includeLabels,
                "includePermissionsForView" => $includePermissionsForView,
                "keepRevisionForever" => $keepRevisionForever,
                "ocrLanguage" => $ocrLanguage,
                "supportsAllDrives	" => $supportsAllDrives	,
            ],
        );
        // Return response
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @param string|null $pageToken
     * @param string|null $driveId
     * @param int $pageSize
     * @param array $orderBy
     * @param string $q
     * @return array
     * @throws GuzzleException
     */
    public function getFilesMetadata(
        string $pageToken = null,
        string $driveId = null,
        int $pageSize = 1000,
        array $orderBy = [],
        string $q = ''
    ): array {
        $fields = [
            'md5Checksum',
            'sha1Checksum',
            'sha256Checksum',
            'size',
            'id',
            'name',
            'thumbnailLink',
            'trashed',
            'explicitlyTrashed',
            'mimeType',
            'createdTime',
            'modifiedTime',
            'parents',
            'trashedTime',
        ];

        $query =[
            "corpora" => $driveId ? Corpora::drive->name : Corpora::allDrives->name,
            "supportsAllDrives" => "true",
            "includeItemsFromAllDrives" => "true",
            "fields" => "files(".implode(",", $fields).")",
            "pageSize" => $pageSize,
        ];

        if ($driveId) {
            $query["driveId"] = $driveId;
        }
        if ($orderBy) {
            $query["orderBy"] = implode(",", $orderBy);
        }
        if ($pageToken) {
            $query["pageToken"] = $pageToken;
        }
        $endpoint = "files";
        if ($q) {
            $endpoint .= '?q=' . urlencode($q);
        }

        // Request the spreadsheet data
        $response = $this->performRequest(
            method: "GET",
            endpoint: $endpoint,
            query: $query,
        );
        // Return response
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @param string|null $driveId
     * @return array
     * @throws GuzzleException
     */
    public function getStartPageToken(
        string $driveId = null
    ): array {
        $query =[
            "includeCorpusRemovals" => "true",
            "supportsAllDrives" => "true",
            "includeItemsFromAllDrives" => "true",
            "fields" => "startPageToken",
            "includeRemoved" => "true",
        ];

        if ($driveId) {
            $query["driveId"] = $driveId;
        }

        // Request the spreadsheet data
        $response = $this->performRequest(
            method: "GET",
            endpoint: 'changes/startPageToken',
            query: $query,
        );
        // Return response
        return json_decode($response->getBody()->getContents(), true);
    }

    /**
     * @param int $pageToken
     * @param string|null $driveId
     * @param int $pageSize
     * @return array
     * @throws GuzzleException
     */
    public function getChanges(
        int $pageToken,
        string $driveId = null,
        int $pageSize = 1000
    ): array {
        $query =[
            "includeCorpusRemovals" => "true",
            "supportsAllDrives" => "true",
            "includeItemsFromAllDrives" => "true",
            "fields" => "*",
            "includeRemoved" => "true",
            "pageSize" => $pageSize,
            "pageToken" => $pageToken,
        ];

        if ($driveId) {
            $query["driveId"] = $driveId;
        }

        // Request the spreadsheet data
        $response = $this->performRequest(
            method: "GET",
            endpoint: 'changes',
            query: $query,
        );
        // Return response
        return json_decode($response->getBody()->getContents(), true);
    }
}
