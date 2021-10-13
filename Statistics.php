<?php

class Statistics{
 
    private static $STATISTICS_FILE = __DIR__ . '/statistics.json';

    public function __construct() 
    {
        if (realpath(Statistics::$STATISTICS_FILE) === false) 
        {
            file_put_contents(Statistics::$STATISTICS_FILE, '{}');
        }
    }
    /**
     * @param string $id
     */
    public function increment($id)
    {
        // Get information from file 
        $data = file_get_contents(Statistics::$STATISTICS_FILE);

        // If data not found return false
        if ($data === false) 
        {
            return;
        }

        $json_data = json_decode($data, true);

        /*
         * If statistic about this query exist increment number 
         * else set the count of query to 1
         */
         if (array_key_exists($id, $json_data)) 
        {
            $json_data[$id] = $json_data[$id] + 1;
        } 
        else 
        {
            $json_data[$id] = 1;
        }

        $json_string = json_encode($json_data);

        file_put_contents(Statistics::$STATISTICS_FILE, $json_string);
    }
}
?>