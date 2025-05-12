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
    public function __construct(int $statusCode = 200)
    {
        $this->setStatusCode($statusCode);
    }

    /**
     * Sets the HTTP status code for the response.
     *
     * @param int $statusCode The HTTP status code.
     * @return self
     * @throws \InvalidArgumentException If the status code is not valid.
     */
    public function setStatusCode(int $statusCode): self
    {
        if ($statusCode < 100 || $statusCode > 599) {
            throw new \InvalidArgumentException('Invalid HTTP status code.');
        }
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * Gets the HTTP status code of the response.
     *
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Adds an HTTP header to the response.
     *
     * @param string $name The header name.
     * @param string $value The header value.
     * @param bool $replace Whether to replace existing headers with the same name.
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
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Clears all headers from the response.
     *
     * @return self
     */
    public function clearHeaders(): self
    {
        $this->headers = [];

        return $this;
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
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * Sends the HTTP status code, headers, and body to the client.
     * Must be implemented by subclasses.
     *
     * @return void
     */
    abstract public function send(): void;

    /**
     * Normalizes the header name format (e.g., 'content-type' to 'Content-Type').
     *
     * @param string $name The header name to normalize.
     * @return string
     */
    protected function normalizeHeaderName(string $name): string
    {
        return str_replace(' ', '-', ucwords(str_replace('-', ' ', strtolower($name))));
    }

    /**
     * Sends the HTTP status code to the client.
     *
     * @return void
     * @throws RuntimeException If headers have already been sent.
     */
    protected function sendStatusCode(): void
    {
        if (headers_sent()) {
            throw new RuntimeException('Headers have already been sent.');
        }

        header($_SERVER['SERVER_PROTOCOL'] . ' ' . $this->statusCode, true, $this->statusCode);
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
            throw new RuntimeException('Headers have already been sent.');
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