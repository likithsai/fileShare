<?php
//  Utility classes
class Utils
{
    /**
     *   Function to get Current URL
     *   @return String - Current URL
     */
    function currentPageURL()
    {
        $url = '';

        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
            $url = "https://";
        } else {
            $url = "http://";
        }

        // Append the host(domain name, ip) to the URL.  
        $url .= $_SERVER['HTTP_HOST'];

        // Append the requested resource location to the URL.   
        $url .= $_SERVER['REQUEST_URI'];

        return $url;
    }

    /**
     *   Function to get last URL Parameter
     *   @url String - Webpage URL
     *   @return String - Last URL parameter
     */
    function getLastURLParam($url)
    {
        $parts = explode("/", $url);
        return end($parts);
    }
}
