<?php

namespace Postpay\HttpClients;

use Postpay\Http\Request;

class Client
{
    /**
     * @const int The timeout in seconds for requests.
     */
    const DEFAULT_REQUEST_TIMEOUT = 20;

    /**
     * @var ClientInterface HTTP client handler.
     */
    protected $clientHandler;

    /**
     * Instantiates a new Client object.
     *
     * @param ClientInterface|null $clientHandler
     */
    public function __construct(ClientInterface $clientHandler = null)
    {
        $this->clientHandler = $clientHandler ?: new GuzzleClient();
    }

    /**
     * Returns the HTTP client handler.
     *
     * @return ClientInterface
     */
    public function getClientHandler()
    {
        return $this->clientHandler;
    }

    /**
     * Sets the HTTP client handler.
     *
     * @param ClientInterface $clientHandler
     */
    public function setClientHandler(ClientInterface $clientHandler)
    {
        $this->clientHandler = $clientHandler;
    }

    /**
     * Sends the request to API and returns the response.
     *
     * @param Request $request
     *
     * @return \Postpay\Http\Response
     *
     * @throws \Postpay\Exceptions\PostpayException
     */
    public function request(Request $request)
    {
        $response = $this->clientHandler->send(
            $request,
            static::DEFAULT_REQUEST_TIMEOUT
        );

        if ($response->isError()) {
            throw $response->getThrownException();
        }
        return $response;
    }
}
