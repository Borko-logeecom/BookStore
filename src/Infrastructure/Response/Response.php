<?php

declare(strict_types=1);

namespace BookStore\Infrastructure\Response;

use RuntimeException;

/**
 * Abstract base class for all HTTP Responses.
 * Encapsulates HTTP status code, headers, and body.
 */
abstract class Response
{
    protected int $statusCode;
    protected array $headers = [];
    protected string $body = '';

    /**
     * Constructor.
     * Initializes the response with a status code.
     *
     * @param int $statusCode The HTTP status code (e.g., 200, 404).
     */
    public function __construct(int $statusCode = 200, array $headers = [])
    {
        $this->statusCode = $statusCode;
        $this->setStatusCode($statusCode);
        $this->headers = $headers;
        $this->sendHeaders();
    }

    /**
     * Sets the HTTP status code for the response.
     *
     * @param int $statusCode The HTTP status code.
     *
     * @return void
     */
    public function setStatusCode(int $statusCode): void
    {
        http_response_code($statusCode);
    }

    /**
     * Sends the HTTP status code, headers, and body to the client.
     * This method must be implemented by concrete response types.
     *
     * @return void
     */
    abstract public function send(): void;

    /**
     * Sends the HTTP headers to the client.
     *
     * @return void
     * @throws RuntimeException If headers have already been sent.
     */
    protected function sendHeaders(): void
    {
        if (headers_sent()) {
            throw new \RuntimeException('Headers have already been sent.');
        }

        foreach ($this->headers as $name => $value) {
            if (is_array($value)) {
                foreach ($value as $v) {
                    header($name . ': ' . $v, false); // false allows multiple headers with the same name
                }
            } else {
                header($name . ': ' . $value, true); // true replaces previous headers with the same name
            }
        }
    }
}