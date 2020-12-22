<?php

global $tplData;

require("TemplateBasics.class.php");
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
<!-- ------------------------------------------------------------------------------------------------------- -->

<?php

?>
</div>
<!-- vypis error stranky -->
<div class='container'>
    <div class='row'>
        <div class='col mt-5 pb-4'>
            <h2 class='col-xs-1 text-center'>Error - požadovaná stránka nebyla nalezena</h2>
        </div>
    </div>
    <div class='row text-center'>
        <div class='col'>
            <i class="fas fa-cogs fa-7x"></i>
        </div>
    </div>
</div>

<?php $tplHeaders->getHTMLFooter($tplData['title']);
?>