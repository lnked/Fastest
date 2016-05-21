<?php

/**
 *  //test URLs
 *  $urlCnt[0]  =   "/information/location";
 *  //controller => information, action => location
 *  $urlCnt[1]  =   "/webdesign/show/1";
 *  $urlCnt[2]  =   "/information/show/3/why-you-should-choose-us";
 *  $urlCnt[3]  =   "/information/delete/3/becauseItsOld";
 *  $urlCnt[4]  =   "/information";
 *  //controller => information, action => $standartAction
 * @author Bjoern Schwabe
 *
 */
class Router
{
    //singleton
    static private $_instance;
    
    //this is the array where all the routes are stored
    private $routes =   array();
    //this is the url that will be parsed
    private $url;
    
    //the final outcome
    private $finalOutcome;
    
    //these are the standard action and controller if no routes or what so ever can match the url
    private $standardAction     =   "index";
    private $standardController =   "index";
    
    //standard error controller and error action
    private $standardErrorAction        =   "index";
    private $standardErrorController    =   "error";
    
    /**
     * 
     * Constructor needs the URL
     * @param unknown_type $url
     */
    public function __construct($url)
    {
        $this->url  =   "/" . $url;
    }
    
    /**
     * 
     * Singleton
     * @param unknown_type $url
     */
    public static function getInstance($url = "")
    {
        if ( !(self::$_instance instanceof self) )
        {
            self::$_instance = new self($url);
        }
        return self::$_instance;
    }
    
    /**
     * 
     * Returns the POST as an ArrayObject
     */
    public function getPOST()
    {
        return new ArrayObject($_POST, 2);
    }
    
    /**
     * 
     * Returns the GET as an ArrayObject
     */
    public function getGET()
    {
        return new ArrayObject($_GET, 2);
    }
    
    /**
     * 
     * Sets the controller
     * @param String $con
     */
    public function setController($con)
    {
        $this->finalOutcome->controller     =   $con;
    }
    
    /**
     * 
     * returns the controller
     */
    public function getController()
    {
        return $this->finalOutcome->controller;
    }
    
    /**
     * 
     * sets the action
     * @param String $act
     */
    public function setAction($act)
    {
        $this->finalOutcome->action     =   $act;
    }
    
    /**
     * 
     * returns the action
     */
    public function getAction()
    {
        return $this->finalOutcome->action;
    }
    
    /**
     * 
     * checks if the request is AJAX
     */
    public function _isAJAX()
    {
        
        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') 
        {
            $status =   true;
        }
        else
        {
            $status =   false;
        }
        return $status;     
    }
    
    /**
     * 
     * checks if the server request is POST
     */
    public function _isPost()
    {
        if ( $_SERVER['REQUEST_METHOD'] == "POST" )
        {
            $status =   true;
        }
        else
        {
            $status =   false;
        }
        return $status;
    }
    
    /**
     * 
     * checks if the server request is GET
     */
    public function _isGET()
    {
        if ( $_SERVER['REQUEST_METHOD'] == "GET" )
        {
            $status =   true;
        }
        else
        {
            $status =   false;
        }
        return $status;
    }
        
    /**
     * 
     * returns all routes in the router
     */
    public function getRoutes()
    {
        return $this->routes;
    }
    
    /**
     * returns the request
     */
    public function getRequest()
    {
        if( empty( $this->routes ) )
        {
            $this->setStandardRoute();
        }
        return $this->generateOutcome();
    }

    /**
     * 
     * sets the standard route
     */
    public function setStandardRoute()
    {
        $this->routes[] =   array(":controller/:action/(\d+)/:name", array(
                                                "controller" => '', 
                                                'action' => 'show',
                                                'id'    => '',
                                                'name' => ''
                                                ));
    }
    
    /**
     * 
     * sets the standart controller 
     * //standard = index
     * @param unknown_type $controller
     */
    public function setStandardController($controller)
    {
        $this->standardController   =   $controller;
    }
    
    /**
     * 
     * sets the standart action 
     * //standard = index
     * @param unknown_type $action
     */
    public function setStandardAction($action)
    {
        $this->standardAction   =   $action;
    }
    
    /**
     * 
     * adds routes to the router
     * @param unknown_type $route
     */
    public function addRoute($route = array() )
    {
        $this->routes[] =   $route;
    }
    
    /**
     * 
     * creates an array that will output something like:
     */
    private function generateOutcome()
    {       
        //check if the domain is just the index... 
        if ($this->url != "/")
        {
            //some empty stuff for later
            $params         =   array();
            $regex          =   "";
            $routesCNT      =   array();
            
            //prepare the regex to parse the urls
            foreach($this->routes as $route)
            {   
                $ga =   preg_split("/\//", $route[0] );
                foreach($ga as $expr)
                {
                    //check if the name starts with a : - if so than it will be a variable that is needed as an array index
                    //important for later stuff, as controller, action, params always be there as an index 
                    if( strpos($expr, ":") === 0 )
                    {
                        //:controller or :action found or whatever -> a regex need to be used
                        $regex  .= "\/[a-zA-Z-0-9]+";
                    }
                    else
                    {
                        //not found therefore what is written is the requirement
                        $regex  .= "\/" . $expr;                
                    }
                                
                }   
                
                //put the route back together but this time with the regular expression
                $rPattern[0]    =   $route[0];
                $rPattern[1]    =   $route[1];
                $rPattern[2]    =   $regex;
        
                $routesCNT[]    =   $rPattern;
                $regex          =   null;
            }   
            
            //build the final array for the url to usefull stuff
        
            $processor  =   array();
            $finalArray =   array();
            $i          =   1;
            foreach($routesCNT as $route)
            {   
                if ( preg_match("/^" . $route[2] . "\z/i", $this->url ) == 1  )
                {
                    //split the url into pieces for the final array
                    $urlValues      =   explode("/", $this->url);   
                    $routeLength    =   count($route[1]);
                    
                    foreach( $route[1] as $name => $val )
                    {           
                        if ( $routeLength > 2 && $routeLength == (count($urlValues) - 1))
                        {
                            //make sure not to override the index
                            if( $val == "")
                            {
                                $processor[$name]   =   $urlValues[$i];
                            }   
                            else
                            {       
                                $processor[$name]   =   $val;
                            }
                        }
                        else
                        {
                            $processor['controller']    =   $urlValues[1];
                            $processor['action']        =   $urlValues[2];
                        }
                        $finalArray =   $processor;
                        $i++;
                    }       
                    
                }
                else
                {
                    //here is the $route[1]['params'] regquired!!!
                    //split the url into pieces for the final array
                    $urlValues      =   explode("/", $this->url);   
                    $processor['controller']    =   $urlValues[1];
                    //check if there is no action set
                    if ( count($urlValues) <= 2)
                    {
                        $processor['action']        =   $this->standardAction;                  
                    }
                    else if(empty($urlValues))
                    {
                        $processor['controller']    =   $this->standardController;
                        $processor['action']        =   $this->standardAction;                      
                    }
                    else
                    {
                        $processor['action']        =   $urlValues[2];  
                        $baseLength     =   strlen($urlValues[1] . "/" . $urlValues[2] );
                        $newUrl         =   substr($this->url, $baseLength + 1);
                        $params     =   explode("/", $newUrl);  
                        array_shift( $params );
                        $processor['params']    =   $params;
                    }
                    $finalArray =   $processor;         
                }
            }
        }
        else
        {
            //if the requested path is just / - it means basically its the index file therefore show the standard
            $processor['controller']    =   $this->standardController;
            $processor['action']        =   $this->standardAction;  
            $finalArray                 =   $processor;     
        }
        $this->finalOutcome     =   new ArrayObject( $finalArray, 2);
        return $this->finalOutcome;
    }
}