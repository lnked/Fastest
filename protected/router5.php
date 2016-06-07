<?php

// namespace App\Singleton;
define('REQUEST_METHOD_GET', 1);
define('REQUEST_METHOD_POST', 2);
define('REQUEST_METHOD_PUT', 3);
define('REQUEST_METHOD_DELETE', 4);
define('REQUEST_METHOD_PATCH', 5);
define('REQUEST_METHOD_HEAD', 6);
define('REQUEST_METHOD_OPTIONS', 7);

/**
 * Compile path string into PCRE pattern:
 *
 *   /blog/:year/:month
 *   /blog/item/:id
 *   /blog/item/:id(.:format)
 *
 */
class PatternCompiler
{
    const TOKEN_TYPE_OPTIONAL = 1;
    const TOKEN_TYPE_VARIABLE = 2;
    const TOKEN_TYPE_TEXT = 3;
    /**
     * compile pattern
     *
     * @param string $pattern
     * @param array $options
     */
    static function compilePattern($pattern, array $options = array())
    {
        $len = strlen($pattern);
        /**
         * contains:
         *
         *   array( 'text', $text ),
         *   array( 'variable', $match[0][0][0], $regexp, $var);
         *
         */
        $tokens = array();
        $variables = array();
        $pos = 0;
        /**
         *  the path like:
         *
         *      /blog/to/:year/:month
         *
         *  will be separated like:
         *      
         *      [
         *          '/blog/to',  (text token)
         *          '/:year',    (reg exp token)
         *          '/:month',   (reg exp token)
         *      ]
         */
        $matches = self::splitTokens( $pattern );
        // build tokens
        foreach ($matches as $match) {
            // match[0][1] // matched position for pattern.
            /*
             * Split tokens from abstract pattern
             * to rebuild regexp pattern.
             */
            if ($text = substr($pattern, $pos, $match[0][1] - $pos)) {
                $tokens[] = array( self::TOKEN_TYPE_TEXT, $text);
            }
            // the first char from pattern (which is the seperater)
            $seps = array($pattern[$pos]);
            $pos = $match[0][1] + strlen($match[0][0]);
            // generate optional pattern recursively
            if( $match[0][0][0] == '(' ) {
                $optional = $match[2][0];
                $subroute = self::compilePattern($optional,array(
                    'default'   => isset($options['default']) ? $options['default'] : null,
                    'require'   => isset($options['require']) ? $options['require'] : null,
                    'variables' => isset($options['variables']) ? $options['variables'] : null,
                ));
                $tokens[] = array( 
                    self::TOKEN_TYPE_OPTIONAL,
                    $optional[0],
                    $subroute['regex'],
                );
                foreach( $subroute['variables'] as $var ) {
                    $variables[] = $var;
                }
            } else {
                // generate a variable token 
                $varName = $match[1][0];
                // if we defined a pattern for this variable, we should use the given pattern..
                if ( isset( $options['require'][$varName] ) && $req = $options['require'][$varName]) {
                    $regexp = $req;
                } else {
                    if ($pos !== $len) {
                        $seps[] = $pattern[$pos];
                    }
                    // use the default pattern (which is based on the separater charactors we got)
                    $regexp = sprintf('[^%s]+?', preg_quote(implode('', array_unique($seps)), '#'));
                }
                // append token item
                $tokens[] = array(self::TOKEN_TYPE_VARIABLE, 
                    $match[0][0][0], 
                    $regexp, 
                    $varName);
                // append variable name
                $variables[] = $varName;
            }
        }
        if ($pos < $len) {
            $tokens[] = array(self::TOKEN_TYPE_TEXT, substr($pattern, $pos));
        }
        // find the first optional token
        $firstOptional = INF;
        for ($i = count($tokens) - 1; $i >= 0; $i--) {
            if ( self::TOKEN_TYPE_VARIABLE === $tokens[$i][0] 
                && isset($options['default'][ $tokens[$i][3] ]) )
            {
                $firstOptional = $i;
            } 
            else 
            {
                break;
            }
        }
        // compute the matching regexp
        $regex = '';
        // indentation level
        $indent = 1;
        // token item structure:
        //   [0] => token type,
        //   [1] => separator
        //   [2] => pattern
        //   [3] => name, 
        // first optional token and only one token.
        if (1 === count($tokens) && 0 === $firstOptional) {
            $token = $tokens[0];
            ++$indent;
            // output regexp with separator and
            $regex .= str_repeat(' ', $indent * 4) . sprintf("%s(?:\n", preg_quote($token[1], '#'));
            // regular expression with place holder name. (?P<name>pattern)
            $regex .= str_repeat(' ', $indent * 4) . sprintf("(?P<%s>%s)\n", $token[3], $token[2]);
        } else {
            foreach ($tokens as $i => $token) {
                switch ( $token[0] ) {
                case self::TOKEN_TYPE_TEXT:
                    $regex .= str_repeat(' ', $indent * 4) . preg_quote($token[1], '#')."\n";
                    break;
                case self::TOKEN_TYPE_OPTIONAL:
                    // the question mark is for optional, the optional item may contains multiple tokens and patterns
                    $regex .= str_repeat(' ', $indent * 4) . "(?:\n" . $token[2] . str_repeat(' ', $indent * 4) . ")?\n";
                    break;
                default:
                    // append new pattern group for the optional pattern
                    if ($i >= $firstOptional) {
                        $regex .= str_repeat(' ', $indent * 4) . "(?:\n";
                        ++$indent;
                    }
                    $regex .= str_repeat(' ', $indent * 4). sprintf("%s(?P<%s>%s)\n", preg_quote($token[1], '#'), $token[3], $token[2]);
                    break;
                }
            }
        }
        // close groups
        while (--$indent) {
            $regex .= str_repeat(' ', $indent * 4).")?\n";
        }
        // save variables
        $options['variables'] = $variables;
        $options['regex'] = $regex;
        $options['tokens'] = $tokens;
        return $options;
    }
    /**
     * Split tokens from path.
     *
     * @param string $string path string
     *
     * @return array matched results
     */
    static function splitTokens($string)
    {
        // split with ":variable" and path
        preg_match_all('/(?:
            # parse variable token with separator
            .            # separator
            :([\w\d_]+)  # variable
            |
            # optional tokens
            \((.*)\)
        )/x', $string, $matches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);
        return $matches;
    }
    /**
     * Compiles the current route instance.
     *
     * @param array $route route info
     *
     * @return array compiled route info, with newly added 'compiled' key.
     */
    static function compile($pattern, array $options = array())
    {
        $route = self::compilePattern($pattern, $options);
        // save compiled pattern
        $route['compiled'] = sprintf("#^%s$#xs", $route['regex']);
        $route['pattern'] = $pattern; // save pattern
        return $route;
    }
}

class Router
{
    public $routes = array();
    public $staticRoutes = array();
    public $routesById = array();
    /**
     * @var Mux[id]
     */
    public $submux = array();
    public $id;
    /**
     * @var boolean expand routes to parent mux.
     *
     * When expand is enabled, all mounted Mux will expand the routes to the parent mux.
     * This improves the dispatch performance when you have a lot of sub mux to dispatch.
     *
     * When expand is enabled, the pattern comparison strategy for
     * strings will match the full string.
     *
     * When expand is disabled, the pattern comparison strategy for
     * strings will match the prefix.
     */
    public $expand = true;
    public static $id_counter = 0;
    public static function generate_id() {
        return ++static::$id_counter;
    }
    public function getId() {
        if ( $this->id ) {
            return $this->id;
        }
        return $this->id = self::generate_id();
    }
    public function appendRoute($pattern, $callback, array $options = array())
    {
        $this->routes[] = array( false, $pattern, $callback, $options );
    }
    public function appendPCRERoute(array $routeArgs, $callback)
    {
        $this->routes[] = array(
            true, // PCRE
            $routeArgs['compiled'],
            $callback,
            $routeArgs,
        );
    }
    public function mount($pattern, $mux, array $options = array())
    {
        if ($mux instanceof Controller) {
            $mux = $mux->expand();
        } else if ((!is_object($mux) || !($mux instanceof Mux)) && is_callable($mux)) {
            $mux($mux = new Mux());
        }
        if ($this->expand) {
            $pcre = strpos($pattern,':') !== false;
            // rewrite submux routes
            foreach ($mux->routes as $route) {
                // process for pcre
                if ( $route[0] || $pcre ) {
                    $newPattern = $pattern . ( $route[0] ? $route[3]['pattern'] : $route[1] );
                    $routeArgs = PatternCompiler::compile($newPattern,
                        array_replace_recursive($options, $route[3]) );
                    $this->appendPCRERoute( $routeArgs, $route[2] );
                } else {
                    $this->routes[] = array(
                        false,
                        $pattern . $route[1],
                        $route[2],
                        isset($route[3]) ? array_replace_recursive($options, $route[3]) : $options,
                    );
                }
            }
        } else {
            $muxId = $mux->getId();
            $this->add($pattern, $muxId, $options);
            $this->submux[ $muxId ] = $mux;
        }
    }
    public function delete($pattern, $callback, array $options = array())
    {
        $options['method'] = REQUEST_METHOD_DELETE;
        $this->add($pattern, $callback, $options);
    }
    public function put($pattern, $callback, array $options = array())
    {
        $options['method'] = REQUEST_METHOD_PUT;
        $this->add($pattern, $callback, $options);
    }
    public function get($pattern, $callback, array $options = array())
    {
        $options['method'] = REQUEST_METHOD_GET;
        $this->add($pattern, $callback, $options);
    }
    public function post($pattern, $callback, array $options = array())
    {
        $options['method'] = REQUEST_METHOD_POST;
        $this->add($pattern, $callback, $options);
    }
    public function patch($pattern, $callback, array $options = array())
    {
        $options['method'] = REQUEST_METHOD_PATCH;
        $this->add($pattern, $callback, $options);
    }
    public function head($pattern, $callback, array $options = array())
    {
        $options['method'] = REQUEST_METHOD_HEAD;
        $this->add($pattern, $callback, $options);
    }
    public function options($pattern, $callback, array $options = array())
    {
        $options['method'] = REQUEST_METHOD_OPTIONS;
        $this->add($pattern, $callback, $options);
    }
    public function any($pattern, $callback, array $options = array())
    {
        $this->add($pattern, $callback, $options);
    }
    public function add($pattern, $callback, array $options = array())
    {
        if ( is_string($callback) && strpos($callback,':') !== false ) {
            $callback = explode(':', $callback);
        }
        // compile place holder to patterns
        $pcre = strpos($pattern,':') !== false;
        if ( $pcre ) {
            $routeArgs = PatternCompiler::compile($pattern, $options);
            // generate a pcre pattern route
            $route = array(
                true, // PCRE
                $routeArgs['compiled'],
                $callback,
                $routeArgs,
            );
            if ( isset($options['id']) ) {
                $this->routesById[ $options['id'] ] = $route;
            }
            return $this->routes[] = $route;
        } else {
            $route = array(
                false,
                $pattern,
                $callback,
                $options,
            );
            if ( isset($options['id']) ) {
                $this->routesById[ $options['id'] ] = $route;
            }
            // generate a simple string route.
            return $this->routes[] = $route;
        }
    }
    public function getRoute($id) {
        if ( isset($this->routesById[$id]) ) {
            return $this->routesById[$id];
        }
    }
    public function sort()
    {
        usort($this->routes, array('Pux\\MuxCompiler','sort_routes'));
    }
    static public function sort_routes($a, $b)
    {
        if ( $a[0] && $b[0] ) {
            return strlen($a[3]['compiled']) > strlen($b[3]['compiled']);
        } elseif ( $a[0] ) {
            return 1;
        } elseif ( $b[0] ) {
            return -1;
        }
        if ( strlen($a[1]) > strlen($b[1]) ) {
            return 1;
        } elseif ( strlen($a[1]) == strlen($b[1]) ) {
            return 0;
        } else {
            return -1;
        }
    }
    public function compile($outFile, $sortBeforeCompile = true)
    {
        // compile routes to php file as a cache.
        if ($sortBeforeCompile) {
            $this->sort();
        }
        $code = '<?php return ' . $this->export() . ';';
        return file_put_contents($outFile, $code);
    }
    public function getSubMux($id)
    {
        if ( isset($this->submux[ $id ] ) ) {
            return $this->submux[ $id ];
        }
    }
    public static function getRequestMethodConstant($method)
    {
        switch (strtoupper($method)) {
            case "POST":
                return REQUEST_METHOD_POST;
            case "GET":
                return REQUEST_METHOD_GET;
            case "PUT":
                return REQUEST_METHOD_PUT;
            case "DELETE":
                return REQUEST_METHOD_DELETE;
            case "PATCH":
                return REQUEST_METHOD_PATCH;
            case "HEAD":
                return REQUEST_METHOD_HEAD;
            case "OPTIONS":
                return REQUEST_METHOD_OPTIONS;
            default:
                return 0;
        }
    }
    public function match($path)
    {
        $requestMethod = null;
        if (isset($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'])) {
            $requestMethod = self::getRequestMethodConstant($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE']);
        } else if (isset($_SERVER['REQUEST_METHOD'])) {
            $requestMethod = self::getRequestMethodConstant($_SERVER['REQUEST_METHOD']);
        }
        foreach( $this->routes as $route ) {
            if ( $route[0] ) {
                if ( ! preg_match($route[1], $path , $regs ) ) {
                    continue;
                }
                $route[3]['vars'] = $regs;
                // validate request method
                if ( isset($route[3]['method']) && $route[3]['method'] != $requestMethod )
                    continue;
                if ( isset($route[3]['domain']) && $route[3]['domain'] != $_SERVER["HTTP_HOST"] )
                    continue;
                if ( isset($route[3]['secure']) && $route[3]['secure'] && (!isset($_SERVER["HTTPS"]) || !$_SERVER["HTTPS"]) )
                    continue;
                return $route;
            } else {
                // prefix match is used when expanding is not enabled.
                if ( ( is_int($route[2]) && strncmp($route[1], $path, strlen($route[1]) ) === 0 ) || $route[1] == $path ) {
                    // validate request method
                    if ( isset($route[3]['method']) && $route[3]['method'] != $requestMethod )
                        continue;
                    if ( isset($route[3]['domain']) && $route[3]['domain'] != $_SERVER["HTTP_HOST"] )
                        continue;
                    if ( isset($route[3]['secure']) && $route[3]['secure'] && (!isset($_SERVER["HTTPS"]) || !$_SERVER["HTTPS"]) )
                        continue;
                    return $route;
                } else {
                    continue;
                }
            }
        }
    }
    public function dispatch($path = null)
    {
        $path = '/'.trim(parse_url(rawurldecode($_SERVER['REQUEST_URI']), PHP_URL_PATH), '/');
        
        if ($route = $this->match($path) ) {
            if (is_int($route[2])) {
                $submux = $this->submux[ $route[2] ];
                // sub-path and call submux to dispatch
                // for pcre pattern?
                if ($route[0]) {
                    $matchedString = $route[3]['vars'][0];
                    return $submux->dispatch( substr($path, strlen($matchedString)) );
                } else {
                    $s = substr($path, strlen($route[1]));
                    return $submux->dispatch(
                        substr($path, strlen($route[1])) ?: ''
                    );
                }
            }
            return $route;
        }
    }
    public function length()
    {
        return count($this->routes);
    }
    public function getRoutes()
    {
        return $this->routes;
    }
    public function setRoutes($routes)
    {
        $this->routes = $routes;
    }
    public function export() {
        return var_export($this, true);
    }
    public static function __set_state($array)
    {
        $mux = new self;
        $mux->routes = $array['routes'];
        $mux->submux = $array['submux'];
        $mux->expand = $array['expand'];
        if ( isset($array['routesById']) ) {
            $mux->routesById = $array['routesById'];
        }
        $mux->id = $array['id'];
        return $mux;
    }
}