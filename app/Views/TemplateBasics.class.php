<?php

/**
 * Trida vypisujici HTML hlavicku a paticku stranky.
 */
class TemplateBasics
{

  /**
   *  Vrati vrsek stranky az po oblast, ve ktere se vypisuje obsah stranky.
   *  @param string $pageTitle    Nazev stranky.
   */
  public function getHTMLHeader(string $pageTitle)
  {
?>

    <!DOCTYPE html>
    <html lang="cs">

    <head>
      <base href="/">
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta name="description" content="Stránky konference">
      <meta name="author" content="Radek Svamberg">

      <title>Konfereční stránky</title>
      <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
      <link rel="stylesheet" href="css/animate.css">
      <link rel="stylesheet" href="css/style.css">
      <script src="../../ckeditor/ckeditor.js"></script>


    </head>

    <body>
      <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarToggle" aria-controls="navbarToggle" aria-expanded="false" aria-label="Navigace">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class='container'>
          <div class='navbar-collapse collapse' id="navbarToggle">

            <a class='navbar-brand' href="/uvod">
              <img src="../../img/logo2.png" height="60px" alt="Úvod">
            </a>
            <ul class='nav navbar-nav ml-auto'>
              <li class='nav-item'>
                <a class='nav-link' href="/konference">Konference <em class="fas fa-address-card"></em></a>
              </li>
              <li class='nav-item'>
                <a class='nav-link' href="/prispevky">Příspěvky <em class="far fa-newspaper"></em></a>
              </li>
              <li class='nav-item'>
                <a class='nav-link' href="/terminy">Termíny konferencí <em class="far fa-calendar-alt"></em></a>
              </li>




            <?php
          }
          /**
           *  Vrati profil moznost v navbaru.
           */
          public function getProfile()
          {
            ?>
              <li class='nav-item'>
                <a class='nav-link' href='/login'>Profil <em class="fas fa-user"></em></a>
              </li>
            </ul>
          </div>
        </div>

      </nav>

    <?php
          }
          /**
           *  Vrati login moznost v navbaru.
           */
          public function getLogin()
          {
    ?>
      <li class='nav-item'>

        <a class='nav-link' href='/login'>Přihlásit se <em class="fas fa-sign-in-alt"></em></a>
      </li>
      </ul>
      </div>
      </div>

      </nav>

    <?php
          }
          /**
           *  Vrati sprava uzivatelu moznost v navbaru.
           */
          public function getUserManagement()
          {
    ?>
      <li class='nav-item'>

        <a class='nav-link' href='/sprava'>Spravovat uživatele <em class="fas fa-users"></em></a>
      </li>

    <?php
          }
          /**
           *  Vrati paticku stranky.
           */
          public function getHTMLFooter()
          {
    ?>
      <br>
      </div>
      </div>

      <div class='footer'>
        <footer class='text-right text-lg-start fixed-bottom'>
          <div class="container">
            Semestrální práce Radek Švamberg
          </div>
        </footer>
      </div>
      
      <!-- SCRIPTS -->
      <script src="https://kit.fontawesome.com/9eeb6a7d46.js" crossorigin="anonymous"></script>
      <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
      <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
      <script src="js/scripts.js"></script>

    </body>

    </html>

<?php
          }
        }

?>