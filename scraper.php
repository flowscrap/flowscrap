<?

require 'scraperwiki.php';
require 'scraperwiki/simple_html_dom.php';

$html = scraperwiki::scrape("http://www.leboncoin.fr/_immobilier_/offres/centre/occasions/?f=p");

$dom = new simple_html_dom();
$dom->load($html);
$annonces = $dom->find(".list-lbc a");

for ($i=0; $i < 5; $i++){
    $annonce_html = scraperwiki::scrape($annonce[$i]->href);
    $annonce_dom = new simple_html_dom();
    $annonce_dom->load($annonce_html);
    $titre = $annonce_dom->find("#ad_subject", 0)->plaintext;
    $phoneLink = $annonce_dom->find("#phoneNumber a", 0);
    if ($phoneLink->href != "") {
        $phoneId = str_replace('javascript:getPhoneNumber("http://www2.leboncoin.fr", ', '', $phoneLink->href);
        $phoneId = str_replace(')', '', $phoneId);
        $jsonImg = scraperwiki::scrape("http://www2.leboncoin.fr/ajapi/get/phone?list_id=". $phoneId ."");
        var_dump($jsonImg);
        if ($jsonImg != '""') {
            $i = json_decode($jsonImg);
            $src = $i->phoneUrl;
            if ($src != "") {
                $md5 = md5($src);
                if (scraperwiki::select("* from data where 'adId'=".$phoneId."")) {
                    echo $phoneId ." already in DB!\n";
                } else {
                    $img = base64_encode(file_get_contents($src));
                    scraperwiki::save_sqlite(array('adId'), array('md5' => $md5, 'adId' => $phoneId, 'titre' => $titre, 'content' => $img));
                    echo "saved ".$phoneId." in DB\n";
                }
            }
        }
    }
}

?>
