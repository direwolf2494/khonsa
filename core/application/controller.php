<?php
namespace Khonsa\Application;

use Khonsa\Application\Request as Request;
use Khonsa\Application\Response as Response;

/**
 * Class Controller - This class contains functions that are essential
 * to all controllers that a user will define.
**/

class Controller
{
    // variables associated with constructor class
    protected $request = null;
    
    /**
     * Construtor that instantiates new Controller object
     * 
     * @param Request $request - the request object containing http data
    **/
    function __construct(Request $request)
    {
        $this->request = $request;
    }
    
    /**
     * Returns the data sent in the request of the supplied argument.
     * 
     * @param string $arg - parameter to find
     * 
     * @return mixed - value of the argument supplied or null
    **/
    final protected function retrieve_parameter($arg, $default=null)
    {
        $param = $this->request->get_parameter($arg);
        return is_null($param) ? $default : $param;
    }
    
    /**
     * Returns all the parameters that were sent in the request. 
     * 
     * @return array - list of parameters
    **/
    final protected function retrieve_all_parameters()
    {
        return $this->request->get_all_parameters();
    }
    
    /**
     * Checks if a file was sent in the request.
     * 
     * @return bool - true if a file is in the request
    **/
    final protected function has_file($param)
    {
        return $this->request->is_file_attached($param);
    }
    
    
    /**
     * Returns the file that was uploaded.
     * 
     * @return array - file data or null
    **/
    final protected function get_file($param)
    {
        return $this->request->get_file($param);
    }
}