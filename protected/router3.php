<?php
   /**
    * Add a regex route to Yaf_Router route stack
    */
    Yaf_Dispatcher::getInstance()->getRouter()->addRoute("name",
        new Yaf_Route_Regex(
           "#^/product/([^/]+)/([^/])+#", //match request uri leading "/product"
           array(
               'controller' => "product",  //route to product controller,
           ),
           array(
              1 => "name",   // now you can call $request->getParam("name")
              2 => "id",     // to get the first captrue in the match pattern.
           )
        )
    );


   /**
    * Use match result as MVC name
    */
    Yaf_Dispatcher::getInstance()->getRouter()->addRoute("name",
        new Yaf_Route_Regex(
           "#^/product/([^/]+)/([^/])+#i", //match request uri leading "/product"
           array(
              'controller' => ":name", // route to :name, which is $1 in the match result as controller name
           ),
           array(
              1 => "name",   // now you can call $request->getParam("name")
              2 => "id",     // to get the first captrue in the match pattern.
           )
        )
    );

    /**
    * Add a regex route to Yaf_Router route stack by calling addconfig
    */
    $config = array(
        "name" => array(
           "type"  => "regex",          //Yaf_Route_Regex route
           "match" => "#(.*)#",         //match arbitrary request uri
           "route" => array(
               'controller' => "product",  //route to product controller,
               'action'     => "dummy",    //route to dummy action
           ),
           "map" => array(
              1 => "uri",   // now you can call $request->getParam("uri")
           ),
        ),
    );
    Yaf_Dispatcher::getInstance()->getRouter()->addConfig(
        new Yaf_Config_Simple($config));

class Bootstrap extends Yaf_Bootstrap_Abstract{
  public function _initConfig() {
      $config = Yaf_Application::app()->getConfig();
      Yaf_Registry::set("config", $config);
  }

  public function _initRoute(Yaf_Dispatcher $dispatcher) {
      $router = $dispatcher->getRouter();
      /**
       * we can add some pre-defined routes in application.ini
       */
      $router->addConfig(Yaf_Registry::get("config")->routes);
      /**
       * add a Rewrite route, then for a request uri: 
       * http://***/product/list/22/foo
       * will be matched by this route, and result:
       *
       *  [module] => 
       *  [controller] => product
       *  [action] => info
       *  [method] => GET
       *  [params:protected] => Array
       *      (
       *          [id] => 22
       *          [name] => foo
       *      )
       * 
       */
      $route  = new Yaf_Route_Rewrite(
          "/product/list/:id/:name",
          array(
              "controller" => "product",
              "action"     => "info",
          )
      ); 
      
      $router->addRoute('dummy', $route);
  }


  Yaf_Route_Simple implements Yaf_Route_Interface {
/* Свойства */
protected $controller ;
protected $module ;
protected $action ;
/* Методы */
public string assemble ( array $info [, array $query ] )
public __construct ( string $module_name , string $controller_name , string $action_name )
public bool route ( Yaf_Request_Abstract $request )
}
