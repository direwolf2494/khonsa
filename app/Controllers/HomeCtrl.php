<?php 

use Khonsa\Application\Controller as Controller;
use Khonsa\Application\Response as Response;

class HomeCtrl extends Controller
{
    public function get()
    {
        return Response::view("Home");
    }
    
    public function create()
    {
        // retrieve request parameters
        $name = $this->retrieve_parameter("name");
        $company = $this->retrieve_parameter("company");
        $message = $this->retrieve_parameter("message");
        
        if (is_null($name) || is_null($company) || is_null($message))
            return Response::bad_request();
        
        $note = new Note();
        $note->addNote($name, $company, $message);
        
        return Response::redirect("/");
    }
}