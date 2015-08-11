<?php
require('kissmvc_core.php');

//===============================================================
// Controller
//===============================================================

class Controller extends KISS_Controller
{
    //This function parses the HTTP request to get the controller name, action name and parameter array.
    function parse_http_request()
    {
        $this->params = array();
        $p            = $this->request_uri_parts;
        //var_dump($p);
        if (isset($p[0]) && $p[0] && $p[0][0] != '?')
            $this->controller = $p[0];
        if (isset($p[1]) && $p[1] && $p[1][0] != '?')
            $this->action = $p[1];
        if (isset($p[2]))
            $this->params = array_slice($p, 2);
        
        if (isset($p[1]) && $p[1] == 'property') {
            $this->controller = 'property';
            $this->action     = 'index';
            if (isset($p[2]) && $p[2] == 'search') {
                $this->action = 'search';
            }            
            if (isset($p[2]) && $p[2] == 'booking') {
                $this->action = 'booking';
            }
            if (isset($p[2]) && $p[2] == 'booking_result') {
                $this->action = 'booking_result';
            }
            
        }
        
        //Routing ajax page
        if (isset($p[1]) && $p[1] == 'ajax') {
            $this->controller = 'ajax';
            $this->action     = 'index';
            if (isset($p[2]) && $p[2] == 'search_hotels') {
                $this->action = 'search_hotels';
            }            
            if (isset($p[2]) && $p[2] == 'retrieve_destinations') {
                $this->action = 'retrieve_destinations';
            }
            if (isset($p[2]) && $p[2] == 'insert_hotels_temp') {
                $this->action = 'insert_hotels_temp';
            }
        }

        // Routing destination page
        if (isset($p[1]) && $p[1] == 'destination') {
            $this->controller = 'destination';
            $this->action     = 'index';            
            if (isset($p[5]) && $p[5] !== '') {
                $this->action = 'country';
            }
        }        
        
        return $this;
    }
    
}
//===============================================================
// View
//===============================================================

class View extends KISS_View
{
    
}

//===============================================================
// Model/ORM
//===============================================================

class Model extends KISS_Model
{
    
}
