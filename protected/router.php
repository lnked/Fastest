<?php declare(strict_types=1);

namespace Fastest\Router;

use \Fastest\Singleton\Singleton as Singleton;

// https://www.w3.org/Protocols/rfc2616/rfc2616-sec5.html#sec5.1.1

/**
 *
 *  Fastest Router for PHP
 *
 *  @author lnked https://github.com/lnked
 *
 *  @version 0.0.2
 *
 */
class Router
{
    use Singleton;

    /**
    * @var string  contain the REQUEST_METHOD
    */
    private static $method;

    /**
    *  @var string   contain the base dir
    */
    
    private static $baseDir;
    
    /**
    * @var string  contain the REQUEST_URI
    */
    private static $request;

    /**
    * @var string  contain the temporary value to add when you use the static method group
    */
    private static $routes = [];
  
    /**
    * Initialize routing
    */
    public function __construct(array $routes = [])
    {
        static::$routes = $routes ?? static::$routes;
    }

    public function get($route, $callback)
    {
        $this->add(['GET'], $route, $callback);
    }

    public function post($route, $callback)
    {
        $this->add(['POST'], $route, $callback);
    }

    public function put($route, $callback)
    {
        $this->add(['PUT'], $route, $callback);
    }

    public function delete($route, $callback)
    {
        $this->add(['DELETE'], $route, $callback);
    }

    public function patch($route, $callback)
    {
        $this->add(['PATCH'], $route, $callback);
    }

    public function add($methods, $pattern = '', $callback = null)
    {
        // preg_match('/(?P<name>\w+): (?P<digit>\d+)/', $url);
        // preg_match('/(?<name>\w+): (?<digit>\d+)/', $str, $matches);
        // preg_match('/^[a-z0-9_.\/\\\]*$/i', $file_string);

        preg_match('/(?P<name>\w+): (?P<digit>\d+)/', 'foobar: 2008', $matches);

        foreach ($matches as $key => $value) {
            if (is_int($key)) {
                unset($matches[$key]);
            }
        }

        echo '<pre>';
        print_r($matches);

        preg_match('/(?<name>\w+): (?<digit>\d+)/', 'foobar: 10', $matches);
        
        $matches = array_intersect_key($matches, array_flip(array_filter(array_keys($matches), 'is_string')));

        print_r($matches);

        exit;

        if (is_array($methods)) {
            foreach ($methods as $method) {
                if (empty(static::$routes[$method])) {
                    static::$routes[$method] = [];
                }

                array_push(static::$routes[$method], [
                    'pattern' => $pattern,
                    'callback' => $callback
                ]);
            }
        }
        else {
            if (empty(static::$routes[$methods])) {
                static::$routes[$methods] = [];
            }

            array_push(static::$routes[$methods], [
                'pattern' => $pattern,
                'callback' => $callback
            ]);
        }
    }

    private function match($path = null)
    {
        // if(strpos($string, 'fox') !== false){
        //     // do the routine
        // }
    }

    // private static function routeMatch($route) {
    // $route = trim($route, '/');
    // $parts = explode('/', $route);
    // if (count(static::$urlParts) != count($parts)) {
    //   return false;
    // }
    // $parameters = [];
    // for ($i = 0; $i < count(static::$urlParts); $i++) {
    //   $p = $parts[$i];
    //   $up = static::$urlParts[$i];
    //   if (static::$urlParts[$i] != $p) {
    //     $param = ltrim(rtrim($p, '}'), '{');
    //       if (strlen($p) - strlen($param) == 2) {
    //         //parameter found
    //         $parameters[$p] = $up;
    //       } else {
    //         return false;
    //       }
    //     }
    //   }
    //   return $parameters;
    // }
    // /**
    // * Get the current request;
    // *
    // * @return \Nozols\Router\Request
    // */
    // public static function request(){
    //   return static::$request;
    // }
 
    private function matchUrl($url = '', $pattern = '')
    {
        // exit("/^" . $pattern . "/i");

        exit( "as " . preg_match('/(?P<name>\w+): (?P<digit>\d+)/', $url, PREG_OFFSET_CAPTURE, 0) );

        // $subject = "abcdef";
        // $pattern = '/^def/';
        // preg_match($pattern, $subject, $matches, PREG_OFFSET_CAPTURE, 3);


        // $subject = "abcdef";
        // $pattern = '/^def/';
        // preg_match($pattern, substr($subject,3), $matches, PREG_OFFSET_CAPTURE);

        return preg_match("" . $pattern . "/i", $url);

        // preg_match_all('/(?:
        //     # parse variable token with separator
        //     .            # separator
        //     :([\w\d_]+)  # variable
        //     |
        //     # optional tokens
        //     \((.*)\)
        // )/x', $string, $matches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);
    }

    public function check($url = '')
    {
        echo '<pre>';

        // exit(print_r(self::$routes));

        foreach(self::$routes['GET'] as $r)
        {
            echo '<br>', $r['pattern'], '<br>';

            $mathed = $this->matchUrl($url, $r['pattern']);

            if ($mathed) {
                $color = 'green';
            }
            else {
                $color = 'red';
            }
            
            echo '<br>', '<span style="color: ' . $color . ';">' . $url . '</span>';
            echo '<br>';
        }

        echo '<br>', '<br>';

        exit;
    }

    public function dispatch($path = null, $method = '')
    {
        self::$method  = $method !== '' ? $method : strtoupper(trim($_SERVER['REQUEST_METHOD']));
        self::$request = $path ?? '/'.trim(parse_url(rawurldecode($_SERVER['REQUEST_URI']), PHP_URL_PATH), '/');

        // Strip query string (?foo=bar) and decode URI
        // if (false !== $pos = strpos($uri, '?')) {
        //     $uri = substr($uri, 0, $pos);
        // }

        // echo '<pre>';
        // foreach(self::$routes as $rot)
        // {
        //     foreach($rot as $r)
        //     {
        //         echo '<br>', $r['pattern'];
        //     }
        // }

        // echo '<pre>';
        // echo 'method: ';
        // echo self::$method;
        // echo '<br>';
        // echo 'request: ';
        // echo self::$request;
        // echo '<br>';
        // echo 'routes: ';
        // print_r(self::$routes);
        // echo '<br>';
        
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

    // public static function run($c) {
    //     $route = static::$routes[count(static::$routes) - $c];
    //     if ($route['method'] == static::$method) {
    //       $routematch = static::routeMatch($route['route']);
    //       if (is_array($routematch)) {
    //         static::$hasFound = true;
    //         call_user_func_array($route['callback'], $routematch);
    //       }
    //     }
    //   }

    /**
     *  Set the base Directory
     *
     *  @param string $string   Set the value of base directory
     *
     *  @return void
     */
    public static function baseDir($string)
    {
        self::$baseDir = $string;
    }

    private function export()
    {
        var_export($this->routes);
    }
}