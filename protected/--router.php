<?php declare(strict_types=1);

$app = new Router();

$app->route('GET', '/api/about', function () {
    echo 'about page';
});

// Получение всех роботов
// $app->add('/page', [ 'PageController', 'page1' ]);

$app->get('/?page', function () {
    echo 'main page';
});

$app->get('/product/?', function () {
    echo 'product page';
});

$app->get('/product/{id:[0-9]+}/{item:(\w+)}', function ($id = 0, $item = 0) {
    print_r($id);
    print_r($item);
});

// Получение всех роботов
$app->get('/api/robots', function () {

});

// Поиск роботов с $name в названии
$app->get('/api/robots/search/{name}', function ($name) {

});

// Получение робота по первичному ключу
$app->get('/api/robots/{id:[0-9]+}', function ($id) {

});

// Добавление нового робота
$app->post('/api/robots', function () {

});

// Обновление робота по первичному ключу
$app->put('/api/robots/{id:[0-9]+}', function () {

});

// Удаление робота по первичному ключу
$app->delete('/api/robots/{id:[0-9]+}', function () {

});

$app->dispatch();


// $route2 = new Route('/blog/{id}/', $endPoint2, ['id' => '\d+']);

// $route = new Route('/{lang}/users/{id}/{action}/', $endPoint, [
//     'lang' => 'ru|en',
//     'action' => '[a-z0-9_]+',
//     'id' => '\d+',
// ], Route::ONLY_XHR);



// // The id must be a number
// $router->add('/user/{n:id}', 'GET', 'App\Controllers\UserController::show');

// // The id must contain either alfabetic chars or numbers
// $router->add('/user/{an:id}', 'GET', 'App\Controllers\UserController::show');

// // Now we want everything behind docs/ in the page variable
// // For example when we go to the url /docs/user/edit we will receive user/edit in the page variable
// $router->add('/docs/{*:page}', 'GET', function($page) {
//     // do something with $page
// });

// // Optional parameter example
// $router->add('/hello/{a:name}/{?:lastname}', 'GET', function($name, $lastname = null) {
//     // check if lastname is provided
//     // if ($lastname) {...}
// })


// /**
//  * initialize the router class
//  */
// $router = new Router();

// /**
//  * Route matches the url '/' for the GET method
//  */
// $router->add('/', 'GET', function() {
//     // the closure will be executed when route is triggerd and will return 'hello world'
//     return 'hello world!'; 
// });

// /**
//  * It is posible to add one or multiple wildcards in one route
//  */
// $router->add('/user/{id}', 'GET', function($id) {
//     return $id;
// });

// /**
//  * It is also posible to allow mulitple methods for one route (methods should be separated with a '|')
//  */
// $router->add('/user/{id}/edit', 'GET|POST', function($id) {
//     return 'user id: '.$id;
// });

// *
//  * Or when u are using controllers in a namespace you could give the full path to a controller (controller::action)
 
// $router->add('/user/{id}/delete', 'DELETE', 'App\Controllers\UserController::delete');

// /**
//  * When all the controller are in the same namespace you could set the default namespace like so
//  */
// $router->setNamespace('App\\Controllers\\');

// /**
//  * The route now uses the default namespace + the given namespace
//  */
// $router->add('/user/{id}/update', 'PUT', 'UserController::update');

// /**
//  * After all the routes are created the resolver must be initialized
//  */
// $resolver = new RouteResolver($router);

// /**
//  * resolve the route and receive the response
//  */
// $response = $resolver->resolve([
//     'uri' => $_SERVER['REQUEST_URI'],
//     'method' => $_SERVER['REQUEST_METHOD'],
// ]);

// # sample JSON end point
// get('/books.json', function ($db, $config) {
//   $list = loadAllBooks($db);
//   $json = json_encode($list);
//   return response($json, 200, ['content-type' => 'application/json']);
// });

// # html end point
// get('/books/:id', function ($args, $db) {
//   // $book = loadBookById($db, $args['id']);
    
//     $book = $args['id'];
    
//     $html = phtml(__DIR__.'/views/book', ['book' => $book]);
//     return response($html);
// });

// # respond using a template
// get('/about', page(__DIR__.'/views/about'));
// get('/catalog', page(__DIR__.'/views/catalog'));

// # sample dependencies
// $config = require __DIR__.'/config.php';
// // $db = createDBConnection($config['db']);

// # arguments you pass here get forwarded to the route actions
// // dispatch($db, $config);

// dispatch($_SERVER['REQUEST_URI']);


// # create a stack of actions
// $routes = [
//   action('GET', '/books.json', function ($db, $config) {
//     $list = loadAllBooks($db);
//     $json = json_encode($list);
//     return response($json, 200, ['content-type' => 'application/json']);
//   }),
//   action('GET', '/books/:id', function ($args, $db) {
//     $book = loadBookById($db, $args['id']);
//     $html = phtml(__DIR__.'/views/book', ['book' => $book]);
//     return response($html);
//   }),
//   action('GET', '/about', page(__DIR__.'/views/about'))
// ];

// # sample dependencies
// $config = require __DIR__.'/config.php';
// $db = createDBConnection($config['db']);

// # we need the method and requested path
// $verb = $_SERVER['REQUEST_METHOD'],
// $path = $_SERVER['REQUEST_URI'],

// # serve app against verb + path, pass dependencies
// $responder = serve($routes, $verb, $path, $db, $config);

// # invoke responder to flush response
// $responder();
