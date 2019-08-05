<?php


class Xkcd_db {
    
    var $db;
    
    function __construct($db) {
        $this->db = $db;
    }

    /*
     * Get highest xkcd num stored in db (proxy)
     */
    public function max_num() {
        $res = $this->db->select_query("SELECT MAX(num) AS num FROM xkcd");
        $max = $res[0]['num'];
        return is_numeric($max)? $max : false;
    }
    
    public function save($xkcd) {
        
        $db = $this->db; 
        $num = $db->escape($xkcd['num']);
        $date = $db->escape($xkcd['year'].'-'.$xkcd['month'].'-'.$xkcd['day']);
        $link = $db->escape($xkcd['link']);
        $news = $db->escape($xkcd['news']);
        $safe_title = $db->escape($xkcd['safe_title']);
        $transcript = $db->escape($xkcd['transcript']);
        $alt = $db->escape($xkcd['alt']);
        $img = $db->escape($xkcd['img']);
        $basename = $db->escape($xkcd['basename']);
        $ext = $db->escape($xkcd['ext']);
        $title = $db->escape($xkcd['title']);
        $explanation = '';

        $tmp = explode(".", $xkcd['img']);
        $format = $db->escape(strtolower(array_pop($tmp)));
        if(!in_array($format, ['gif','png','jpg'])) {
            $format = '';
        }
        $sql = "INSERT INTO xkcd ("
            . "     num, date, link, news, safe_title, transcript, alt, img, "
            . "     basename, ext, title, explanation, format"
            . ") VALUES ("
            . "     '$num','$date','$link','$news','$safe_title','$transcript',"
            . "     '$alt','$img','$basename','$ext','$title','$explanation',"
            . "     '$format'"
            . ")";
        if(!$db->query($sql)) {
            die("Error: ". $db->sqlstate());
        }
    }
    
}