<?php

namespace Eightbitsnl\GezinsbondPhpClient\Requests;

use Psr\Http\Message\ResponseInterface;

class GezinsbondResponse
{
    public $response;

    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->response, $name], $arguments);
    }

    public function json()
    {
        return json_decode($this->response->getBody()->getContents(), true);
    }
}
