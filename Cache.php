<?php

class Cache
{

    private static $CACHE_FOLDER = '/cache';
    
    public function __construct() 
    {
        // When folder is missing, create one
        if (realpath(__DIR__ . Cache::$CACHE_FOLDER) === false) 
        {
            mkdir(__DIR__  . Cache::$CACHE_FOLDER);
        }
    }

    /**
     * @param string $id
     * @param array $data
     */
    public function set_cache($id,$data)
    {
        // Filepath 
        $filename = __DIR__ . Cache::$CACHE_FOLDER . DIRECTORY_SEPARATOR . $id . '.json';

        file_put_contents($filename,$data);
    }

    /**
     * @param string $id
     * @return array|false False when no data found
     */
    public function get_cache($id)
    {
        // Filepath
        $filename = realpath(__DIR__ . Cache::$CACHE_FOLDER . DIRECTORY_SEPARATOR . $id . '.json');
        
        // File does not exist
        if($filename === false)
        {
            return false;
        }
        // Read file 
        $data = file_get_contents($filename);
        if($data === false)
        {
            return false;
        }

        $decoded_data = json_decode($data);

        // When data cannot be decode, return False
        if($decoded_data === false)
        {
            return false;
        }

        // return data
        return $decoded_data;

    }
}


    


?>