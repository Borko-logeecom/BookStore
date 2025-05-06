<?php

declare(strict_types=1);

namespace BookStore\Response;

use RuntimeException;

/**
 * Represents an HTTP response containing JSON content.
 */
class JsonResponse extends Response
{
    /**
     * Constructor.
     *
     * @param mixed $data       The data to be encoded as JSON.
     * @param int   $statusCode The HTTP status code (default: 200).
     * @param array $headers    Additional headers as an associative array (default: empty).
     * @param int   $encodingOptions JSON encoding options (default: JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES).
     */
    public function __construct(mixed $data, int $statusCode = 200, array $headers = [], int $encodingOptions = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
    {
        parent::__construct($statusCode);

        $this->addHeader('Content-Type', 'application/json; charset=UTF-8');

        $jsonBody = json_encode($data, $encodingOptions);

        if ($jsonBody === false) {
            $this->body = json_encode(['error' => 'JSON encoding error'], $encodingOptions);
        } else {
            $this->body = $jsonBody;
        }

        foreach ($headers as $name => $value) {
            if (is_array($value)) {
                foreach ($value as $v) {
                    $this->addHeader($name, $v, false);
                }
            } else {
                $this->addHeader($name, $value, true);
            }
        }
    }

    /**
     * Sends the JSON response to the client.
     * Implements the abstract send method from the parent Response class.
     * Sends status code, headers, and the JSON encoded body.
     *
     * @return void
     * @throws \RuntimeException If headers have already been sent (via parent methods).
     */
    public function send(): void
    {
        $this->sendStatusCode();

        $this->sendHeaders();

        echo $this->body;
    }

    /**
     * Sets the body of the response.
     * Note: For JsonResponse, the body is typically set via the constructor by encoding data.
     * This method exists to fulfill the parent abstract contract but direct use is discouraged
     * if you intend the response to be JSON encoded data.
     *
     * @param string $body The response body content.
     * @return self
     */
    public function setBody(string $body): self
    {
        $this->body = $body;
        return $this;
    }
}