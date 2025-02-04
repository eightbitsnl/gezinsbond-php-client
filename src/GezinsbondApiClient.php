<?php

namespace Eightbitsnl\GezinsbondPhpClient;

use BadMethodCallException;
use Eightbitsnl\GezinsbondPhpClient\Requests\V2\Members\GetIsActive as V2GetMembersIsActive;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Eightbitsnl\GezinsbondPhpClient\GezinsbondApiClient
 *
 * @method Requests\GezinsbondResponse V2GetMembersIsActive()
 */
class GezinsbondApiClient
{

    /**
     * Map Requests
     *
     * @return void
     */
    private function mapRequests()
    {
        $this->requests = [
            'V2GetMembersIsActive' => V2GetMembersIsActive::class,
        ];
    }

    private $requests = [];

    /**
     * @var \GuzzleHttp\Client
     */
    protected $httpClient;

    /**
     * Base URL
     *
     * @var string
     */
    public string $base_url = "https://apiqa.gezinsbond.be";

    /**
     * Verify SSL
     *
     * @var bool
     */
    protected bool $verify_ssl = true;

    /**
     * Username
     *
     * @var string
     */
    protected string $api_key = "";

    /**
     * Timeout Delay
     *
     * @var int|null
     */
    protected ?int $timeout_delay = 120;

    /**
     * set base_url
     *
     * @param string $base_url
     * @return self
     */
    public function setBaseUrl(string $base_url): self
    {
        $this->base_url = rtrim(trim($base_url), '/');
        return $this;
    }

    /**
     * set verify_ssl
     *
     * @param string $verify_ssl
     * @return self
     */
    public function setVerifySsl(bool $verify_ssl = true): self
    {
        $this->verify_ssl = $verify_ssl;
        return $this;
    }

    /**
     * set api_key
     *
     * @param string $api_key
     * @return self
     */
    public function setApiKey(string $api_key): self
    {
        $this->api_key = $api_key;
        return $this;
    }

    /**
     * set timeout delay
     *
     * @param string $timeout_delay
     * @return self
     */
    public function setTimeoutDelay(string $timeout_delay): self
    {
        $this->timeout_delay = $timeout_delay;
        return $this;
    }

    /**
     * Constructor
     *
     * @param ClientInterface|null $httpClient
     */
    public function __construct(ClientInterface $httpClient = null)
    {
        $this->httpClient = $httpClient ?? new Client();

        $this->mapRequests();
    }

    /**
     * Magic Method
     *
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        if ($this->isRequest($name)) {
            return (new $this->requests[$name]($this))->setArguments($arguments);
        }

        throw new BadMethodCallException();
    }

    public function __get($name)
    {
        if ($this->isRequest($name)) {
            return $this->requests[$name]();
        }

        trigger_error("Undefined Property");
    }

    protected function isRequest($name)
    {
        return array_key_exists($name, $this->requests);
    }

    /**
     * Get Auth Token
     *
     * @return string auth token
     */
    private function getAuthToken(): string
    {
        return $this->api_key;
    }

    /**
     * Get Request Headers
     *
     * @return array Request Headers
     */
    private function mergeRequestHeaders($headers = []): array
    {
        return array_merge(
            [
                "X-Gravitee-Api-Key" => "{$this->getAuthToken()}",
                "Accept" => "application/json",
                "Content-Type" => "application/json",
            ],
            $headers
        );
    }
    /**
     * Perform a http call
     *
     * @param string $httpMethod
     * @param string $url
     * @param string|null $httpBody
     * @throws GuzzleException
     * @return ResponseInterface
     */
    public function performHttpCallToFullUrl($httpMethod, $url, $headers = [], $httpBody = null): ResponseInterface
    {

        $options = [
            "verify" => $this->verify_ssl,
            "headers" => $this->mergeRequestHeaders($headers)
        ];

        if (!is_null($httpBody)) {
            if ($options['headers']['Content-Type'] == "application/json")
                $options['json'] = $httpBody;
            else
                $options['body'] = $httpBody;
        }

        return $this->httpClient->request($httpMethod, $url, $options);
    }
}
