<?php

$routes =  
[
    "/" => [
        'GET' => [
            "controller" => "HomeCtrl",
            "method" => "get"
        ],
        'POST' => [
            "controller" => "HomeCtrl",
            "method" => "create",
        ]
    ],
    "/notes" => [
        'GET' => [
            "controller" => "NoteCtrl",
            "method" => "get",
        ]
    ],
    "/notes/:id" => [
        'DELETE' => [
            "controller" => "NoteCtrl",
            "method" => "delete",
            "parameters" => ["id" => "[0-9]+"]    
        ],
        'PUT' =>[
            "controller" => "NoteCtrl",
            "method" => "update",
            "parameters" => ["id" => "[0-9]+"],
        ],    
    ],
    "/notes/all" => [
        "GET" => [
            "controller" => "NoteCtrl",
            "method" => "getAllNotes"
        ]
    ],
    "*" => "404"
];
    
return $routes;