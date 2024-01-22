<?php

namespace Postpay\HttpClients;

use Postpay\Http\Request;

interface ClientInterface
{
    /**
     * Sends a request to the server and returns the response.
     *
     * @param Request  $request Request to send.
     * @param int|null $timeout The timeout for the request.
     *
     * @return \Postpay\Http\Response Response from the server.
     *
     * @throws \Postpay\Exceptions\PostpayException
     */
    public function send(Request $request, $timeout = null);
}
