<?php declare(strict_types=1);

$app = new App();

$app->route('GET', '/api/about', function () {
    echo 'about page';
});

// Получение всех роботов
// $app->add('/page', [ 'PageController', 'page1' ]);

$app->get('/?wewe', function () {
    echo 'main page';
});

$app->get('/product/?', function () {
    echo 'product page';
});

$app->get('/product/:id/:item', function ($id = 0, $item = 0) {
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
