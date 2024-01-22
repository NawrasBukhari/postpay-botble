<?php

namespace Postpay\Http;

use Postpay\Exceptions\GraphQLException;
use Postpay\Exceptions\RESTfulException;

class Response
{
    /**
     * @var \Postpay\Http\Request The original request.
     */
    protected $request;

    /**
     * @var int The HTTP response status code.
     */
    protected $statusCode;

    /**
     * @var array The headers returned from API.
     */
    protected $headers;

    /**
     * @var string The raw body of the response.
     */
    protected $body;

    /**
     * @var array The decoded body.
     */
    protected $decodedBody = [];

    /**
     * @var string The exception type to be thrown.
     */
    protected $exc;

    /**
     * @var \Postpay\Exceptions\PostpayException The exception thrown.
     */
    protected $thrownException;

    /**
     * Creates a new Response entity.
     *
     * @param \Postpay\Http\Request $request
     * @param int|null              $statusCode
     * @param array|null            $headers
     * @param string|null           $body
     */
    public function __construct(
        Request $request,
        $statusCode = null,
        array $headers = [],
        $body = null
    ) {
        $this->request = $request;
        $this->statusCode = $statusCode;
        $this->headers = $headers;
        $this->body = $body;

        if ($this->request->isGraphQL()) {
            $this->exc = GraphQLException::class;
        } else {
            $this->exc = RESTfulException::class;
        }
        $this->decodeBody();
    }

    /**
     * Return the original request that returned this response.
     *
     * @return \Postpay\Http\Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Return the HTTP status code.
     *
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Return the HTTP headers.
     *
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Return the JSON response.
     *
     * @return array
     */
    public function json()
    {
        return $this->decodedBody;
    }

    /**
     * Returns true if API returned an error message.
     *
     * @return bool
     */
    public function isError()
    {
        $excClassName = $this->exc;
        return (
            $this->statusCode >= 400 or
            isset($this->decodedBody[$excClassName::ERROR_KEY])
        );
    }

    /**
     * Convert the raw response into an array if possible.
     */
    public function decodeBody()
    {
        $this->decodedBody = json_decode($this->body, true);

        if (!is_array($this->decodedBody)) {
            $this->decodedBody = [];
        }

        if ($this->isError()) {
            $this->thrownException = new $this->exc($this);
        }
    }

    /**
     * Returns the exception that was thrown.
     *
     * @return \Postpay\Exceptions\ApiException|null
     */
    public function getThrownException()
    {
        return $this->thrownException;
    }
}
