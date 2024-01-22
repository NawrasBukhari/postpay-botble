<?php

namespace Postpay\Exceptions;

use Postpay\Http\Response;

class GraphQLException extends ApiException
{
    /**
     * @const string Error key returned.
     */
    const ERROR_KEY = 'errors';

    /**
     * @var array The decoded JSON response errors.
     */
    protected $errors = [];

    /**
     * Creates a RESTfulException.
     *
     * @param Response $response The response that threw the exception.
     */
    public function __construct(Response $response)
    {
        $this->response = $response;
        $this->decodedBody = $response->json();
        $this->errors = $this->decodedBody[self::ERROR_KEY];
        parent::__construct('GraphQL Error.');
    }

    /**
     * Return the JSON response errors.
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
