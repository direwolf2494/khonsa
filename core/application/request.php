<?php
namespace Khonsa\Application;

/**
 * Class Request - contains important information that was sent to the
 * server when the http request was made. 
 * 
 * Futher improvements can be made to sanitize and process the information
 * that was sent in the request. Currently, this information is only stored.
**/

class Request
{
    private $headers;
    private $parameters;
    private $files;
    private $server_info;
    
    /**
     * Constructor that instantiates a new Request object.
     * 
     * @param array $req_info - general request information
     * @param array $headers - request headers
     * @param array $paramters - request parameters
     * @param array $files - files upload in request
    **/
    function __construct(array $req_info, array $headers, array $parameters, array $files)
    {
        $this->server_info = $req_info;
        $this->headers = $headers;
        $this->parameters = $parameters;
        $this->files = $files;
    }
    
    /**
     * Returns all the parameters that was sent in the http request.
     * 
     * @return array - parameters from request
    **/
    public function get_all_parameters()
    {
        return $this->parameters;
    }
    
    /**
     * Returns the associated value of the supplied parameter from the
     * http request.
     * 
     * @param string $param - the name of the supplied parameter
     * 
     * @return string - the value of the parameters, else null
    **/
    public function get_parameter($param)
    {
        return $this->parameters[$param];
    }
    
    /**
     * Returns the server name. This is the same as the base url. According to
     * php manual this value should not be trusted unless certain settings have
     * been defined for the server.
     * 
     * See: http://php.net/manual/en/reserved.variables.server.php
     * 
     * @return string - server name
    **/
    public function get_server_name()
    {
        return $this->server_info['SERVER_NAME'];
    }
    
    /**
     * Returns all the headers that were sent with this request.s
     * 
     * @return array - list of headers
    **/
    public function get_all_headers()
    {
        return $this->headers;
    }
    
    /**
     * Returns a value of the header with the supplied name.
     * 
     * @param string $header - the header being requested
     * 
     * @return string - the value of the header, else null
    **/
    public function get_header($header)
    {
        return $this->headers[$header];
    }
    
    /**
     * Returns the HTTP METHOD of the request
    **/
    public function get_request_method()
    {
        return $this->server_info["REQUEST_METHOD"];
    }
    
    /**
     * Checks whether a file was sent in this request.
     * 
     * @return bool - true if file was sent, false otherwise
    **/
    public function is_file_attached($name)
    {
        return !is_null($this->files[$name]);
    }
    
    /**
     * Returns all the information associated with the uploaded
     * file. 
     * 
     * @param string $key - key of associated with file at upload
     * 
     * @return array - array of file information, null otherwise
    **/
    public function get_file($key)
    {
        return $this->files[$key];
    }
}