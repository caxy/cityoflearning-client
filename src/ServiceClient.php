<?php

namespace Caxy\CityOfLearning;

use GuzzleHttp;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Command\CommandInterface;
use GuzzleHttp\Command\Exception\CommandException;
use GuzzleHttp\Command\Result;
use GuzzleHttp\Command\ResultInterface;
use GuzzleHttp\Psr7;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class ServiceClient.
 *
 * @method ResultInterface postBadge(array $args = []) get systems
 * @method ResultInterface getOrganizationBadges(array $args = []) get systems
 * @method ResultInterface getOrganizationBadge(array $args = []) get systems
 * @method ResultInterface putBadge(array $args = []) get systems
 * @method ResultInterface issueBadge(array $args = []) get systems
 * @method ResultInterface getPrograms(array $args = []) get systems
 * @method ResultInterface getProgram(array $args = []) get systems
 */
class ServiceClient extends GuzzleHttp\Command\ServiceClient
{
    /**
     * @var array
     */
    private $api;

    /**
     * @var array
     */
    private $adminContext;

    /**
     * BadgeKitClient constructor.
     *
     * @param ClientInterface $client
     * @param array           $adminContext
     */
    public function __construct(ClientInterface $client, array $adminContext = [])
    {
        $this->api = json_decode(file_get_contents(__DIR__.'/../res/cityoflearning.json'), true);
        parent::__construct($client, [$this, 'commandToRequestTransformer'], [$this, 'responseToResultTransformer']);
        $this->adminContext = $adminContext;
    }

    /**
     * @param CommandInterface $command
     *
     * @return RequestInterface
     */
    public function commandToRequestTransformer(CommandInterface $command)
    {
        $name = $command->getName();
        if (!isset($this->api[$name])) {
            throw new CommandException('Command not found', $command);
        }
        $action = $this->api[$name];

        $prefix = '';

        $path = GuzzleHttp\uri_template($prefix.$action['path'], array_merge($command->toArray(), $this->adminContext));

        $headers = [];
        $body = null;
        if ($command->hasParam('body')) {
            $headers = ['Content-Type' => 'application/json'];
            $body = GuzzleHttp\json_encode($command['body']);
        }

        if ($command->hasParam('query')) {
            $path .= '?'.Psr7\build_query($command['query']);
        }

        return new Psr7\Request($action['method'], $path, $headers, $body);
    }

    /**
     * @param ResponseInterface $response
     * @param RequestInterface  $request
     *
     * @return Result
     */
    public function responseToResultTransformer(ResponseInterface $response, RequestInterface $request)
    {
        $data = json_decode($response->getBody(), true);

        return new Result($data);
    }
}
