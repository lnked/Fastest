<?php declare(strict_types=1);

class Router
{
    use Singleton;

    public  $base       = '/';

    private $routes     = [];
    private $request    = null;
    private $method     = null;
    
    public function __construct(array $routes = [])
    {
        $this->routes = $routes ?? $this->routes;


        $this->method = strtoupper(trim($_SERVER['REQUEST_METHOD']));
        $this->request = '/'.trim(parse_url(rawurldecode($_SERVER['REQUEST_URI']), PHP_URL_PATH), '/');
    }

    public function get($pattern, callable $callback)
    {
        $this->add($pattern, 'GET', $callback);
    }

    public function post($pattern, callable $callback)
    {
        $this->add($pattern, 'POST', $callback);
    }

    public function put($pattern, callable $callback)
    {
        $this->add($pattern, 'PUT', $callback);
    }

    public function delete($pattern, callable $callback)
    {
        $this->add($pattern, 'DELETE', $callback);
    }

    public function patch($pattern, callable $callback)
    {
        $this->add($pattern, 'PATCH', $callback);
    }

    public function add($pattern = '', $method = '', callable $callback, array $options = [])
    {
        exit($this->method . ' as ' . $this->request);
    }

    public function match($path = null)
    {
        // if(strpos($string, 'fox') !== false){
        //     // do the routine
        // }

    }

    public function dispatch($path = null)
    {

        exit(print_r($this->routes));
        
        // if ($route = $this->match($path) ) {
        //     if (is_int($route[2])) {
        //         $submux = $this->submux[ $route[2] ];
        //         // sub-path and call submux to dispatch
        //         // for pcre pattern?
        //         if ($route[0]) {
        //             $matchedString = $route[3]['vars'][0];
        //             return $submux->dispatch( substr($path, strlen($matchedString)) );
        //         } else {
        //             $s = substr($path, strlen($route[1]));
        //             return $submux->dispatch(
        //                 substr($path, strlen($route[1])) ?: ''
        //             );
        //         }
        //     }
        //     return $route;
        // }
    }
    
    private function is_ajax()
    {
        return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
    }

    private function export()
    {
        var_export($this->routes);
    }
}