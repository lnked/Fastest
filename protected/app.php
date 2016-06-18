<?php declare(strict_types=1);

use Fastest\Router\Router as Router;

$r = new Router();

// $dispatcher = FastRoute\cachedDispatcher(function(FastRoute\RouteCollector $r) {
//     $r->add('GET', '/user/{name}/{id:[0-9]+}', 'handler0');
//     $r->add('GET', '/user/{id:[0-9]+}', 'handler1');
//     $r->add('GET', '/user/{name}', 'handler2');
// }, [
//     'cacheFile' => __DIR__ . '/route.cache', /* required */
//     'cacheDisabled' => IS_DEBUG_ENABLED,     /* optional, enabled by default */
// ]);

// $r->add('GET', '/api/about', function () {
//     echo 'about page';
// });

// $r->add('/add_page', [ 'PageController', 'page1' ]);

// $r->get('/get', ['HelloController','helloAction']);
// $r->post('/post', ['HelloController','helloAction']);
// $r->put('/put', ['HelloController','helloAction']);

// $r->any('/product', ['ProductController','listAction']);
// $r->get('/product/:id', ['ProductController','itemAction'] , [
//     'require' => [ 'id' => '\d+', ],
//     'default' => [ 'id' => '1', ]
// ]);
// $r->post('/product/:id', ['ProductController','updateAction'] , [
//     'require' => [ 'id' => '\d+', ],
//     'default' => [ 'id' => '1', ]
// ]);
// $r->delete('/product/:id', ['ProductController','deleteAction'] , [
//     'require' => [ 'id' => '\d+', ],
//     'default' => [ 'id' => '1', ]
// ]);

// $r->get('/get', ['HelloController','helloAction']);



// // Matches /user/42, but not /user/xyz
// $r->add('GET', '/user/{id:\d+}', 'handler');

// // Matches /user/foobar, but not /user/foo/bar
// $r->add('GET', '/user/{name}', 'handler');

// // Matches /user/foo/bar as well
// $r->add('GET', '/user/{name:.+}', 'handler');

// // This route
// $r->add('GET', '/user/{id:\d+}[/{name}]', 'handler');
// // Is equivalent to these two routes
// $r->add('GET', '/user/{id:\d+}', 'handler');
// $r->add('GET', '/user/{id:\d+}/{name}', 'handler');

// // Multiple nested optional parts are possible as well
// $r->add('GET', '/user[/{id:\d+}[/{name}]]', 'handler');

// // This route is NOT valid, because optional parts can only occur at the end
// $r->add('GET', '/user[/{id:\d+}]/{name}', 'handler');

// $r->add('GET', '/user/{name}/{id:[0-9]+}', 'handler0');
// $r->add('GET', '/user/{id:[0-9]+}', 'handler1');
// $r->add('GET', '/user/{name}', 'handler2');

// $r->get('/', function() { echo 'HTTP METHOD : GET';});
// $r->post('/', function() { echo 'HTTP METHOD : POST';});
// $r->put('/', function() { echo 'HTTP METHOD : PUT';});
// $r->delete('/', function() { echo 'HTTP METHOD : DELETE';});
// $r->add('/', function() { echo 'HTTP METHOD : CUSTOM';}, 'CUSTOM');

// $r->get(
//     array(
//         array(
//             "type" => "regex",
//             "pattern" => "",
//             "action" => "Foo",
//             "tokens" = array(
//                 "group",
//                 "id"
//             )
//         )
//     )
// );

// ':d}'   => ':[0-9]++}',             // digit only
// ':l}'   => ':[a-z]++}',             // lower case
// ':u}'   => ':[A-Z]++}',             // upper case
// ':a}'   => ':[0-9a-zA-Z]++}',       // alphanumeric
// ':c}'   => ':[0-9a-zA-Z+_\-\.]++}', // common chars
// ':nd}'  => ':[^0-9/]++}',           // not digits
// ':xd}'  => ':[^0-9/][^/]*+}',       // no leading digits

$r->get('/user/{action:\w}/{id:\d}', 'handler1');

$r->get('/section1/{id}/{page}/', 'handler1');
$r->get('/section1/{id}/', 'handler1');

$r->get('/user/{action:[^0-9/][^/]*}/{id:[0-9]+}', 'handler1');

$r->get('/product/{id}/{item}', function ($id = 0, $item = 0) {
    print_r($id);
    print_r($item);
});

$r->get('/api/robots/search/{name}', function ($name) {

});

$r->get('/api/robots/{id:[0-9]+}', function ($id) {

});

// // This route
// $r->addRoute('GET', '/user/{id:\d+}[/{name}]', 'handler');
// // Is equivalent to these two routes
// $r->addRoute('GET', '/user/{id:\d+}', 'handler');
// $r->addRoute('GET', '/user/{id:\d+}/{name}', 'handler');

// // Multiple nested optional parts are possible as well
// $r->addRoute('GET', '/user[/{id:\d+}[/{name}]]', 'handler');

// // This route is NOT valid, because optional parts can only occur at the end
// $r->addRoute('GET', '/user[/{id:\d+}]/{name}', 'handler');

// // Matches /user/42, but not /user/xyz
// $r->addRoute('GET', '/user/{id:\d+}', 'handler');

// // Matches /user/foobar, but not /user/foo/bar
// $r->addRoute('GET', '/user/{name}', 'handler');

// // Matches /user/foo/bar as well
// $r->addRoute('GET', '/user/{name:.+}', 'handler');

// // This route
// $r->addRoute('GET', '/user/{id:\d+}[/{name}]', 'handler');
// // Is equivalent to these two routes
// $r->addRoute('GET', '/user/{id:\d+}', 'handler');
// $r->addRoute('GET', '/user/{id:\d+}/{name}', 'handler');

// // Multiple nested optional parts are possible as well
// $r->addRoute('GET', '/user[/{id:\d+}[/{name}]]', 'handler');

// // This route is NOT valid, because optional parts can only occur at the end
// $r->addRoute('GET', '/user[/{id:\d+}]/{name}', 'handler');

// $r->addRoute('GET', '/user/{name}/{id:[0-9]+}', 'handler0');
// $r->addRoute('GET', '/user/{id:[0-9]+}', 'handler1');
// $r->addRoute('GET', '/user/{name}', 'handler2');

$r->add(
    "/admin/users/my-profile",
    array(
        "controller" => "users",
        "action"     => "profile"
    )
);

$r->add(
    "/admin/:controller/a/:action/:params",
    array(
        "controller" => 1,
        "action"     => 2,
        "params"     => 3
    )
);

$r->add(
    "/admin/users/change-password",
    array(
        "controller" => "users",
        "action"     => "changePassword"
    )
);

$r->get('/api/robots/{id:[0-9]+}', function ($id) {

});

$r->get('/foo/(\d+)/(\d+)/', 'handler1');

$r->get('/(\d+)', function () {
    echo 'product page';
});

$r->get('/product/?', function () {
    echo 'product page';
});

$r->post('/?wewe/asd', function () {
    echo 'main page';
});

// Получение всех роботов
$r->get('/api/robots', function () {

});

// Поиск роботов с $name в названии
$r->get('/api/robots/search/{name}', function ($name) {

});

// Получение робота по первичному ключу
$r->get('/api/robots/{id:[0-9]+}', function ($id) {

});

$r->get('/проверка/КирилЛицы/', function () {

});

// Добавление нового робота
$r->post('/api/robots', function () {

});

// Обновление робота по первичному ключу
$r->put('/api/robots/{id:[0-9]+}', function () {

});

// Удаление робота по первичному ключу
$r->delete('/api/robots/{id:[0-9]+}', function () {

});

// // Matches /user/42, but not /user/xyz
// $r->add('GET', '/user/{id:\d+}', 'handler');

// // Matches /user/foobar, but not /user/foo/bar
// $r->add('GET', '/user/{name}', 'handler');

// // Matches /user/foo/bar as well
// $r->add('GET', '/user/{name:.+}', 'handler');


// // This route
// $r->add('GET', '/user/{id:\d+}[/{name}]', 'handler');
// // Is equivalent to these two routes
// $r->add('GET', '/user/{id:\d+}', 'handler');
// $r->add('GET', '/user/{id:\d+}/{name}', 'handler');

// // Multiple nested optional parts are possible as well
// $r->add('GET', '/user[/{id:\d+}[/{name}]]', 'handler');

// // This route is NOT valid, because optional parts can only occur at the end
// $r->add('GET', '/user[/{id:\d+}]/{name}', 'handler');

$route = $r->dispatch();

$check = array(
    '/foo/10/',
    '/foo/10/10200/',
    '/user/230/10/',
    '/user/asd/1',
    '/100',
    '/1200/',
    '/product/120',
    'product/10/asdasd/',
    '/api/robots',
    '/api/robots/search/ed',
    '/api/robots/10',
    '/проверка/кириллицы',
    '/ПРОВЕРКА/КИРИЛЛИЦЫ',
    '/asd',
    '/wewe/asd'
);

foreach ($check as $uri) {
    $r->check($uri);
}

// /foo/(\d+)/(\d+)/
// /user/{action:[^0-9/][^/]*}/{id:[0-9]+}
// /user/{action:xd}/{id:d}
// /(\d+)
// /product/?
// /product/:id/:item
// /api/robots
// /api/robots/search/{name}
// /api/robots/{id:[0-9]+}
// /проверка/КирилЛицы/
// /?wewe/asd
// /api/robots
// /api/robots/{id:[0-9]+}
// /api/robots/{id:[0-9]+}

echo '<pre>';
print_r($route);
echo '</pre>';