<?php

declare(strict_types=1);

namespace BookStore\Infrastructure\Response;

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
     * @param array  $headers    Additional headers as an associative array (default: empty).
     */
    public function __construct(string $url, int $statusCode = 303, array $headers = [])
    {
        parent::__construct($statusCode);

        if (!in_array($statusCode, [301, 302, 303, 307, 308])) {
            throw new \InvalidArgumentException('Invalid redirect status code.');
        }

        $this->addHeader('Location', $url);

        foreach ($headers as $name => $value) {
            $this->addHeader($name, $value);
        }

        $this->body = '';
    }

    /**
     * Prevents setting a body for redirect response.
     *
     * @param string $body
     * @return self
     * @throws \LogicException
     */
    public function setBody(string $body): self
    {
        throw new \LogicException('RedirectResponse cannot have a body.');
    }

    /**
     * Sends the redirect response to the client.
     *
     * @return void
     */
    public function send(): void
    {
        $this->sendStatusCode();
        $this->sendHeaders();
    }
}