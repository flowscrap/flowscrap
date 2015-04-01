<?

require 'scraperwiki.php';
require 'scraperwiki/simple_html_dom.php';

$html = scraperwiki::scrape("http://www.topannonces.fr/annonces-immobilier-u7.html?type=1");

$dom = new simple_html_dom();
$dom->load($html);
$annonces = $dom->find("div.marginbtm5 a");
var_dump($annonces);
for ($i=0; $i < 5; $i++){
    $annonce_html = scraperwiki::scrape($annonces[$i]->href);
    $annonceDom = new simple_html_dom();
    $annonceDom->load($annonce_html);
    $id =  $annonceDom->find("#ClassifiedId")->value;
    $titre = $annonceDom->find(".detailTitle h2", 0)->plaintext;
    $phoneNb = $annonceDom->find(".zone-tel", 0)->plaintext;
    if (scraperwiki::select("* from data where 'Id'=".$id."")) {
        echo $phoneId ." already in DB!\n";
    } else {
        scraperwiki::save_sqlite(array('Id'), array('Id' => $id, 'titre' => $titre, 'Telephone' => $phoneNb));
        echo "saved ".$id." in DB\n";
    }
}

?>
