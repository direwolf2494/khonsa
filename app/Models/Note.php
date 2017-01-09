<?php

use Khonsa\Application\Model as Model;

class Note extends Model
{
    public function addNote($name, $company, $message)
    {
        $sql = "INSERT INTO notes (name, company, message) VALUES (:name, :company, :message)";
        $query = $this->db->prepare($sql);
        $parameters = [':name' => $name, ':message' => $message, ':company' => $company];
        $query->execute($parameters);
    }
    
    public function getNotes()
    {
        $sql = "SELECT id, name, company, message FROM notes";
        $query = $this->db->prepare($sql);
        $query->execute();
        
        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public function getNoteById($id)
    {
        $sql = 'SELECT id, name, company, message FROM notes WHERE id=:id';
        $parameters = [':id' => $id];
        $query = $this->db->prepare($sql);
        $query->execute($parameters);
        
        return $query->fetch(\PDO::FETCH_ASSOC);
    }
    
    public function deleteNote($id)
    {
        $sql = "DELETE FROM notes WHERE id = :id";
        $query = $this->db->prepare($sql);
        $parameters = [':id' => $id];
        
        $query->execute($parameters);
    }
    
    public function updateNote($id, $name, $company, $message)
    {
        $sql = "UPDATE notes SET name = :name, company = :company,";
        $sql .= " message = :message WHERE id = :id";
        $parameters = [
            ':id' => $id, 
            ':name' => $name, 
            ':company' => $company,
            ':message' => $message
        ];
        
        $query = $this->db->prepare($sql);
        $query->execute($parameters);
    }
}