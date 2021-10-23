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

    /**
     *  Function to convert bytes to KB, MB, GB
     *  @bytes String - file size in bytes
     *  @return String - Formatted file size in KB, MB, GB
     */
    function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0 bytes';
        }

        return $bytes;
    }
}
