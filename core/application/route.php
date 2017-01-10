<?php
namespace Khonsa\Application;

/**
 * Class Route
 * 
 * This class contains static methods that are used to parse and extract
 * that information that a user defines in his routes file. 
**/
class Route
{
    // class constants
    const WILDCARD = "/[a-zA-Z_0-9]+[\w-]*/";
    const PATTERN = "/:[a-zA-Z_]+[\w-]*/";
    const ENDPOINT_FORMAT = "/(\/(:?[a-zA-z_]+[0-9]*)*)+/";
    
    // class variables
    private static $routes;
    private static $endpoints = ["GET" => [], "POST" => [], "PUT" => [], "DELETE" => []];
    private static $http_methods = ['GET', 'POST', 'PUT', 'DELETE'];
    private static $error_view = null;
    
    /**
     * Loads the contents of the user defined route file. The file is then
     * parsed and other information that is required to respond appropriately 
     * to a request to each of the declared endpoint is also extracted.
    **/
    public static function load()
    {
        self::$routes = require(ROUTES_FILE);
        self::parse();
    }
    
    /**
     * Checks to determine if the supplied route/method combination is valid.
     * This is determined by checking it against the predefined routes that
     * have been declared.
     * 
     * @param string $endpoint - the url route to be checked.
     * @param string $http_method - the method associated with the request.
     * 
     * @return bool - true if $endpoint/$http_method was declareds.
    **/
    public static function is_valid_route($endpoint, $http_method)
    {
        $http_method = strtoupper($http_method);
        $endpoint = self::find_route($endpoint, $http_method);
        
        if (is_null($endpoint))
            return false;
            
        return true;
    }
    
    /**
     * Returns the controller, method and parameters that are associated
     * with the supplied endpoint/method. 
     * 
     * @param  string $endpont - the endpoint who's info is required.
     * @param string $http_method - the method associated with the request
     * 
     * @return array - contains data for the endpoint, otherwise null.
    **/
    public static function get_controller_method($endpoint, $http_method)
    {
        $http_method = strtoupper($http_method);
        
        if (self::is_valid_route($endpoint, $http_method))
        {
            // get route information
            $data = self::find_route($endpoint, $http_method);
            $route = self::$routes[$data[1]][$http_method];
            $parameters = [];
            
            // get the assocated parameters associated with this route
            if (preg_match($data[0], $endpoint, $matches) === 1)
            {
                for($idx = 1, $len = count($matches); $idx < $len; ++$idx)
                    array_push($parameters, $matches[$idx]);
            }
            
            return [$route["controller"], $route["method"], $parameters];
        }

        return null;
    }
    
    
    /**
     * Returns the view name for the 404/Not found routes.
     * 
     * @return string - view name, null if none defined
    **/
    public static function get_not_found_view()
    {
        return self::$error_view;
    }
    
    /**
     * Finds information for the supplied endpoint. This is the information
     * that was defined by the user in the route file. Routes may have
     * been defined to be supplied with variable path parameters. Therefore,
     * a fully formed url should be matched to the defined endpoint with the
     * appropriate variables.
     * 
     * @param $endpoint - the endpoint to search for.
     * @param $http_method - the method associated with $endpoint.
     * 
     * @return array - controller/function associated with endpoint, else null.
    **/
    private static function find_route($endpoint, $http_method)
    {
        $http_method = strtoupper($http_method);
        
        // before PHP 5.6, the 3rd array_filter doesn't support a third param
        // therefore, flip the array to be able to be filter by keys.
        $flipped_routes = array_flip(self::$endpoints[$http_method]);
        $routes = array_filter($flipped_routes, function($k) use ($endpoint) {
            return preg_match($k, $endpoint);
        });
        // flip array back to original form.
        $routes = array_flip($routes);
        
        return count($routes) == 0 ? null : [array_keys($routes)[0], array_values($routes)[0]];
    }
    
    /**
     * Parse the user defined route file to the appropriate format. Additional
     * information used to by other functions are also extracted/formulated.
    **/
    private static function parse()
    {
        // extract original route info
        self::format_routes();
        
        // generate regex for each route that was defined
        foreach (self::$routes as $endpoint => &$route_obj)
        {
            self::validate_routes($endpoint, $route_obj);
            self::generate_route($endpoint, $route_obj);
        }
    }
    
    /**
     * Extract and format the information that was declared by the user
     * in the routes file. The make future processing easier, all http 
     * methods used as keys have been converted to upper case and leading
     * slashes are added to the defined routes if necessary, ending slashes
     * are removed as well. If a not found (catch all) route is defined that
     * is also extracted and saved.
    **/
    private static function format_routes()
    {
        $temp = [];
        $k = $v = null;
        
        foreach (self::$routes as $key => $value)
        {
            // catch all route found
            if (strcmp($key, "*") === 0)
            {
                // value should be string containing the view name
                if (!is_string($value))
                    throw new \Exception("Invalid error view");
                    
                self::$error_view = $value;
                continue;
            }
            
            // adds leading slash and removes trailing slash if necessary
            $k = ($key[0] != '/') ? ('/' . $key) : $key;
            $key_length = strlen($k) - 1;
            $k = ($k[$key_length] == '/') ? substr($k, 0, $key_length) : $k;
            // convert all keys to uppercase
            $v = array_change_key_case($value, CASE_UPPER);
            $temp[$k] = $v;
        }
        
        self::$routes = $temp;
    }
    
    /**
     * Determines if the supplied $endpoint and the associated
     * HTTP METHODS declared as valid.
     * 
     * @param string $endpoint - the defined endpoint
     * @param array $routes - the associated http methods for this endpoint
    **/
    private static function validate_routes($endpoint, $routes)
    {
        $m = preg_match(self::ENDPOINT_FORMAT, $endpoint, $matches);
        
        // endpoint doesn't adhere to defined format
        if (($m !== 1) || (strcmp($matches[0], $endpoint) !== 0))
            throw new \Exception("Invalid route: " . $endpoint);
        
        $keys = array_keys($routes);
        // all http methods declared are valid
        foreach ($keys as $key)
        {
            if (!in_array($key, self::$http_methods))
                throw new \Exception($endpoint . "contains invalid http method: \"" . $key . "\"");
        }
    }
    
    /**
     * Generate the regular expression that will be used to match fully
     * formulated endpoints to the ones that were defned in the route file.
     * 
     * @param string $endpoint - the defined endpoint
     * @param array $routes - http methods and settings for this endpoint
    **/
    private static function generate_route($endpoint, $routes)
    {
        foreach($routes as $method => $route)
        {
            // generate regular expression for this endpoint
            $parameters = isset($route["parameters"]) ? $route["parameters"] : [];
            $regex_route = self::replace_variables($endpoint, $parameters);
            
            // check if route already exists first
            if (array_key_exists($regex_route, self::$endpoints[$method]))
                throw new \Exception("Route declared multiple times: " . $endpoint);
            
            self::$endpoints[$method][$regex_route] = $endpoint;
        }
    }
    
    /**
     * Generates a regular expression string for the supplied endpoint. If 
     * the endpoint contains path parameters, these are replaced by the 
     * associated values supplied in the parameters array. If none is present
     * then a default expression to catch any valid path parameter is inserted
     * instead.
     * 
     * @param string $endpoint - the user defined endpoint.
     * @param array $paramters - the path parameters for the endpoint
     * 
     * @return string - regular expression string for the endpoint
    **/
    private static function replace_variables($endpoint, array $parameters)
    {
        $original = $endpoint;
        $offset = 0;
        
        while (true)
        {
            // extract path parameter from the endpoint
            $matched = preg_match(self::PATTERN, $endpoint, $matches, PREG_OFFSET_CAPTURE, $offset);
            
            // no matches found, endpoint can be returned
            if ($matched === 0)
            {
                $endpoint = str_replace('/', '\/', $endpoint);
                return '/^' . $endpoint . '$/';
            }
            else if ($matched === false)
                throw new \Exception("An error occured while parsing endpoint: " . $original . ' ' . $endpoint);
            
            $var = substr($matches[0][0], 1); // remove colon from string
            // find replacement for the parameter
            $replacement = is_null($parameters[$var]) ? self::WILDCARD : $parameters[$var];
            $pattern = '/' . $matches[0][0] . '/';
            // replace path parameter with regular expression string
            $endpoint = preg_replace($pattern, '(' . $replacement . ')', $endpoint);
            $offset = $matches[0][1] + strlen($matches[0][0]);
        }
    }
}