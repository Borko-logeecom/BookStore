<?php

declare(strict_types=1);

namespace BookStore\Infrastructure\Response;

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
        $this->setJsonBody($data, $encodingOptions);

        foreach ($headers as $name => $value) {
            $this->addHeader($name, $value);
        }
    }

    /**
     * Sets the JSON body of the response.
     *
     * @param mixed $data
     * @param int $encodingOptions
     * @return void
     */
    private function setJsonBody(mixed $data, int $encodingOptions): void
    {
        $json = json_encode($data, $encodingOptions);
        if ($json === false) {
            throw new \RuntimeException('JSON encoding error: ' . json_last_error_msg());
        }

        $this->body = $json;
    }

    /**
     * Sends the JSON response to the client.
     *
     * @return void
     */
    public function send(): void
    {
        $this->sendStatusCode();

        $this->sendHeaders();

        echo $this->body;
    }
}