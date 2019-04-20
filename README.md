Slip Router
============

Slip Router allows you to add namespaces to your Slim routes and route groups. 

## Install

Via Composer

``` bash
$ composer require slip/router
```

## Usage

You need to replace default Slim router with Slip router:

``` php

$app = new \Slim\App();
$container = $app->getContainer();

$container['router'] = function ($container) {
    $routerCacheFile = false;

    if (isset($container->get('settings')['routerCacheFile'])) {
        $routerCacheFile = $container->get('settings')['routerCacheFile'];
    }

    $router = (new \Slip\Routing\Router())->setCacheFile($routerCacheFile);
        
    $router->setContainer($container);

    return $router;
};

```

Then you can set your routes namespaces

``` php

$app->get('/profile/user/{id}', 'UserController:getProfile')->setNamespace('App\Http\Controllers');

```

Or you can add namespace to the whole route group

``` php

$app->group('/profile/user', function ($app) {
    $app->get('/{id}, 'UserController:getProfile');
})->setNamespace('App\Http\Controllers');

```

Nested route groups also support namespaces

``` php

$app->group('/profile', function ($app) {
    $app->group('/user', function ($app) {
        $app->get('/{id}, 'UserController:getProfile');
    })->setNamespace('Http\Controllers');
})->setNamespace('App');

```

Route namespace appends to route groups namespaces

``` php

$app->group('/profile', function ($app) {
    $app->group('/user', function ($app) {
        $app->get('/{id}, 'UserController:getProfile')->setNamespace('Controllers');
    })->setNamespace('Http');
})->setNamespace('App');

```

## Testing

``` bash
$ php ./vendor/bin/phpunit
```

## License

The MIT License (MIT).
