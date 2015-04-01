<?php
require 'scraperwiki.php';
require 'scraperwiki/simple_html_dom.php';
//
// // Read in a page

    $jsonImg = file_get_contents("http://www2.leboncoin.fr/ajapi/get/phone?list_id=773980147");
    var_dump($jsonImg);
    if ($jsonImg != '""') {
        $i = json_decode($jsonImg);
        $src = $i->phoneUrl;
        if ($src != "") {
            $md5 = md5($src);
            if (scraperwiki::select("* from data where 'adId'='773980147'")) {
                echo "773980147 already in DB!\n";
            } else {
                $img = base64_encode(file_get_contents($src));
                scraperwiki::save_sqlite(array('adId'), array('md5' => $md5, 'adId' => "773980147", 'content' => $img));
                echo "saved 773980147 in DB\n";
            }
        }
    }

