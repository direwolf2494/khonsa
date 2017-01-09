<?php
namespace Khonsa\Application;

use Khona\Application\Request as Request;

/**
 * Class Response - contains methods that are used to respond to
 * a valid http request. 
 * 
 * Currently, the most useful functions are implemented as static function.
 * More work can be done to add other useful methods.
 * 
**/

class Response
{
    private $request = null;

    /**
     * Constructer used to instantiate a new Response object.
     * 
     * @param Request $request - the request that will be responded to.
    **/
    function __construct(Request $request)
    {
        $this->request = $request;
    }
    
    /**
     * Return JSON formatted data.
     * 
     * @param mixed $data - the data to be returned as json.
    **/
    public static function json($data)
    {
        $encoded = json_encode($data);
        header("Content-Type: application/json");
        exit($encoded);
    }

    /**
     * Return a user defined view (html).
     * 
     * @param string $viewname - the name of the view to return
     * @param array $parameters - parameters required by the view.
    **/    
    public static function view($viewname, $parameters=[])
    {
        $content = View::parse($viewname, $parameters);
        header("Content-Type: text/html; charset=utf-8");
        exit($content);
    }

    /**
     * Send a redirect response to the client.
     * 
     * @param string $location - the endpoint to redirect to 
    **/    
    public static function redirect($location, $status_code=202)
    {
        $location = ($location[0] === '/') ? $location : ('/' . $location);
        $address = $request->get_server_name . $location;
        header("Location: " . $address, $http_response_code=$status_code);
        exit;
    }

    /**
     * Trigger a 404 request. Called when a requested route has not been found.
    **/    
    public static function not_found()
    {
        $content = null;
        $viewname = Route::get_not_found_view(); // get user has defined view
        
        // not view defined, use default
        if (is_null($viewname))
        {
            $path = CORE_DIR . 'default' . DIRECTORY_SEPARATOR . '404.html';
            $content = file_get_contents($path);
        }
        else
            $content = View::parse($viewname); // use user defined view
        
        header("HTTP/1.0 404 Not Found");
        header("Content-Type: text/html; charset=utf-8");
        
        exit($content);
    }
    
    /**
     * Triggers a 400 request, useful when client has not provided
     * adequate information for processing in their intitial request.
    **/
    public static function bad_request()
    {
        $content = file_get_contents(CORE_DIR . 'default' . DIRECTORY_SEPARATOR . '400.html');
        
        http_response_code(400);
        exit($content);
    }
}