<?php

namespace Slip\Routing\Tests;

use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class TestController
 */
class TestController
{
    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     */
    public function route(Request $request, Response $response)
    {
        return $response;
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     */
    public function group(Request $request, Response $response)
    {
        return $response;
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     */
    public function nested(Request $request, Response $response)
    {
        return $response;
    }

    /**
     * @param Request  $request
     * @param Response $response
     *
     * @return Response
     */
    public function combined(Request $request, Response $response)
    {
        return $response;
    }
}
