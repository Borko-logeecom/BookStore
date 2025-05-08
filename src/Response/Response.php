<?php

declare(strict_types=1);

namespace BookStore\Response;

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
    public function __construct(int $statusCode = 200)
    {
        $this->statusCode = $statusCode;
    }

    /**
     * Sets the HTTP status code for the response.
     *
     * @param int $statusCode The HTTP status code.
     * @return self
     */
    public function setStatusCode(int $statusCode): self
    {
        $this->statusCode = $statusCode;

        return $this; // Enables method chaining
    }

    /**
     * Gets the HTTP status code of the response.
     *
     * @return int The HTTP status code.
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Adds or sets an HTTP header for the response.
     * Existing headers with the same name will be overwritten unless $replace is false.
     * For headers that can have multiple values (e.g., Set-Cookie), set $replace to false.
     *
     * @param string $name The header name.
     * @param string $value The header value.
     * @param bool $replace Whether to replace existing headers with the same name (default true).
     * @return self
     */
    public function addHeader(string $name, string $value, bool $replace = true): self
    {
        // Normalize header name (e.g., 'content-type' to 'Content-Type')
        $normalizedName = $this->normalizeHeaderName($name);

        if ($replace || !isset($this->headers[$normalizedName])) {
            $this->headers[$normalizedName] = $value;
        } elseif (is_array($this->headers[$normalizedName])) {
            $this->headers[$normalizedName][] = $value;
        } else {
            $this->headers[$normalizedName] = [$this->headers[$normalizedName], $value];
        }

        return $this;
    }

    /**
     * Gets all headers set for the response.
     *
     * @return array Associative array of header names and values.
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Sets the body of the response.
     *
     * @param string $body The response body content.
     * @return self
     */
    public function setBody(string $body): self
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Gets the body of the response.
     *
     * @return string The response body content.
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * Sends the HTTP status code, headers, and body to the client.
     * This method must be implemented by concrete response types.
     *
     * @return void
     */
    abstract public function send(): void;

    /**
     * Helper method to normalize header names (e.g., 'content-type' to 'Content-Type').
     *
     * @param string $name The header name to normalize.
     * @return string The normalized header name.
     */
    protected function normalizeHeaderName(string $name): string
    {
        // Convert to lower case, replace hyphens with spaces, capitalize each word, then replace spaces with hyphens
        $name = str_replace('-', ' ', strtolower($name));
        $name = ucwords($name);
        $name = str_replace(' ', '-', $name);

        return $name;
    }

    /**
     * Sends the HTTP status line (e.g., "HTTP/1.1 200 OK").
     *
     * @return void
     * @throws \RuntimeException If headers have already been sent.
     */
    protected function sendStatusCode(): void
    {
        if (headers_sent()) {
            throw new \RuntimeException('Headers have already been sent.');
        }

        $protocol = isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.1';
        header($protocol . ' ' . $this->statusCode, true, $this->statusCode);
    }

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