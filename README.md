```
      ___           ___           ___           ___           ___           ___     
     /\__\         /\__\         /\  \         /\__\         /\  \         /\  \    
    /:/  /        /:/  /        /::\  \       /::|  |       /::\  \       /::\  \   
   /:/__/        /:/__/        /:/\:\  \     /:|:|  |      /:/\ \  \     /:/\:\  \  
  /::\__\____   /::\  \ ___   /:/  \:\  \   /:/|:|  |__   _\:\~\ \  \   /::\~\:\  \ 
 /:/\:::::\__\ /:/\:\  /\__\ /:/__/ \:\__\ /:/ |:| /\__\ /\ \:\ \ \__\ /:/\:\ \:\__\
 \/_|:|~~|~    \/__\:\/:/  / \:\  \ /:/  / \/__|:|/:/  / \:\ \:\ \/__/ \/__\:\/:/  /
    |:|  |          \::/  /   \:\  /:/  /      |:/:/  /   \:\ \:\__\        \::/  / 
    |:|  |          /:/  /     \:\/:/  /       |::/  /     \:\/:/  /        /:/  /  
    |:|  |         /:/  /       \::/  /        /:/  /       \::/  /        /:/  /   
     \|__|         \/__/         \/__/         \/__/         \/__/         \/__/    

```
Khonsa is a MVC/OOP framework developed in PHP. Khonsa is based
on the paradigms used in rest based technologies. Making it flexible to create traditional
websites or Rest based JSON APIs to integrate with most frontend applications.

Note: This framework is still in the development phase and will have security flaws. It is recommended that
Khonsa only be used to create small web applications where security and efficiency are not of utmost concern.

## Setting Up Khonsa (Vagrant)
Khonsa was developed within a vagrant environment. The following steps provide information on how to configure
and run applications using khonsa.

1. Install [VirtualBox](https://www.virtualbox.org/wiki/Downloads) and [Vagrant](https://www.vagrantup.com/downloads.html)
2. Copy the Vagrantfile and the setup script into the follow you'll be using as the base of your application.
3. Run `vagrant up`
4. Wait while the application and it's required resources are installed.
5. Go to `http://localhost:8080` to the see the live application.
6. Enjoy.
________

## Project Layout
Khonsa is designed to be modified to suit the preferences of the developer. Below is the default layout of
of the framework.

````
/app --- Folder containing all user defined files for and individual project

    /Controllers --- Folder with user defined controllers
    
    /Models --- folder containing user defined models
    
    /Views --- folder containing user defined views(html) files
    
    -routes.py --- file with endpoints that the server is expected facilitate
    
/core -- Folder containing the base/core functionality of the framework

    /application --- folder containing essential base classes in khonsa
    
        -controller.php --- Base Controller class (user defined controllers extends this class)
        
        -model.php --- Base Model class (user defined models extends this class)
        
        -requrest.php --- Request class (encapsulates http request information)
        
        -response.php --- Provides various response options to reply to client
        
        -route.php --- Parses and keeps track of the routes that the user defined in their routes file
        
        -view.php --- Provides options to parse user defined view files
        
    /config --- folder containing configuration settings for Khonsa
    
        -config.php --- general configuration settings for the application
        
        -database.php --- database configuration settings for the application
        
    /default --- folder containing default views/files that are used if the user hasn't defined any
    
    -khonsa.php --- base of application that determines what actions should be taken when a request is received.
    
/public --- publicly accessible folder of the application.
```
### Changing default layout

The config.php file found under the config folder in the core of the application can be used to changed the provided layout 
of the application. If you desire to change the layout of the application simply update the value of the settings that 
you wish to change. The name of the publicly accessible folder can't be changed.
______

## Routes

The various states that the application is expected to respond to are defined by routes in the routes.py file. The expected
format of routes can be see below. Each route defined can be associated with any of the four major http methods (GET, POST, PUT, DELETE).
When a route has been defined and the associated HTTP METHOD chosen, the controller and method that will be used to respond to
the request is then defined. 

Additionally, a parameters attribute may be added that is used to accurately identify routes that contain path parameters. 
The value of the parameter is expected to be a regular expression string that will be matched against incoming requests. If 
a parameter is contained within a route and no parameters attribute is defined, any request made to that route will be allowed.

The `*` route is used to capture all other routes. Unlike the definition for valid routes, the only attribute that is expected
for this endpoint is a string with the name of the view to return when this route is triggered. If this is not defined a default
404 page is returned to the client. 

```php
$routes =  [
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
```
______

## Controllers (Khonsa\Application\Controller)

The base controller defined in the core of the application provides the following functions
to aid in imporoving development speed. It is expected that all user defined controllers will
be a subclass of this base controller class. When a constructor object is created, a request
object is passed to the constructor that contains information that was sent by the client when
they made the http request.

If the child class has it's own constructor, ensure that the constructor of the base controller
is called. This is done implicitly when a constructor is not present.

Currently, the following functions are available to all children of the controller class:

```php
- get_all_parameters() which returns all request parameters
- retrieve_parameter($param, $default=null) returns the value associated with the supplied parameter 
```
______

## Models (Khonsa\Application\Model)

Models are classes created that are designed to imitate the table of a database. All user defined models
are expected to subclass the base Model class. If this is not done, then a connection to the database will
not be created. All child model objects that are used when an application is running will share the same
connection to the database server.

NOTE: The name of the class and the file containing the class should be the same (case sensitive) to facilitate
autoloading of all required models throughout the application.

______
## Views (Khonsa\Application\View)

Views are the files that are to be rendered to a client when a makes a request to the application. To facilitate
variable dynamic content for a view, the following format can be followed when creating a view. When the view
is being rendered, via a response method the placeholder text is replaced by the supplied information.

```%{{ variable_name }}%``` - this is the format expected to when parsing variables into a view.

_____
## Requests
When the application receives an HTTP Request, the data received is collected and placed within a resquest 
object. This object is then made available to the controller that tasked with responding to request. All
parameters, request headers and general server information is available through this request object.

The request object can be used to check for the presence of files, headers, parameters among other 
essential information.

______
## Response
A Response class is provided that can be used to reply to a client. This class has two static methods
that can be used to respond to a client via json or by rendering a view. When a view is being rendered
an array can also be passed containing data should be placed within the view (placeholder information).

To render a view named Home with that have `firstname` and `lastname` placeholders you would do the first.
If you just wish to return the data as json, then second method would be used.

```php 
$name = ['firstname' => 'John', 'lastname' => 'Doe'];

Response::view('Home', $name); // render Home view with firtname and lastname
Response::json($name); // return response as json
```

Additionally, if you just wish to redirect the client to another route the following can be done. THe status
code parameter can be omitted. 
```php 
Response::redirect('/home', $status_code=202)
```

_____
## Database Support

Currently, Khonsa is only able to connect to MySQL database servers. The settings relating to the connection
can be found in `core/config/database.php` file.

______

## Example Application
The example application provided is a two page application. The first page is a 
single page bootstrap application with a form to submit contact information at the
end. All the information submitted through this form can be viewed/update/delete
on `/notes` route of the application. Here all the entered data is presented in table
format.

______

## Things to Come
- Support for Composer
- Other DBMS systems (MSSQL, PSQL, SQLITE, MONGO)
- Nested/Sub Views