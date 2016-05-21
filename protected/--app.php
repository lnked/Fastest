<?php

// use FCMS\Mvc\App;

final class Router {
    
    public $routes = [
        'GET'       => [],
        'POST'      => [],
        'PUT'       => [],
        'DELETE'    => [],
        'PATCH'     => [],
        'HEAD'      => [],
        'OPTIONS'   => []
    ];

    public $base = '/';

    public function __construct(array $routes = [])
    {
        if (!empty($routes))
        {
            $this->routes = $routes;
        }

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

    public function add($pattern, $method, callable $callback, array $options = [])
    {
        if (is_string($callback) && strpos($callback,':') !== false)
        {
            $callback = explode(':', $callback);
        }

        // array_push($context, action($verb, $path, $func))

        $route = [
            'pattern'   => $pattern,
            'callback'  => $callback,
            'options'   => $options
        ];

        $this->routes[$method][] = $route;
        
        // echo '<pre>';
        // exit(print_r($this->routes));

        // // compile place holder to patterns
        // $pcre = strpos($pattern,':') !== false;
        // if ( $pcre ) {
        //     $routeArgs = PatternCompiler::compile($pattern, $options);
        //     // generate a pcre pattern route
        //     $route = array(
        //         true, // PCRE
        //         $routeArgs['compiled'],
        //         $callback,
        //         $routeArgs,
        //     );
        //     if ( isset($options['id']) ) {
        //         $this->routesById[ $options['id'] ] = $route;
        //     }
        //     return $this->routes[] = $route;
        // } else {
        //     $route = array(
        //         false,
        //         $pattern,
        //         $callback,
        //         $options,
        //     );
        //     if ( isset($options['id']) ) {
        //         $this->routesById[ $options['id'] ] = $route;
        //     }
        //     // generate a simple string route.
        //     return $this->routes[] = $route;
        // }
    }

    public function route($method = '', $path = '', callable $cb)
    {
        $this->{strtolower($method)}($path, $cb);
    }

    // public function match($path)
    // {
    //     $requestMethod = null;
    //     if (isset($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'])) {
    //         $requestMethod = self::getRequestMethodConstant($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE']);
    //     } else if (isset($_SERVER['REQUEST_METHOD'])) {
    //         $requestMethod = self::getRequestMethodConstant($_SERVER['REQUEST_METHOD']);
    //     }
    //     foreach( $this->routes as $route ) {
    //         if ( $route[0] ) {
    //             if ( ! preg_match($route[1], $path , $regs ) ) {
    //                 continue;
    //             }
    //             $route[3]['vars'] = $regs;
    //             // validate request method
    //             if ( isset($route[3]['method']) && $route[3]['method'] != $requestMethod )
    //                 continue;
    //             if ( isset($route[3]['domain']) && $route[3]['domain'] != $_SERVER["HTTP_HOST"] )
    //                 continue;
    //             if ( isset($route[3]['secure']) && $route[3]['secure'] && (!isset($_SERVER["HTTPS"]) || !$_SERVER["HTTPS"]) )
    //                 continue;
    //             return $route;
    //         } else {
    //             // prefix match is used when expanding is not enabled.
    //             if ( ( is_int($route[2]) && strncmp($route[1], $path, strlen($route[1]) ) === 0 ) || $route[1] == $path ) {
    //                 // validate request method
    //                 if ( isset($route[3]['method']) && $route[3]['method'] != $requestMethod )
    //                     continue;
    //                 if ( isset($route[3]['domain']) && $route[3]['domain'] != $_SERVER["HTTP_HOST"] )
    //                     continue;
    //                 if ( isset($route[3]['secure']) && $route[3]['secure'] && (!isset($_SERVER["HTTPS"]) || !$_SERVER["HTTPS"]) )
    //                     continue;
    //                 return $route;
    //             } else {
    //                 continue;
    //             }
    //         }
    //     }
    // }

    private function match()
    {
        echo '<pre>';

        // exit(print_r($this->routes));
        
        $page = array_map(function($i){
            // echo '<br>', print_r( $i ), '<br>';
        }, $this->routes[$this->method]);
        
        echo '<br>';
        echo 'method : ', $this->method;
        echo '<br>';
        echo '<br>';
        echo '<pre>';

        $pattern = '('.implode('|', $needles_esc).')';
        if ($flags & STRPOSM_CI) $pattern .= 'i';
        if (preg_match($pattern, $haystack, $matches, PREG_OFFSET_CAPTURE, $offset)) {
        }



        // print_r($this->routes);

        print_r($this->request, $this->method);

        foreach ($this->routes[$this->method] as $test)
        {
            // $match = preg_match($test['pattern'], $this->request);
            
            // preg_match($test['pattern'], $this->request, $matches, PREG_OFFSET_CAPTURE, 3); 
            
            // print_r('match', $matches);

            // echo print_r($test), ' as ', $this->request, '<br>';
      
            $rexp = preg_replace('@:(\w+)@', '(?<\1>[^/]+)', $test['pattern']);

            if (preg_match("@^{$rexp}$@", $this->request, $caps))
            {
                echo 'test: ';
                print_r($test);
             
                echo 'caps: ';
                print_r($caps);

                return [$test['callback'], array_slice($caps, 1)];
                // return [$test['callback'], array_slice($caps, 1)];
            }
        }

        return [];
    }

    public function dispatch($path = null)
    {
        $this->request = !is_null($path) ? $path : $this->request;

        $match = $this->match();
        
        echo '<br><br>';

        print_r($match);

        if (!empty($match))
        {
            $match[0]($match[1]);
        }
    }

    // public function dispatch($path)
    // {
    //     if ($route = $this->match($path) ) {
    //         if (is_int($route[2])) {
    //             $submux = $this->submux[ $route[2] ];
    //             // sub-path and call submux to dispatch
    //             // for pcre pattern?
    //             if ($route[0]) {
    //                 $matchedString = $route[3]['vars'][0];
    //                 return $submux->dispatch( substr($path, strlen($matchedString)) );
    //             } else {
    //                 $s = substr($path, strlen($route[1]));
    //                 return $submux->dispatch(
    //                     substr($path, strlen($route[1])) ?: ''
    //                 );
    //             }
    //         }
    //         return $route;
    //     }
    // }

    public function getRoutes()
    {
        return $this->routes;
    }

    public function setRoutes($routes)
    {
        $this->routes = $routes;
    }

    public function setBase($base = '/')
    {
        $this->base = $base;
    }

    // public static function __set_state($array)
    // {
    //     $mux = new self;
    //     $mux->routes = $array['routes'];
    //     $mux->submux = $array['submux'];
    //     $mux->expand = $array['expand'];
    //     if ( isset($array['routesById']) ) {
    //         $mux->routesById = $array['routesById'];
    //     }
    //     $mux->id = $array['id'];
    //     return $mux;
    // }
}