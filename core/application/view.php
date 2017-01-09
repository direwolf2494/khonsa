<?php
namespace Khonsa\Application;

/**
 * Class View
 * 
 * The View class contains static functions that are used to 
 * manipulate use defined views. 
 * 
**/
class View
{
    /**
     * prase is used to replace any variables that were declared within
     * the user defined view. Additionally, if view templates were used
     * the child template is loaded into the appropriate parent template.
     * 
     * @param string $viewname - name of the view to parse
     * @param array $parameters - parameters that should placed into the view
     * 
     * @return string $contents - parsed view file
    **/
    public static function parse($viewname, $parameters=[])
    {
        $view_path = VIEW_DIR . $viewname . '.html';
        $contents = file_get_contents($view_path);
        
        if ($contents === false)
            throw new \Exception("Unable to load view: " . $viewname);
            
        // replace parameters in file
        foreach ($parameters as $key => $value)
        {
            $pattern = '/%{{ *' . $key . ' *}}%/';
            $contents = preg_replace($pattern, $value, $contents);
        }
        
        return $contents;
    }
    
    /**
     * The function is used to recursively load a child templates
     * into their associated parent templates and return the final
     * contents of the view.
     * 
     * @param string $viewname name of the view to load
     * @return string content of view file
    **/
    private static function load_template($viewname)
    {
        
    }
}