<?php
session_start();

include_once("libs/libUtils.php");
include_once("libs/libJoueur.php");
include_once("libs/libOrdinateur.php");

// On reçoit une action sur notre contrôleur
if($action = valider("action")){
    switch($action){
        /* On inscrit un nouvel utilisateur dans la base de données */
        case "inscription":
                if($login = valider("login", "POST"))
                    if($pass = valider("pass", "POST"))
                        if($mail = valider("mail", "POST")){
                            if(inscrireJoueur($login, $pass, $mail)){
                                enregistrerMessage("Votre compte a bien été créé ! Un mail de confirmation a été envoyé à l'adresse <b>$mail</b>.");
                                rediriger("connexion.php");
                            }
                            else{
                                enregistrerMessage("Ce pseudo ou ce mail a déjà été utilisé sur ce site.", "danger");
                                rediriger("inscription.php");
                            }
                        }
        break;

        /* On valide un nouvel utilisateur ici */
        case "valider":
                if($id = valider("id")){
                    if($chaine = valider("chaine")){
                        if(validerJoueur($id, $chaine)){
                            enregistrerMessage("Votre compte est <b>actif</b> ! Vous pouvez à présent vous <b>connecter</b>.");
                            creerOrdinateur($id);
                            rediriger("connexion.php");
                        }
                        else{
                            enregistrerMessage("Votre compte est déjà validé.", "danger");
                            rediriger("inscription.php");
                        }
                    }
                }
        break;

        /* On connecte l'utilisateur et on créer sa session */
        case "connexion":
                if($login = valider("login"))
                    if($pass = valider("pass"))
                    {
                        if(connecterJoueur($login, $pass)){
                            if(valider("remember")){
                                setcookie("login", $login, time()+60*60*24*30);
                                setcookie("pass", $pass, time()+60*60*24*30);
                                setcookie("remember", true, time()+60*60*24*30);
                            }
                            else{
                                setcookie("login", "", time()-3600);
                                setcookie("pass", "", time()-3600);
                                setcookie("remember", false, time()-3600);
                            }
                            rediriger("jeu.php");
                        }
                        else{
                            enregistrerMessage("Combinaison pseudo / mot de passe invalide.", "danger");
                            rediriger("connexion.php");
                        }
                    }
        break;

        /* On déconnecte l'utilisateur et on supprime sa session */
        case "deconnexion":
                session_destroy();
                rediriger("connexion.php");
        break;

        /* On édite le fichier de logs d'un ordinateur */
        case "editLogs":
                if(($ip = valider("ip", "SESSION")) && ($logs = valider("logs"))){
                    ecrireLogs($ip, $logs);
                    enregistrerMessage("Les logs de la machine $ip ont été mis à jour.");
                    rediriger("jeu.php?view=logs");
                }
                else{
                    enregistrerMessage("Une erreur est survenue lors de l'enregistrement des logs, veuillez rééssayer.", "danger");
                    rediriger("jeu.php?view=log");
                }
        break;

        /* On sécurise des fonds sur notre machine */
        case "secFonds":
                if(($id = valider("id", "SESSION")) && ($niv = recupNiveaMat(recupIPLocal($id), "Porte_Feuille"))){
                    $montant = securiserFonds($id, $niv);

                    if($montant > 0){
                        enregistrerMessage("Vous avez sécuriser <b>$montant</b> I2C sur votre compte.");
                        rediriger("jeu.php?view=money");
                    }
                    else{
                        enregistrerMessage("Vous n'avez pas de fonds sécurisable.", "danger");
                        rediriger("jeu.php?view=money");
                    }
                }
        break;
        
        /* On achète un nouveau logiciel */
        case "acheterL":
                if(($id = valider("id", "SESSION")) && ($logi = valider("logiciel")) && ($prix = valider("prix"))){
                    if(acheter($id, $prix)){
                        augmenterNiveau($logi, $id);
                        setNiveauJoueur($id);
                        enregistrerMessage("Vous avez acheter un nouveau <b>".str_replace("_", " ", $logi)."</b> pour <b>$prix I2C</b>.");
                        rediriger("jeu.php?view=logiciels");
                    }
                    else{
                        enregistrerMessage("Vous n'avez pas assez de fonds pour acheter un nouveau <b>".str_replace("_", " ", $logi)."</b>.", "danger");
                        rediriger("jeu.php?view=logiciels");
                    }
                }
        break;

        /* On achète un nouveau materiel */
        case "acheterM":
                if(($id = valider("id", "SESSION")) && ($mat = valider("mat")) && ($prix = valider("prix"))){
                    if(acheter($id, $prix)){
                        augmenterNiveau($mat, $id);
                        setNiveauJoueur($id);
                        enregistrerMessage("Vous avez acheter un nouveau <b>".str_replace("_", " ", $mat)."</b> pour <b>$prix I2C</b>.");
                        rediriger("jeu.php?view=materiels");
                    }
                    else{
                        enregistrerMessage("Vous n'avez pas assez de fonds pour acheter un nouveau <b>".str_replace("_", " ", $mat)."</b>.", "danger");
                        rediriger("jeu.php?view=materiels");
                    }
                }
        break;
    }
}