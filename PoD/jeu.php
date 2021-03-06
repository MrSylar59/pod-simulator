<?php
include_once("libs/libUtils.php");
include_once("libs/libJoueur.php");
include_once("libs/libOrdinateur.php");

session_start();

if(!valider("connecte", "SESSION"))
    rediriger("connexion.php");

$view = valider("view");

if(!$view || !file_exists("templates/$view.php"))
    $view = "status";

?>
<!DOCTYPE html>
<html lang=fr>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <link rel="icon" type="image/png" href="res/favicon.png">
        <!-- Import de Bootstrap sur la page -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <!-- Import de FontAwesome pour les icones -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
        <link rel="stylesheet" href="css/jeu.css">
        <title>PoD Simulator - Jeu</title>
    </head>

    <body>
        <header>
            <nav class="navbar navbar-expand-md navbar-dark bg-dark">
                <a href="index.html" class="navbar-brand">P0D Simulator</a>

                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#podNav" aria-controls="navbarNavAltMarkups" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" style="margin-top:10px;">
                    <div class="navbar-nav ml-auto">
                        <p class="nav-item nav-link active"><?php echo valider("pseudo", "SESSION");?></p>
                        <p class="nav-item nav-link active"><?php afficherFondsTotaux(valider("id", "SESSION"));?></p>
                        <a href="controleur.php?action=deconnexion" class="nav-item nav-link">Déconnexion</a>
                    </div>
                </div>
            </nav>
        </header>

        <main>
            <div class="row">
                <aside class="col-md-2 sidenav">
                    <div class="sidenav-cat" id="ordinateur">
                        <h3 class="sideTitle"><span class="side-underline">Mon Ordinateur (<?php echo valider("ip", "SESSION"); ?>)</span></h3>
                        <a href="jeu.php?view=status" class="btn btn-secondary <?php if($view == 'status') echo 'active' ?>">Status</a>
                        <a href="jeu.php?view=logs" class="btn btn-secondary <?php if($view == 'logs') echo 'active' ?>">Logs</a>
                        <a href="jeu.php?view=antivirus" class="btn btn-secondary <?php if($view == 'antivirus') echo 'active' ?>">Antivirus</a>
                        <a href="jeu.php?view=telechargement" class="btn btn-secondary <?php if($view == 'telechargement') echo 'active' ?>">Téléchargement</a>
                        <a href="jeu.php?view=money" class="btn btn-secondary <?php if($view == 'money') echo 'active' ?>">Porte-feuille</a>
                        <?php if(valider("ip", "SESSION") != recupIPLocal(valider("id", "SESSION"))) 
                                echo '<a href="controleur.php?action=stopAttaque" class="btn btn-secondary">Déconnexion</a>';
                        ?>
                    </div>

                    <div class="sidenav-cat" id="internet">
                        <h3 class="sideTitle"><span class="side-underline">Internet</span></h3>
                        <a href="jeu.php?view=scanner" class="btn btn-secondary <?php if($view == 'scanner') echo 'active' ?>">Scanner</a>
                        <a href="jeu.php?view=attaque" class="btn btn-secondary <?php if($view == 'attaque') echo 'active' ?>">Attaque</a>
                        <a href="jeu.php?view=bdd" class="btn btn-secondary <?php if($view == 'bdd') echo 'active' ?>">BDD Hacker</a>
                        <a href="jeu.php?view=classement" class="btn btn-secondary <?php if($view == 'classement') echo 'active' ?>">Classement</a>
                    </div>

                    <?php if(valider("ip", "SESSION") == recupIPLocal(valider("id", "SESSION"))) { ?>
                    <div class="sidenav-cat" id="magasin">
                        <h3 class="sideTitle"><span class="side-underline">Magasin</span></h3>
                        <a href="jeu.php?view=logiciels" class="btn btn-secondary <?php if($view == 'logiciels') echo 'active' ?>">Logiciels</a>
                        <a href="jeu.php?view=materiels" class="btn btn-secondary <?php if($view == 'materiels') echo 'active' ?>">Materiels</a>
                    </div>
                    <?php } ?>
                </aside>

                <section class="col-md-10" id="jeu">
                    <?php
                        ecrireMessage();
                        include("templates/$view.php");
            
                    ?>
                </section>
            </div>        
        </main>

        <footer class="row mx-auto fixed-bottom" style="background-color:#212529;">
            <div class="col-md-12">
                <p class="text-center" style="color:#fff; padding-top:16px;">P0D © Copyright 2018, tous droits réservés</p>
            </div>
        </footer>
    
        <!-- Import des librairies JS pour Bootstrap -->
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    
    </body>
</htmL>