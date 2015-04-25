<?php namespace Dxi;

use Dxi\Exceptions\AuthenticationException;
use Dxi\Exceptions\RequestException;
use Dxi\Commands\Command;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Message\Response;
use GuzzleHttp\Message\ResponseInterface;

class Dxi {

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * Response format, ['json','xml']
     *
     * @var
     */
    private $format;

    /**
     * @var string
     */
    private $endpoint;

    /**
     * @var Client
     */
    private $client;

    /**
     * @var string
     */
    private $authToken;

    /**
     * Init with the username and password
     *
     * @param string $username
     * @param string $password
     * @param string $format
     * @param string $endpoint
     */
    public function __construct($username, $password, $format = 'json', $endpoint = 'https://api-106.dxi.eu')
    {
        $this->username = $username;
        $this->password = $password;
        $this->format = $format;
        $this->endpoint = $endpoint;
    }

    /**
     * Get the client
     *
     * @return ClientInterface
     */
    public function getClient()
    {
        if (! $this->client) {
            $this->client = new Client();
        }

        return $this->client;
    }

    /**
     * Set the Client
     *
     * @param ClientInterface $client
     * @return $this
     */
    public function setClient(ClientInterface $client)
    {
        $this->client = $client;
        return $this;
    }

    /**
     * Get the auth token
     *
     * @param string
     */
    public function getToken()
    {
        if (! $this->authToken) {
            $url = $this->endpoint
                . '/token.php?action=get&format=json&username=' . $this->username . '&password=' . $this->password;

            $response = $this->getClient()
                ->get($url);

            $data = json_decode($response->getBody());

            if ($data->success) {
                $this->authToken = $data->token;
                $this->authExpires = $data->expire;
            } else {
                throw new AuthenticationException('Failed to get a valid auth token from the service');
            }
        }

        return $this->authToken;
    }

    /**
     * Execute command
     *
     * @param Command $command
     * @return ResponseInterface
     * @throws AuthenticationException
     * @throws Exceptions\MissingRequiredParamsException
     * @throws RequestException
     */
    public function fire(Command $command)
    {
        $getParams = [
            'token' => $this->getToken(),
            'format' => $this->format,
            'method' => $command->getMethod(),
            'action' => $command->getAction(),
            'raw' => 1
        ];

        $url = $this->endpoint .
            $command->getUrlPath() .
            '?' . http_build_query($getParams);

        try {
            $response = $this->getClient()
                ->post($url, ['body' => json_encode(array($command->getPayload()))]);

            $data = json_decode($response->getBody());

            if (! $data->success) {
                throw new RequestException($data);
            }

            return $response;

        } catch (ClientException $e) {
            return false;
        }
    }
}