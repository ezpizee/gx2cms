<?php

namespace GX2CMSJoomla;

use Ezpizee\MicroservicesClient\Client;
use Ezpizee\Utils\Request;
use Ezpizee\Utils\RequestEndpointValidator;
use GX2CMSJoomla\ContextProcessors\BaseContextProcessor;

class ApiClient
{
    /**
     * @var Client
     */
    private $microserviceClient;
    private $endpoints = [
        '/api/v1/joomla/refresh/token' => 'GX2CMSJoomla\ContextProcessors\RefreshToken',
        '/api/v1/joomla/expire-in' => 'GX2CMSJoomla\ContextProcessors\ExpireIn',
        '/api/v1/joomla/authenticated-user' => 'GX2CMSJoomla\ContextProcessors\AuthenticatedUser',
        '/api/v1/joomla/crsf-token' => 'GX2CMSJoomla\ContextProcessors\CRSFToken',
        '/api/v1/joomla/user/profile' => 'GX2CMSJoomla\ContextProcessors\User\Profile'
    ];

    public function __construct(Client $client)
    {
        $this->microserviceClient = $client;
    }

    public function load(string $uri): array
    {
        $uri = str_replace('//', '/', '/'.$uri);
        RequestEndpointValidator::validate($uri, $this->endpoints);
        $namespace = RequestEndpointValidator::getContextProcessorNamespace();
        $class = new $namespace();
        if ($class instanceof BaseContextProcessor) {
            $class->setMicroServiceClient($this->microserviceClient);
            $request = new Request();
            $class->setRequest($request);
            return $class->getContext();
        }
        return ['code'=>404, 'message'=>'Invalid namespace: '.$namespace, 'data'=>null];
    }
}
