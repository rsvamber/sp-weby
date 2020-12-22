<?php

global $tplData;
require(DIRECTORY_VIEWS . "/TemplateBasics.class.php");
$tplHeaders = new TemplateBasics();

// hlavicak
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
<div class='container'>
    <div class='row'>
        <div class='col'>
            <div class='card'>
                <div class='card-body'>
                <?php 
                    // alerty
                    if (isset($tplData['register'])) {
                        echo "<div class='alert alert-primary'>$tplData[register]</div>"; 
                    }
                ?>
                    <div class="prihlaseni scene animated fadeIn">
                        <form action="" method="POST">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Zadejte emailovou adresu</label>
                                <input type="email" class="form-control" name="email" id="inputEmail" placeholder="Zadejte email" required>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Zadejte jméno</label>
                                <input type="username" class="form-control" name="jmeno" id="username" placeholder="Zadejte jméno" required>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Zadejte přezdívku</label>
                                <input type="username" class="form-control" name="login" id="username" placeholder="Zadejte přezdívku" required>
                            </div>
                            <div class="form-group">
                                <label for="pw1">Zadejte heslo</label>
                                <input type="password" class="form-control" name="heslo" id="pw1" placeholder="Heslo" required>
                                <label for="pw2">Zopakujte heslo</label>
                                <input type="password" class="form-control" name="heslo2" id="pw2" placeholder="Znovu heslo" required>
                                <button type="submit" name="registrationAction" class="btn btn-primary mt-4">Odeslat</button>
                        </form>
                    </div>
                </div>
                </div>

                </div>

            </div>
        </div>
                    
        <?php
        // paticka
        $tplHeaders->getHTMLFooter($tplData['title']);
        ?>