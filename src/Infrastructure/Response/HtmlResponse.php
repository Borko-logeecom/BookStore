<?php

declare(strict_types=1);

namespace BookStore\Infrastructure\Response;

/**
 * Represents an HTTP response containing HTML content.
 */
class HtmlResponse extends Response
{
    /**
     * Constructor.
     *
     * @param string $body       The HTML content for the response body.
     * @param int    $statusCode The HTTP status code (default: 200).
     * @param array  $headers    Additional headers as an associative array (default: empty).
     */
    public function __construct(string $body, int $statusCode = 200, array $headers = [])
    {
        $this->headers[] = ['Content-Type' => 'text/html'];
        parent::__construct($statusCode, $headers);
        $this->body = $body;

    }

    /**
     * Sends the HTML response to the client.
     * Implements the abstract send method from the parent Response class.
     *
     * @return void
     * @throws \RuntimeException If headers have already been sent.
     */
    public function send(): void
    {
        echo $this->body;
    }
}