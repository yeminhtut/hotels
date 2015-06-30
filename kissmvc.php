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
            if (isset($p[2]) && $p[2] == 'search_new') {
                $this->action = 'search_new';
            }
            if (isset($p[2]) && $p[2] == 'detail') {
                $this->action = 'detail';
            }
            
        }
        
        //Routing ajax page
        if (isset($p[1]) && $p[1] == 'ajax') {
            $this->controller = 'ajax';
            $this->action     = 'index';
            if (isset($p[2]) && $p[2] == 'search_hotels') {
                $this->action = 'search_hotels';
            }
        }
        
        // Routing destination page
        if (isset($p[1]) && $p[1] == 'destination') {
            $this->controller = 'destination';
            $this->action     = 'index';
            if (isset($p[2]) && $p[2] !== '') {
                $this->action = 'city';
            }
            if (isset($p[5]) && $p[5] !== '') {
                $this->action = 'country';
            }
        }
        // Routing scraper
        if (isset($p[1]) && $p[1] == 'scraper') {
            $this->controller = 'scraper';
            $this->action     = 'index';
            
        }
        
        if (isset($p[1]) && $p[1] == 'about') {
            $this->controller = 'about';
            $this->action     = 'index';
            
        }
        
        if (isset($p[0]) && $p[0] == 'directory') {
            $letters          = range('A', 'Z');
            $letters[]        = '_HEX_';
            $this->controller = 'directory';
            if (isset($p[1]) && in_array($p[1], $letters)) {
                $this->action = 'letter';
                $this->params = array_slice($p, 1);
            } elseif (isset($p[1]) && $p[1] == 'review') {
                $this->action = 'review';
                $this->params = array_slice($p, 2);
            } elseif (isset($p[1]) && $p[1] == 'enquire') {
                $this->action = 'enquire';
                $this->params = array_slice($p, 2);
            } elseif (isset($p[1]) && $p[1] == 'enquire_thankyou') {
                $this->action = 'enquire_thankyou';
                $this->params = array_slice($p, 2);
            } else {
                $this->action = 'index';
                $this->params = array_slice($p, 1);
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
