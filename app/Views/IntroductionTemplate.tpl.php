<?php

global $tplData;

// pripojim objekt pro vypis hlavicky a paticky HTML
require(DIRECTORY_VIEWS . "/TemplateBasics.class.php");
$tplHeaders = new TemplateBasics();

// hlavicka
$tplHeaders->getHTMLHeader($tplData['title']);

// pro ucely navbar
if ($this->db->isUserLogged()) {
    if ($this->db->isUserAdmin()) {
        $tplHeaders->getUserManagement();
    }
    $tplHeaders->getProfile();
} else {
    $tplHeaders->getLogin();
}

?>
<div class="background">
    <div>
        <div class="container">
            <div class='row animated fadeIn'>
                <div class="col-md-8 text-white text-center text-md-left mt-xl-5 mb-5 fadeInLeft">
                    <h1 class="h1-responsive font-weight-bold mt-sm-5">TECHKONF - Konference technologických novinek</h1>
                    <hr style='border-color:#707070;' class="hr-light">
                    <h3 class="mb-4">Přidejte se i vy</h3>
                </div>
            </div>
        </div>
    </div>
<?php 
// paticka
$tplHeaders->getHTMLFooter($tplData['title']);
?>