<?php

namespace Postpay\Exceptions;

use Postpay\Http\Response;

class RESTfulException extends ApiException
{
    /**
     * @const string Error key returned.
     */
    const ERROR_KEY = 'error';

    /**
     * @var string API error code.
     */
    protected $errorCode;

    /**
     * Creates a RESTfulException.
     *
     * @param Response $response The response that threw the exception.
     */
    public function __construct(Response $response)
    {
        $this->response = $response;
        $this->decodedBody = $response->json();
        $this->errorCode = $this->get('code', 'unknown');
        parent::__construct($this->get('message', 'Unknown error.'));
    }

    /**
     * Checks isset and returns that or a default value.
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    private function get($key, $default = null)
    {
        if (isset($this->decodedBody[self::ERROR_KEY][$key])) {
            return $this->decodedBody[self::ERROR_KEY][$key];
        }
        return $default;
    }

    /**
     * Returns API error code.
     *
     * @return string
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }
}
