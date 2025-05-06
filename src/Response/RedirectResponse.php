<?php

declare(strict_types=1);

namespace BookStore\Response;

use RuntimeException; // Although not directly used in *this* class, it's thrown by parent methods.

/**
 * Represents an HTTP response that redirects the client to a different URL.
 */
class RedirectResponse extends Response
{
    /**
     * Constructor.
     *
     * @param string $url        The URL to redirect to.
     * @param int    $statusCode The HTTP status code for the redirect (default: 303 See Other).
     * Common codes are 301, 302, 303, 307, 308.
     * @param array  $headers    Additional headers as an associative array (default: empty).
     */
    public function __construct(string $url, int $statusCode = 303, array $headers = [])
    {
        // Call the parent constructor (Response) to set the status code
        // Redirect status codes are typically in the 3xx range (e.g., 303)
        parent::__construct($statusCode);

        // Add the mandatory Location header with the redirect URL
        $this->addHeader('Location', $url);

        // Add any additional headers provided in the constructor
        foreach ($headers as $name => $value) {
            // Use the parent's addHeader method
            if (is_array($value)) {
                foreach ($value as $v) {
                    $this->addHeader($name, $v, false); // false to allow multiple headers with the same name
                }
            } else {
                $this->addHeader($name, $value, true); // true replaces previous headers with the same name
            }
        }

        // The response body is intentionally empty for redirects
        $this->body = '';
    }

    /**
     * Sends the redirect response to the client.
     * Implements the abstract send method from the parent Response class.
     * Only sends status code and headers (specifically including the Location header).
     *
     * @return void
     * @throws \RuntimeException If headers have already been sent (via parent methods).
     */
    public function send(): void
    {
        // Send the status line (calling a method from the parent class)
        $this->sendStatusCode();

        // Send HTTP headers (calling a method from the parent class)
        // This includes the Location header we added in the constructor
        $this->sendHeaders();

        // There is no response body for redirects, so we don't echo $this->body;
    }
}