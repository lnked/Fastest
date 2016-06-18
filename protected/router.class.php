<?php
/**
 * PHP Request Router
 *
 * This script allows for simple or advanced routing capabilities. Useful for putting together a simple site 
 * where you want to handle URL routing directly to files or classes.
 *
 * PHP version 5
 *
 * LICENSE: Permission is hereby granted, free of charge, to any person obtaining a 
 * copy of this software and associated documentation files (the "Software"), to deal 
 * in the Software without restriction, including without limitation the rights to use, 
 * copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the 
 * Software, and to permit persons to whom the Software is furnished to do so, subject 
 * to the following conditions:
 * The above copyright notice and this permission notice shall be included in all copies 
 * or substantial portions of the Software. THE SOFTWARE IS PROVIDED "AS IS", WITHOUT 
 * WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES 
 * OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT 
 * SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER 
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF 
 * OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 * @author     Jonathan Sharp <jdsharp.com>
 * @copyright  Copyright (c) 2012 Jonathan Sharp
 * @license    MIT 
 * @link       http://github.com/jdsharp/php-route
 */

define('HTTP_GET',    1);
define('HTTP_POST',   2);
define('HTTP_PUT',    4);
define('HTTP_DELETE', 8);
define('HTTP_HEAD',   16);
define('FILE_UPLOAD', 32);

class Route
{
    public static $debug = false;
    private static $request = null;
    private static $routes  = array();

    public static function init()
    {
        $request = new RouteRequest();
        self::set($request);
    }

    public static function set($httpVerb, $url = null, $data = null)
    {
        $args = func_get_args();

        if ( !is_object($args[0]) ) {
            $request = new RouteRequest();
            if ( is_int($args[0]) ) {
                $request->httpVerb = array_shift($args);
            }

            if ( is_string($args[0]) ) {
                $request->url = array_shift($args);
            }

            if ( is_array($args[0]) ) {
                $request->data = array_shift($args);
            }
            self::$request = $request;
        } else {
            self::$request = $args[0];
        }
    }

    public static function request()
    {
        return self::$request;
    }

    public static function run($httpVerb = null, $url = null, $params = null)
    {
        if ( self::$request === null ) {
            return false;
        }
        if ( self::$debug ) {
            header('Content-type: text/plain');
            print_r(self::$routes);
        }

        // Check the http verb
        $httpVerbs = array(
            'GET'    => HTTP_GET,
            'POST'   => HTTP_POST,
            'PUT'    => HTTP_PUT,
            'DELETE' => HTTP_DELETE,
            'HEAD'   => HTTP_HEAD
        );
        
        $filters  = array();
        $handlers = array();

        $r = self::$request;
        foreach ( self::$routes AS $i => $route ) {
            $pass = true;
            if ( self::$debug )
                echo "Route Index: $i\n";
            $pass = $pass && self::testHttpVerb($route['httpVerb'], $r->httpVerb);
            if ( self::$debug )
                echo "\thttpVerb: $pass\n";
            $pass = $pass && self::testSsl($route['ssl'], $r->ssl);
            if ( self::$debug )
                echo "\tssl: $pass\n";
            $pass = $pass && self::testPort($route['port'], $r->port);
            if ( self::$debug )
                echo "\tport: $pass\n";
            $pass = $pass && self::testDomain($route['domain'], $r->domain);
            if ( self::$debug )
                echo "\tdomain: $pass\n";
            $pass = $pass && self::testUrl($route['url'], $r->url);
            if ( self::$debug )
                echo "\turl: $pass\n";
            $pass = $pass && self::testQuery($route['query'], $r->query);
            if ( self::$debug )
                echo "\tquery: $pass\n";
            $pass = $pass && self::testCookie($route['cookie'], $r->cookie);
            if ( self::$debug )
                echo "\tcookie: $pass\n";

            if ( $pass ) {
                if ( self::$debug )
                    echo "\tPASSED!\n";
                
                // Parse the URL and map the params
                if ( isset($route['params']) ) {
                    $r->params = self::mapParams($route, $r);
                }
                
                // Now determine what we need to do, if there's a filter to execute, or a handler to call
                if ( isset($route['response']['filter']) ) {
                    $filters[] = $route;
                }
                
                if ( isset($route['response']['handler']) ) {
                    $handlers[] = $route;
                }

            } else {
                if ( self::$debug )
                    echo "\tDID NOT PASS\n";
            }
        }
        
        foreach ( $filters AS $rte ) {
            self::executeFilter($rte['response']['filter'], $r, $rte);
        }
        
        foreach ( $handlers AS $handler ) {
            $ret = self::executeHandler($handler['response']['handler'], $r, $route);
            if ( $ret !== false ) {
                break;
            }
        }
        
        // Check the URL

        return true;
    }
    
    public static function executeFilter($filter, $request, $route)
    {
        if ( is_string($filter) ) {
            $FILTER = $filter;
            $REQUEST = $request;
            unset($request);
            $ROUTE   = $route;
            unset($route);
            $ret = include( $filter );
            
        } else if ( is_array($filter) ) {
            if ( is_string($filter[1]) ) {
                include_once($filter[0]);
                if ( function_exists($filter[1]) ) {
                    $ret = call_user_func($filter[1], $request, $route);
                }
            } else if ( is_array($filter[1]) ) {
                include_once($filter[0]);
                if ( class_exists($filter[1][0]) ) {
                    $obj = new $filter[1][0]();
                    if ( method_exists($obj, $filter[1][1]) ) {
                        $ret = call_user_method($filter[1][1], $obj, $request, $route);
                    }
                }
            } else if ( !isset($route['filter'][1]) ) {
                $FILTER = $filter;
                $REQUEST = $request;
                unset($request);
                $ROUTE   = $route;
                unset($route);
                $ret = include( $route['filter'][0] );
            }
        }
        return $ret;
    }
    
    public static function executeHandler($handler, $request, $route)
    {
        if ( is_string($handler) ) {
            $HANDLER = $handler;
            $REQUEST = $request;
            unset($request);
            $ROUTE   = $route;
            unset($route);
            $ret = include( $handler );
        } else if ( is_array($handler) ) {
            if ( is_string($handler[1]) ) {
                include_once($handler[0]);
                if ( function_exists($handler[1]) ) {
                    $ret = call_user_func($handler[1], $request, $route);
                }
            } else if ( is_array($handler[1]) ) {
                include_once($handler[0]);
                if ( class_exists($handler[1][0]) ) {
                    $obj = new $handler[1][0]();
                    if ( method_exists($obj, $handler[1][1]) ) {
                        $ret = call_user_method($handler[1][1], $obj, $request, $route);
                    }
                }
            } else if ( !isset($handler[1]) ) {
                $HANDLER = $handler;
                $REQUEST = $request;
                unset($request);
                $ROUTE   = $route;
                unset($route);
                $ret = include( $handler[0] );
            }
        }
        return $ret;
    }

    public static function testHttpVerb($expected, $actual)
    {
        // Ignore this test (matches for anything)
        if ( $expected === null ) {
            return true;
        }
        if ( $expected === ( $actual & $expected ) ) {
            return true;
        }
        return false;
    }

    public static function testSsl($expected, $actual)
    {
        if ( $expected === null ) {
            return true;
        }
        return $expected && $actual;
    }

    public static function testPort($expected, $actual)
    {
        if ( $expected === null ) {
            return true;
        }
        if ( is_array($expected) ) {
            return in_array($actual, $expected);
        }
        return $expected == $actual;
    }

    public static function testDomain($expected, $actual)
    {
        if ( $expected === null ) {
            return true;
        }
        if ( substr($expected, 0, 1) == '/' ) {
            return preg_match($expected, $actual);
        }
        return $expected == $actual;
    }

    public static function testUrl($expected, $actual)
    {
        if ( $expected === null ) {
            return true;
        }
        // We have a regex expression
        if ( substr($expected, 0, 1) == '^' ) {
            $preg = str_replace('/', '\/', $expected);
            if ( self::$debug )
                echo "preg_match: " . $preg . "\n";
            return preg_match('/' . $preg . '/i', $actual);
        }
        if ( self::$debug )
            echo "Expected: \"$expected\" == \"$actual\"\n";
        return $expected == $actual;
    }

    public static function testQuery($expected, $actual)
    {
        if ( $expected === null ) {
            return true;
        }
        if ( is_array($expected) ) {
            foreach ( $expected AS $k => $v ) {
                // Test if our key is optional (?key) or required (key)
                $wild = substr($k, 0, 1) == '?';
                if ( $wild ) {
                    $k = substr($k, 1);
                }
                if ( !$wild && !isset($actual[$k]) ) {
                    return false;
                }

                if ( is_string($v) ) {
                    if ( substr($v, 0, 1) == '/' ) {
                        $test = !isset($actual[$k]) || preg_match($v, $actual[$k]);
                    } else {
                        $test = !isset($actual[$k]) || ( $v == $actual[$k] );
                    }
                } else if ( is_array($v) ) {
                    $test = !isset($actual[$k]) || in_array($actual[$k], $v);
                }

                if ( !$test ) {
                    return false;
                }
            }
            return true;
        }
        return false;
    }

    public static function testCookie($expected, $actual)
    {
        return self::testQuery($expected, $actual);
    }

    public static function mapParams($route, $request)
    {
        $url    = $route['url'];
        if ( !isset($route['params']) ) {
            return array();
        }
        $mapped = $route['params'];

        // Handle all URL substitution
        if ( substr($url, 0, 1) == '^' ) {
            $preg = '/' . str_replace('/', '\/', $url) . '/i';
            if ( preg_match($preg, $request->url, $matched) ) {
                foreach ( $mapped AS $k => $v ) {
                    if ( is_int($k) ) {
                        if ( isset($matched[$k + 1]) ) {
                            $mapped[$v] = $matched[$k + 1];
                        }
                    } else {
                        foreach ( $matched AS $i => $mv ) {
                            $v = str_replace('$' . $i, $mv, $v);
                        }
                        $mapped[$k] = $v;
                    }
                }
            }
        }

        // Extract any query string data
        $query = array();
        foreach ( $route['query'] AS $id => $v ) {
            if ( substr($id, 0, 1) == '?' ) {
                $id = substr($id, 1);
            }

            if ( isset($request->query[$id]) ) {
                if ( is_string($v) ) {
                    if ( substr($v, 0, 1) == '/' ) {
                        if ( preg_match($v, $request->query[$id], $matched) ) {
                            $query[$id] = $matched;
                        }
                    } else {
                        $query[$id] = $request->query[$id];
                    }
                } else if ( is_array($v) && in_array($request->query[$id], $v) ) {
                    $query[$id] = $request->query[$id];
                }
            }
        }
        
        // Handle query & cookie params
        foreach ( $mapped AS $k => $rep ) {
            if ( is_int($k) ) {
                continue;
            }
            foreach ( $query AS $id => $value ) {
                if ( is_string($value) ) {
                    $rep = str_replace('$' . $id, $value, $rep);
                } else if ( is_array($value) ) {
                    foreach ( $value AS $tt => $ttt ) {
                        $rep = str_replace('$' . $id . '[' . $tt . ']', $ttt, $rep);
                    }
                }
            }
            $mapped[$k] = $rep;
        }

        // Unset any numeric keys
        foreach ( $mapped AS $k => $v ) {
            if ( is_int($k) ) {
                unset($mapped[$k]);
            }
        }
        
        return $mapped;
    }


    // This method executes and continues the routing chain
    public static function rewrite($from, $to, $status = 301)
    {
        
    }

    // add('^/recipes/(\w+)(/(\w+))?', array('product', 'filter'), 'path/to/handler.php');
    // add('/url/pattern', 'path/to/handler.php')
    // add( array('/url/pattern', 'id' => '3'), 'path/to/handler.php')
    // add(HTTP_VERB, '/url/pattern', 'path/to/handler.php')
    // add('/url/pattern', array('params','mapping'), 'path/to/handler');
    // add(HTTP_VERB, '/url/pattern', array('params','mapping'), 'path/to/handler');
    // add('/url/pattern', array('params','mapping'), array('arguments'));
    // add('/url/pattern', null, array('arguments'));
    // add(HTTP_VERB, '/url/pattern', array('params','mapping'), array('arguments'));
    /* add(array( 
        'httpVerb' => 0,
        'ssl'      => false,
        'port'     => 0,
        'domain'   => $_SERVER['name'],
        'url'      => 'path/to/this/request',
        'query'    => array(),
        'mapping'  => null
       ),
       array(
        'handler'  => ''
       ));
    */
    public static function add()
    {
        $argc = func_num_args();
        $args = func_get_args();

        $route = array(
            'id'       => null,
            'httpVerb' => null,
            'ssl'      => null,
            'port'     => null,
            'domain'   => null,
            'url'      => null,
            'query'    => null,
            'cookie'   => null,
            'params'   => null,
            'response' => null
        );

        if ( is_array($args[0]) && !isset($args[0][0]) ) {
            // We have the long array form
            $keys = array_keys($route);
            $tmp  = array_shift($args);
            foreach ( $tmp AS $k => $v ) {
                if ( in_array($k, $keys) ) {
                    $route[$k] = $v;
                }
            }

            // Set the response
            if ( is_array($args[0]) ) {
                $route['response'] = array_shift( $args );
            } else if ( is_string($args[0]) ) {
                $route['response'] = array( 'handler' => array_shift( $args ) );
            } else {
                // TODO: Throw error
            }
            self::$routes[] = $route;
            return true;
        }

        // HTTP Verb
        if ( is_int($args[0]) ) {
            $route['httpVerb'] = array_shift($args);
        }

        // URL Pattern
        if ( is_string($args[0]) ) {
            $route['url'] = array_shift($args);
        } else if ( is_array($args[0]) ) {
            // This handles query string params (or post data)
            $tmp = array_shift($args);
            $route['url'] = array_shift($tmp);
            if ( count($tmp) > 0 ) {
                $route['query'] = $tmp;
            }
        } else {
            // Throw route exception?
        }

        // Param Mapping (from URL)
        if ( is_array($args[0]) || $args[0] === null ) {
            $arg = array_shift($args);
            if ( $arg !== null ) {
                $route['params'] = $arg;
            }
        }

        // Response
        if ( is_array($args[0]) ) {
            $route['response'] = array_shift($args);
        } else if ( is_string($args[0]) ) {
            $route['response'] = array(
                'handler' => array_shift($args)
            );
        }

        self::$routes[] = $route;
        return true;
    }
}

class RouteRequest
{
    public $httpVerb = 0;
    public $ssl      = false;
    public $port     = 0;
    public $domain   = null;
    public $url      = null;
    public $query    = null;
    public $cookie   = null;
    public $params   = null;
    public $data     = null;

    public function __construct($autoInit = true)
    {
        if ( $autoInit ) {
            $this->init();
        }
    }
    
    public function init()
    {
        $httpVerbs = array(
            'GET'    => HTTP_GET,
            'POST'   => HTTP_POST,
            'PUT'    => HTTP_PUT,
            'DELETE' => HTTP_DELETE,
            'HEAD'   => HTTP_HEAD
        );
        if ( isset($httpVerbs[ $_SERVER['REQUEST_METHOD'] ]) ) {
            $this->httpVerb = $httpVerbs[ $_SERVER['REQUEST_METHOD'] ];
        } else {
            $this->httpVerb = -1;
        }

        if ( isset($_SERVER['SERVER_PORT']) ) {
            $this->port = $_SERVER['SERVER_PORT'];
        } else {
            $this->port = -1;
        }

        // TODO: Test for true SSL key
        if ( isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443 ) {
            $this->ssl = true;
        }

        if ( isset($_SERVER['SERVER_NAME']) ) {
            $this->domain = $_SERVER['SERVER_NAME'];
        }

        if ( isset($_SERVER['REQUEST_URI']) ) {
            if ( !empty($_SERVER['QUERY_STRING']) ) {
                $this->url = str_replace('?' . $_SERVER['QUERY_STRING'], '', $_SERVER['REQUEST_URI']);
            } else {
                // QUERY_STRING may not be set
                $this->url = preg_replace('/\?.*$/', '$1', $_SERVER['REQUEST_URI']);
            }
        }

        if ( isset($_SERVER['QUERY_STRING']) ) {
            $this->query = $_GET;
        }

        if ( isset($_COOKIE) ) {
            $this->cookie = $_COOKIE;
        }

        if ( isset($_REQUEST) ) {
            $this->data = $_REQUEST;
        }
    }
}
 