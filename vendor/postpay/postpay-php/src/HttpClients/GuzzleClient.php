<?php

namespace Postpay\HttpClients;

use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\RequestOptions;

use Postpay\Exceptions\PostpayException;
use Postpay\Http\Request;
use Postpay\Http\Response;

class GuzzleClient implements ClientInterface
{
    /**
     * @var GuzzleHttpClient The Guzzle client.
     */
    protected $guzzleClient;

    /**
     * @param GuzzleHttpClient|null $guzzleClient The Guzzle client.
     */
    public function __construct(GuzzleHttpClient $guzzleClient = null)
    {
        $this->guzzleClient = $guzzleClient ?: new GuzzleHttpClient();
    }

    /**
     * @inheritdoc
     */
    public function send(Request $request, $timeout = null)
    {
        $options = [
            'auth' => $request->getAuth(),
            'headers' => $request->getHeaders(),
            'http_errors' => false,
            'timeout' => $timeout,
            'connect_timeout' => 10,
            RequestOptions::JSON => $request->json(),
        ];
        try {
            $response = $this->guzzleClient->request(
                $request->getMethod(),
                $request->getUrl(),
                $options
            );
        } catch (RequestException $e) {
            throw new PostpayException($e->getMessage(), $e->getCode());
        }
        return new Response(
            $request,
            $response->getStatusCode(),
            $response->getHeaders(),
            $response->getBody()
        );
    }
}
