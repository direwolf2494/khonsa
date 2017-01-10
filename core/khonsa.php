<?php
namespace Khonsa;

// core application
require_once 'application/controller.php';
require_once 'application/model.php';
require_once 'application/request.php';
require_once 'application/response.php';
require_once 'application/route.php';
require_once 'application/view.php';

// configuration files
require_once 'config/config.php';

// load model files
spl_autoload_register(function($class) {
   require_once MODEL_DIR . $class . '.php';
});

// create aliases for the classes in use
use Khonsa\Application\Route as Route;
use Khonsa\Application\Request as Request;
use Khonsa\Application\Response as Response;

class Khonsa
{
    function __construct()
    {
        Route::load();
        // get incoming requested route
        $route = $this->parse_uri($_SERVER['REQUEST_URI']);
        $method = $_SERVER['REQUEST_METHOD'];
        // generate the request object
        $request = $this->generate_request();

        if (Route::is_valid_route($route, $method))
        {
            $handler = Route::get_controller_method($route, $method);

            // check if handler valid
            if (is_null($handler))
                throw new \Exception("No controller found for route: " . $route);
            
            // load the controller file
            require_once CONTROLLER_DIR . $handler[0] . '.php';
            
            // trigger the controller
            $controller = new $handler[0]($request);
            // check if method exists
            if (!method_exists($controller, $handler[1]))
                throw new \Exception('Method ' . $handler[1] . 'does not exist in controller: ' . $handler[0]);
                
            // retrieve path parameters if any
            call_user_func_array(array($controller, $handler[1]), $handler[2]);
        }
        else
            Response::not_found();
    }
    
    private function generate_request()
    {
        $parameters = null;
        $headers = getallheaders();
        
        switch($_SERVER['REQUEST_METHOD'])
        {
            case 'GET':
                $parameters = $_GET;
                break;
            case 'POST':
                $parameters = $_POST;
                break;
            case 'PUT':
            case 'DELETE':
                parse_str(file_get_contents("php://input"), $parameters);
                
        }

        return new Request($_SERVER, $headers, $parameters, $_FILES);
    }
    
    private function parse_uri($uri)
    {
        $parts = explode("?", $uri);
        $parts = ($parts === '') ? ['/'] : $parts;
        
        return $parts[0];
    }
}