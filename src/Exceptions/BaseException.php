<?php

namespace Eightbitsnl\GezinsbondPhpClient\Exceptions;

use Exception;
use GuzzleHttp\Exception\ClientException;

class BaseException extends Exception
{
    public ClientException $clientException;

    public function setClientException($clientException)
    {
        $this->clientException = $clientException;
        return $this;
    }
}
?>
