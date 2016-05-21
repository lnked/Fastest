<?php

public function doRouting(){
    // I used PATH_INFO instead of REQUEST_URI, because the 
    // application may not be in the root direcory
    // and we dont want stuff like ?var=value
    $reqUrl = $_SERVER['PATH_INFO'];
    $reqMet = $_SERVER['REQUEST_METHOD'];

    foreach($this->routes as  $route) {
        // convert urls like '/users/:uid/posts/:pid' to regular expression
        $pattern = "@^" . preg_replace('/\\\:[a-zA-Z0-9\_\-]+/', '([a-zA-Z0-9\-\_]+)', preg_quote($route['url'])) . "$@D";
        $matches = Array();
        // check if the current request matches the expression
        if($reqMet == $route['method'] && preg_match($pattern, $reqUrl, $matches)) {
            // remove the first match
            array_shift($matches);
            // call the callback with the matched positions as params
            return call_user_func_array($route['callback'], $matches);
        }
    }
}
