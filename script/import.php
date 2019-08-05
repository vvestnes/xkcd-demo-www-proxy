<?php
/**
 * 
 * This script is responsible for downloading and storing all xkcd to 
 * the the proxy database. 
 * 
 * This script could be set to run on a daily basis by a crontab.
 * 
 *  --- Crontab setup --------------------------------------------------------------
 * |                                                                                |
 * |  0 12 * * * php /Users/vidarvestnes/Documents/www/xkcd/script/import.php 2>&1  |
 * |                                                                                |
 *  --------------------------------------------------------------------------------
 * 
 * Author: Vidar Vestnes 2019
 *  
 */

require_once __DIR__.'/../src/lib/db.php';
require_once __DIR__.'/../src/lib/xkcd_db.php';
require_once __DIR__.'/../src/lib/xkcd_api.php';


// Create a database connection
$db = new Db();
$db->connect();


$xkcd_db = new Xkcd_db($db);
$xkcd_api = new Xkcd_api();

// Get the highest xkcd num already downloaded. 
// This is needed to know what xkcd is the next to download.
$max_num = $xkcd_db->max_num();
if(is_numeric($max_num)) {
    $next = $max_num + 1;
} else {
    $next = 1;
}

// Fetches the current (latest) xkcd num (id). 
$current_num = $xkcd_api->fetch_data()['num'];
if($next <= $current_num) {
    // Downloads and save xkcd's which are not previously stored.
    for($num = $next; $num <= $current_num; $num++) {
        $data = $xkcd_api->fetch_data($num);
        
        if(!is_array($data)) {
            // Jump to next item if data is empty/invalid
            continue;
        }
        $img = false;
        if($data['img']) {
            // Download image (png, gif or jpeg)
            $img = file_get_contents($data['img']);
        }
        if($img) {
            // Save image to disk
            $pathinfo = pathinfo($data['img']);
            $data['basename'] = $data['num'].'_'.$pathinfo['basename'];
            $data['ext'] = $pathinfo['extension'];
            $size = file_put_contents(__DIR__.'/../img/'.$data['basename'], $img);
        } else {
            $data['basename'] = '';
            $data['ext'] = '';
            $size = 0;
        }
        // Save metadata
        $xkcd_db->save($data);
        
        echo "Saved: xkcd num " . $num . "  [". ceil($size/1024)." Kb]\n";
    }
} else {
    echo "xkcd_db is already up to date! \n";
}

// Closing db-connection.
$db->close();


