<?php

global $tplData;

// pripojim objekt pro vypis hlavicky a paticky HTML
require(DIRECTORY_VIEWS . "/TemplateBasics.class.php");
$tplHeaders = new TemplateBasics();
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

?>
<!-- vypis informaci o konferenci  -->
<div class='container'>
    <div class='row animated fadeIn'>
        <div class='col-xs-1 mt-4 text-center'>
            <h1>O NÁS </h1>
            <p>Jsme konference založena roku 2020 zaměřená na novinky ve světě technologií. Přidejte se i vy a poznejte (nebo i vyzkoušejte!) budoucnost</p>
            <img src="../../img/logo2.png" height="120px" alt="Úvod">
        </div>
    </div>
</div>
<?php $tplHeaders->getHTMLFooter($tplData['title']);
?>