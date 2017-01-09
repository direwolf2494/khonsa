<?php

use Khonsa\Application\Controller as Controller;
use Khonsa\Application\Response as Response;

class NoteCtrl extends Controller
{
    public function get()
    {
        return Response::view('Notes');
    }

    public function getAllNotes()
    {
        $note = new Note();
        $notes = $note->getNotes();
        return Response::json($notes);
    }
        
    public function delete($id)
    {
        $id = intval($id);
        $note = new Note();
        $note->deleteNote($id);
        
        return Response::json('done');
    }
    
    public function update($id)
    {
        $id = intval($id);
        $name = $this->retrieve_parameter("name");
        $company = $this->retrieve_parameter("company");
        $message = $this->retrieve_parameter("message");
        
        if (is_null($name) || is_null($company) || is_null($message))
            return Response::bad_request();
            
        $note = new Note();
        $note->updateNote($id, $name, $company, $message);
        
        return Response::json($note->getNoteById($id));
    }
}