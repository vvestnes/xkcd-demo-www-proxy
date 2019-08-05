<?php
class Xkcd_api {
    
    /**
     * Fetch a single xkcd's data by its "num" (unique id)
     * 
     * if $num not specified, the current (latest) xkcd will be fetched.
     * 
     * @param type $num int (optional) The num of the xkcd 
     * @return type mixed json data as array or false if xkcd num is not found.
     */
    public function fetch_data($num = false) {
        if(is_numeric($num)) {
            $url = 'https://xkcd.com/'.$num.'/info.0.json';
        } else {
            $url = 'http://xkcd.com/info.0.json';
        }
        try {
            $str = file_get_contents($url);
            $data = json_decode($str, true);
        } catch (Exception $ex) {
            $data = false;
        }
        return $data;
    }
    
}