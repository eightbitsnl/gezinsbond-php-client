<?php

namespace Eightbitsnl\GezinsbondPhpClient\Requests;

use BadMethodCallException;
use Eightbitsnl\GezinsbondPhpClient\GezinsbondApiClient;
use Eightbitsnl\GezinsbondPhpClient\Exceptions\NotFoundException;
use GuzzleHttp\Exception\ClientException;

class BaseRequest
{
    public const HTTP_METHOD = null;
    public const URI = "";

    /**
     * HTTP Methods
     */
    public const HTTP_GET = "GET";
    public const HTTP_POST = "POST";
    public const HTTP_DELETE = "DELETE";
    public const HTTP_PATCH = "PATCH";
    public const HTTP_PUT = "PUT";

    protected $querystring = [];
    protected $uri_path = '';

    /**
     * @var GezinsbondApiClient
     */
    protected GezinsbondApiClient $client;

    /**
     * Request Headers
     *
     * @var array
     */
    protected array $request_headers = [];

    /**
     *
     * @param GezinsbondApiClient $client
     */
    public function __construct(GezinsbondApiClient $client)
    {
        $this->client = $client;
    }

    /**
     * @return GezinsbondResponse
     */
    public function __invoke(): GezinsbondResponse
    {
        return $this->send();
    }

    /**
     * Replaces placeholders in the URI with the given arguments.
     *
     * This method searches for placeholders in the format `{placeholder}` 
     * within the URI and replaces them with values from the `$arguments` array 
     * in the order they appear.
     * 
     * If there are more placeholders than values, the remaining placeholders 
     * will be left unchanged.
     *
     * @param array $arguments The list of values to replace the placeholders with.
     * @return self Returns the current object with the updated `uri_path`.
     */
    public function setArguments($arguments)
    {
        $index = 0;

        $this->uri_path = preg_replace_callback(
            "/\{[^}]+\}/",
            function ($matches) use (&$arguments, &$index) {
                return $arguments[$index++] ?? $matches[0];
            },
            static::URI
        );

        return $this;
    }

    public function __call($name, $arguments)
    {
        if (in_array($name, ['json'])) {
            return $this->send()->$name(...$arguments);
        }

        throw new BadMethodCallException();
    }

    public function setHeader($key, $value)
    {
        $this->request_headers[$key] = $value;
        return $this;
    }

    public function getFullUrl()
    {
        return $this->client->base_url . $this->parseUriPath() . $this->parseQueryString();
    }

    public function send($httpBody = null)
    {

        try {
            return new GezinsbondResponse(
                $this->client->performHttpCallToFullUrl(
                    static::HTTP_METHOD,
                    $this->getFullUrl(),
                    $this->request_headers,
                    $httpBody
                )
            );
        } catch (ClientException $guzzleException) {
            if ($guzzleException->getResponse()->getStatusCode() == 404) {
                throw (new NotFoundException)->setClientException($guzzleException);
            }

            throw $guzzleException;
        }
    }

    public function parseUriPath()
    {
        if (empty($this->uri_path))
            return '';

        return '/' . trim(ltrim($this->uri_path, '/'));
    }

    public function addQueryStringParam($key, $value)
    {
        $this->querystring[$key] = $value;
        return $this;
    }

    public function parseQueryString()
    {
        if (! count($this->querystring))
            return '';

        return '?' . http_build_query($this->querystring);
    }
}
