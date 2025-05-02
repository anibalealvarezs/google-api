<?php

namespace Anibalealvarezs\GoogleApi\Services\Gmail;

use Anibalealvarezs\GoogleApi\Google\GoogleApi;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class GmailApi extends GoogleApi
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
            baseUrl: "https://gmail.googleapis.com/gmail/v1/",
            redirectUrl: $redirectUrl,
            clientId: $clientId,
            clientSecret: $clientSecret,
            refreshToken: $refreshToken,
            userId: $userId,
            scopes: ($scopes ?: ["https://www.googleapis.com/auth/gmail.modify"]),
            guzzleClient: $guzzleClient,
        );
    }

    /**
     * @param array $labelIds
     * @param string $query
     * @param int $maxResults
     * @return object
     * @throws GuzzleException
     */
    public function batchGetEmailsList(
        array $labelIds = [],
        string $query = "",
        int $maxResults = 20
    ): object {
        $pageToken = "";
        $pageCount = 0;
        $total = 9999999;
        $data = [];
        $interrupt = false;
        while (!$interrupt && ($pageCount * $maxResults < $total)) {
            $tries = 0;
            $results = (object) ["success" => false];
            while (!$results->success && $tries < 3) {
                $results = $this->getEmailsList(labelIds: $labelIds, q: $query, maxResults: $maxResults, pageToken: $pageToken);
                if ($results->success) {
                    $data = array_merge($data, $results->data);
                    $pageCount++;
                    $total = $results->total;
                    $pageToken = $results->page_token;
                }
                $tries++;
            }
            if (!$results->success) {
                $interrupt = true;
            }
        }
        return (object) [
            "success" => !$interrupt,
            "data" => $data,
            "total" => $total,
        ];
    }

    /**
     * @param string $pageToken
     * @param array $labelIds
     * @param string $q
     * @param int $maxResults
     * @return object
     * @throws GuzzleException
     */
    public function getEmailsList(
        array $labelIds = [],
        string $q = "",
        int $maxResults = 20,
        string $pageToken = ""
    ): object {
        $query = [
            "maxResults" => $maxResults,
            "pageToken" => $pageToken,
            "labelIds" => implode(",", $labelIds),
            "q" => $q
        ];

        $response = $this->performRequest(
            method: "GET",
            endpoint: "users/".$this->userId."/messages",
            query: $query
        );
        $data = json_decode($response->getBody()->getContents());

        return (object) [
            "data" => $data->messages ?? [],
            "success" => isset($data->messages),
            "page_token" => $data->nextPageToken ?? "",
            "total" => $data->resultSizeEstimate ?? 0
        ];
    }

    /**
     * @param string $email_id
     * @param string $format
     * @param array $metadataHeaders
     * @return object
     * @throws GuzzleException
     */
    public function getEmail(
        string $email_id,
        string $format = "",
        array $metadataHeaders = []
    ): object {
        $query = [];
        if ($format) {
            $query["format"] = $format;
        }
        if ($metadataHeaders) {
            $query["metadataHeaders"] = implode(",", $metadataHeaders);
        }

        $response = $this->performRequest(
            method: "GET",
            endpoint: "users/".$this->userId."/messages/" . $email_id,
            query: $query
        );
        $data = json_decode($response->getBody()->getContents());

        return (object) [
            "data" => $data,
            "success" => isset($data->id),
        ];
    }

    /**
     * @param $payload
     * @return array
     * @throws GuzzleException
     */
    protected function parseParts(
        $payload
    ): array {
        $parts = [];
        if (isset($payload->parts)) {
            foreach ($payload->parts as $part) {
                if (isset($part->parts)) {
                    $subparts = $this->parseParts(payload: $part);
                } else {
                    $parts[] = $part;
                }
            }
        }
        return array_merge($subparts ?? [], $parts);
    }

    /**
     * @param string $id
     * @return object|null
     * @throws GuzzleException
     */
    public function getMessageContent(
        string $id
    ): ?object {
        $content = (object) [
            "from" => "",
            "subject" => "",
            "text" => "",
            "html" => "",
            "attachments" => []
        ];
        $msg = $this->getEmail(email_id: $id);
        if (!$msg->success) {
            return null;
        }
        $content->from = $this->getMessageFrom(message: $msg->data);
        $content->subject = $msg->data->snippet;
        $parts = $this->parseParts(payload: $msg->data->payload);
        $files = [];
        foreach ($parts as $part) {
            if (!$part->filename) {
                if ($part->mimeType == "text/plain") {
                    $content->text = $this->gmailBodyDecode(data: $part->body->data);
                } elseif ($part->mimeType == "text/html") {
                    $content->html = $this->gmailBodyDecode(data: $part->body->data);
                }
            } else {
                $files[] = $part;
            }
        }
        foreach ($files as $part) {
            $fileContent = $this->getEmailAttachment(email_id: $id, attachment_id: $part->body->attachmentId);
            $content->attachments[] = (object) [
                "name" => $part->filename,
                "mimeType" => $part->mimeType,
                "content" => $fileContent->success ? $fileContent->data : ""
            ];
        }
        return $content;
    }

    /**
     * @param object $message
     * @return object|null
     */
    public function getMessageFrom(
        object $message
    ): ?object {
        $from = (object) [
            "name" => "",
            "email" => ""
        ];
        $headers = $message->payload->headers;
        foreach ($headers as $header) {
            if ($header->name == "From") {
                $parts = explode("<", $header->value);
                $from->name = $parts[0];
                $from->email = str_replace(">", "", $parts[1]);
            }
        }
        return $from;
    }

    /**
     * @param object $message
     * @return bool
     */
    public function hasTextBody(
        object $message
    ): bool
    {
        return !empty($message->text);
    }

    /**
     * @param object $message
     * @return bool
     */
    public function hasHtmlBody(
        object $message
    ): bool {
        return !empty($message->html);
    }

    /**
     * @param string $email_id
     * @param string $attachment_id
     * @return object
     * @throws GuzzleException
     */
    public function getEmailAttachment(
        string $email_id,
        string $attachment_id
    ): object {
        $response = $this->performRequest(
            method: "GET",
            endpoint: "users/".$this->userId."/messages/" . $email_id . "/attachments/" . $attachment_id
        );
        $data = $this->replaceChars(
            data: $this->padChars(
                string: json_decode($response->getBody()->getContents())->data
            )
        );

        return (object) [
            "data" => $data,
            "success" => (bool)$data,
        ];
    }

    /**
     * @param string $string
     * @return string
     */
    protected function padChars(
        string $string
    ): string {
        $exceeded = strlen($string) % 4;
        return $string . str_repeat("=", $exceeded == 0 ? 0 : (4 - $exceeded));
    }

    /**
     * @return object
     * @throws GuzzleException
     */
    public function getLabels(): object
    {
        $response = $this->performRequest(
            method: "GET",
            endpoint: "users/".$this->userId."/labels"
        );
        $data = json_decode($response->getBody()->getContents());

        return (object) [
            "data" => $data,
            "success" => isset($data->labels),
        ];
    }

    /**
     * @param string $email_id
     * @param array $add
     * @param array $remove
     * @return bool
     * @throws GuzzleException
     */
    public function updateEmail(
        string $email_id,
        array $add = [],
        array $remove = []
    ): bool {
        $body = [
            "addLabelIds" => $add,
            "removeLabelIds" => $remove,
        ];

        $response = $this->performRequest(
            method: "POST",
            endpoint: "users/".$this->userId."/messages/" . $email_id . "/modify",
            body: json_encode($body),
        );
        $data = json_decode($response->getBody()->getContents());

        return isset($data->id);
    }

    /**
     * @param array $email_ids
     * @param array $add
     * @param array $remove
     * @return bool
     * @throws GuzzleException
     */
    public function batchUpdateEmails(
        array $email_ids,
        array $add = [],
        array $remove = []
    ): bool {
        $body = [
            "ids" => $email_ids,
            "addLabelIds" => $add,
            "removeLabelIds" => $remove,
        ];

        var_dump(json_encode($body));
        $response = $this->performRequest(
            method: "POST",
            endpoint: "users/".$this->userId."/messages/batchModify",
            body: json_encode($body)
        );
        $data = json_decode($response->getBody()->getContents());

        return empty($data);
    }

    /**
     * @param string $data
     * @return string
     */
    public function replaceChars(
        string $data
    ): string
    {
        $data = base64_decode(str_replace(array('-', '_'), array('+', '/'), $data));
        //from php.net/manual/es/function.base64-decode.php#118244

        return($data);
    }

    /**
     * @param string $data
     * @return string|bool
     */
    public function gmailBodyDecode(
        string $data
    ): string|bool
    {
        $data = $this->replaceChars(data: $data);
        //from php.net/manual/es/function.base64-decode.php#118244

        return(imap_qprint($data));
    }
}
