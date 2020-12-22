<?php

global $tplData;

// pripojim objekt pro vypis hlavicky a paticky HTML
require(DIRECTORY_VIEWS . "/TemplateBasics.class.php");
$tplHeaders = new TemplateBasics();

?>
<!-- ------------------------------------------------------------------------------------------------------- -->

<!-- Vypis obsahu sablony -->
<?php

// hlavicka
$tplHeaders->getHTMLHeader($tplData['title']);

// pro ucely navbaru

if ($this->db->isUserLogged()) {
    if ($this->db->isUserAdmin()) {
        $tplHeaders->getUserManagement();
    }
    $tplHeaders->getProfile();
} else {
    $tplHeaders->getLogin();
}

// vypis clanku
$res = "";

// pokud jsou terminy
if (array_key_exists('allEvents', $tplData)) {
    echo "<div class='col-xs-1 text-center' id='terminy'>";
    // vypis vsech terminu
    foreach ($tplData['allEvents'] as $d) {
        $res .= "<div class='container'>";
        $res .= "<div class='row animated fadeIn'>";
        $res .= "<div class='col mt-3'>";
        $res .= "<div>";
        $res .= "<div> <h2>$d[nazev]</h2></div></div>";
        $res .= "<div>";
        $res .= "<b>Datum konání:</b> " . date("d. m. Y, H:i", strtotime($d['datetime'])) . "<br><br>";
        $res .= "<b>Místo konání:</b> " . $d['lokace'] . "<br><br>";

        $res .= "<div><b>Popis:</b> $d[obsah]</div><br>";
        $res .= "<hr>";
        }
        $res .= "</div>";
    }
else {
    $res .= "Termíny nenalezeny";
}
echo $res;
echo "</div>";
echo "</div>";
echo "</div>";
echo "</div>";


$tplHeaders->getHTMLFooter($tplData['title']);

?>