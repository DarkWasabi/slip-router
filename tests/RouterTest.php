<?php

namespace Slip\Router\Tests;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Slip\Routing\Router;
use Slim\App;
use Slim\Http\Environment;
use Slim\Http\Headers;
use Slim\Http\Request;
use Slim\Http\RequestBody;
use Slim\Http\Response;
use Slim\Http\Uri;

class RouterTest extends TestCase
{
    /**
     * @var App
     */
    private $app;


    /**
     * Setup test case.
     */
    protected function setUp(): void
    {
        $this->app = new App();

        $this->setUpRouter();
        $this->defineRoutes();
    }

    /**
     * @test Test single route namespace.
     */
    public function testRouteNamespace()
    {
        $response = $this->getQuery('/route');

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test Test route group namespace.
     */
    public function testGroupNamespace()
    {
        $response = $this->getQuery('/group');

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test Test nested route group namespace.
     */
    public function testNestedNamespace()
    {
        $response = $this->getQuery('/group/nested');

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * @test Test combined namespace.
     */
    public function testCombinedNamespace()
    {
        $response = $this->getQuery('/group/combined');

        $this->assertEquals(200, $response->getStatusCode());
    }

    /**
     * Setup router.
     */
    private function setUpRouter()
    {
        $container = $this->app->getContainer();

        $container['router'] = function ($container) {
            $router = (new Router())->setCacheFile(false);
            $router->setContainer($container);

            return $router;
        };
    }

    /**
     * Define application routes.
     */
    private function defineRoutes()
    {
        $this->app->get('/route', 'TestController:route')->setNamespace('Slip\Routing\Tests');

        $this->app->group('/group', function (App $app) {
            $app->get('', 'TestController:group');
        })->setNamespace('Slip\Routing\Tests');

        $this->app->group('/group', function (App $app) {
            $app->group('/nested', function (App $app) {
                $app->get('', 'TestController:nested');
            })->setNamespace('Tests');
        })->setNamespace('Slip\Routing');

        $this->app->group('/group', function (App $app) {
            $app->group('/combined', function (App $app) {
                $app->get('', 'TestController:nested')->setNamespace('Tests');
            })->setNamespace('Routing');
        })->setNamespace('Slip');
    }

    public function getQuery($uri, array $data = [], array $headers = []) : ResponseInterface
    {
        return $this->query('GET', $uri, $data, $headers);
    }

    private function query(string $method, string $uri, array $data = [], array $headers = []) : ResponseInterface
    {
        $request = $this->requestFactory($method, $uri, $data, $headers);

        // Invoke app
        return $this->app->process($request, new Response());
    }

    private function requestFactory($method, $uri, array $data = [], array $additionalHeaders = [])
    {
        $env = Environment::mock();

        if ($method === 'GET' && $data) {
            $uri .= '?' . http_build_query($data);
        }

        $uri = Uri::createFromString($uri);

        $headers = Headers::createFromEnvironment($env);

        if (!empty($additionalHeaders)) {
            foreach ($additionalHeaders as $key=> $value) {
                $headers->add($key, $value);
            }
        }

        $cookies = [];
        $serverParams = $env->all();
        $body = new RequestBody();

        // Create Request object
        $request = new Request($method, $uri, $headers, $cookies, $serverParams, $body);

        if ($method !== 'GET' && $data) {
            $request = $request->withParsedBody($data);
        }

        return $request;
    }
}
