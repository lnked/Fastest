<?php declare(strict_types=1);

$app = new Router;

// $app->add('GET', '/api/about', function () {
//     echo 'about page';
// });

// $app->add('/add_page', [ 'PageController', 'page1' ]);

// $app->get('/get', ['HelloController','helloAction']);
// $app->post('/post', ['HelloController','helloAction']);
// $app->put('/put', ['HelloController','helloAction']);

// $app->any('/product', ['ProductController','listAction']);
// $app->get('/product/:id', ['ProductController','itemAction'] , [
//     'require' => [ 'id' => '\d+', ],
//     'default' => [ 'id' => '1', ]
// ]);
// $app->post('/product/:id', ['ProductController','updateAction'] , [
//     'require' => [ 'id' => '\d+', ],
//     'default' => [ 'id' => '1', ]
// ]);
// $app->delete('/product/:id', ['ProductController','deleteAction'] , [
//     'require' => [ 'id' => '\d+', ],
//     'default' => [ 'id' => '1', ]
// ]);

// $app->get('/get', ['HelloController','helloAction']);

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

$route = $app->dispatch();

echo '<pre>';
print_r($route);
echo '</pre>';