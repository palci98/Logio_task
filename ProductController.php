<?php

include_once(__DIR__ . '/Cache.php');
include_once(__DIR__ . '/Statistics.php');

class ProductController{


    private $elastic_search_driver = NULL;
    private $my_sql_driver = NULL;
    private $cache = NULL;
    private $statistics = NULL;

    function __construct(
        IElasticSearchDriver $elastic_search_driver = NULL,
        IMySQLDriver $my_sql_driver = NULL
    ) 
    {
        $this->elastic_search_driver = $elastic_search_driver;
        $this->my_sql_driver = $my_sql_driver;
        $this->cache = new Cache();
        $this->statistics = new Statistics();
    }

    /**
     * @param string $id
     * @return string|false
     */
    public function detail($id)
    {
        // try to get data from cache
        $data = $this->cache->get_cache($id);
        
        // increment statistics 
        $this->statistics->increment($id);

        // When elastic search driver is defined, use that
        if($data === false && isset($this->elastic_search_driver))
        {
            $data = $this->elastic_search_driver->findById($id);
        }
        // When mysql driver is defined, use that
        if($data === false && isset($this->my_sql_driver))
        {
            $data = $this->my_sql_driver->findProduct($id);
        }

        // No data found
        if($data === false)
        {
            return false;
        }

        // Encode found data
        $encoded_data = json_encode($data);

        // When encoding failed, return false
        if ($encoded_data === NULL) {
            return false;
        }

        // Save to cache
        $this->cache->set_cache($id, $encoded_data);

        // return data 
        return $encoded_data;

    }

}



?>