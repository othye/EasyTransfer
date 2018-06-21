<?php

namespace Controllers;

use Core\Controllers\Controller;
use Model\Transfer;

class TransferController extends Controller {


    public function index($page = "1"){
        echo $this->twig->render('transfers/index.html.twig');
    }

    // la fonction qui permette la saisie des champs sur le fomulaire
    public function add(){

        // Verification si les inputes ne sont pas vide
        if(isset($_POST['upload']) && !empty($_POST['exp_email']) && !empty($_POST['dest_email']) && !empty($_FILES['uploadFile'])) {
            $transfer = new Transfer();

            $transfer->exp_email = htmlspecialchars($_POST['exp_email']);
            $transfer->dest_email = htmlspecialchars($_POST['dest_email']);
            $transfer->message = htmlspecialchars($_POST['message']);
            $file = $_FILES['uploadFile']['name'];

            $ext = pathinfo($_FILES['uploadFile']['name'], PATHINFO_EXTENSION);
            $fake_file = uniqid().'.'.$ext;

            $path = 'app/transfers/';
            $size_max = 524288000; //taille max = 500 Mo
            $size_file = filesize($_FILES['uploadFile']['tmp_name']);

            // verification du format d-email (exemple]you.com FORMAT VALID)
            if(filter_var($_POST['dest_email'], FILTER_VALIDATE_EMAIL) && filter_var($_POST['exp_email'], FILTER_VALIDATE_EMAIL)){

                // Verification de la taille du fichier ( max =500Mo )
                if ($size_file <= $size_max) {

                    // verification si le fichier a était bien enregistrer sur le dossier transfers
                    if(move_uploaded_file($_FILES['uploadFile']['tmp_name'], $path.$fake_file)){
                        $transfer->path = $file;
                        $transfer->fake_path = $fake_file;

                        $transfer->save();

                        $id = $transfer->id;
                        $exp_email = $transfer->exp_email;
                        $dest_email = $transfer->dest_email;
                        $message = $transfer->message;

                        
                        $id = $transfer->id;
                        $fakeId = rand(100000,900000);
                        $fake= $fakeId.$id;

                        $this->sendeMailDest($exp_email, $dest_email, $file, $this::formatBytes($size_file), $fake, $message);
                        $this->sendeMailExp($exp_email, $dest_email, $file, $this::formatBytes($size_file), $fake, $message);

                        /* $this->flashbag->set('alert', [
                            'type' => 'success',
                            'msg' => 'Le transfert s\'est déroulé avec succès !'
                        ]); */

                        echo $this->twig->render('transfers/result.html.twig',[
                            'file' => $file,
                            'fake' => $fake,
                            'dest_email' => $_POST['dest_email'],
                            'size' => $this::formatBytes($size_file)
                        ]);

                    }else{            
                        $this->flashbag->set('alert', [
                            'type' => 'warning',
                            'msg' => 'Echec du téléchargement'
                        ]);
                        $this->url->redirect(''); 
                    }
                
                }else{
                    $this->flashbag->set('alert', [
                        'type' => 'warning',
                        'msg' => 'Fichier trop volumineux'
                    ]);
                    $this->url->redirect(''); 
                }
            }else{                    
                $this->flashbag->set('alert', [
                    'type' => 'warning',
                    'msg' => 'Format email invalide'
                ]);
                $this->url->redirect('');
            }
        }else{
            $this->flashbag->set('alert', [
                'type' => 'warning',
                'msg' => 'Merci de remplir tous les champs'
            ]);
            $this->url->redirect('');
        }         
    }

    // Fonction qui permette de telecharger les fichier par les utulisateurs.
    public function download($id){

        $id = substr($id, 6);
        $transfer = Transfer::findOne([
            'id' => $id
        ]);

        $fake_file = dirname(__FILE__,2).'/transfers/'.$transfer->fake_path;
        $file = $transfer->path;
        $mime = mime_content_type($fake_file); 
        header('Content-Description: File Transfer');
        header('Content-Type:'.$mime.'');
        header('Content-Disposition: attachment; filename='.basename($file));
        header('Content-Transfer-Encoding: binary');
        ob_clean();
        flush();
        readfile($fake_file);
        die;

    }

    //Fonction permette l'envoie du mail au destinataires
    public function sendeMailDest($exp_email, $dest_email, $file, $size_file, $fake, $message){

        $to         = $dest_email;
        $headers    = 'From: "contact" <info@easytransfer.com>' . "\r\n";
        $headers    .= "Mime-Version: 1.0\n";
        $headers    .= "Content-Transfer-Encoding: 8bit\n";
        $headers    .= "Content-type: text/html; charset= utf-8\n";
        $subject = 'No-reply Easy Transfer: download your file';

        $bodyHtml = $this->twig->parse('emails/dest_email.html.twig', [
            'exp_email' => $exp_email,
            'dest_email' => $dest_email,
            'file' => $file,
            'size' => $size_file,
            'id' => $fake,
            'message' => $message

        ]);
            mail($to, $subject, $bodyHtml, $headers);
    }
    
    //Fonction permette l'envoie du mail au expiditeurs
    private function sendeMailExp($exp_email, $dest_email, $file, $size_file, $fake, $message){

        $to         = $exp_email;
        $headers    = 'From: "contact" <info@easytransfer.com>' . "\r\n";
        $headers    .= "Mime-Version: 1.0\n";
        $headers    .= "Content-Transfer-Encoding: 8bit\n";
        $headers    .= "Content-type: text/html; charset= utf-8\n";
        $subject = 'No-reply Easy Transfer: your file has been sent';

        $bodyHtml = $this->twig->parse('emails/exp_email.html.twig', [
            'exp_email' => $exp_email,
            'dest_email' => $dest_email,
            'file' => $file,
            'size' => $size_file,
            'id' => $fake,
            'message' => $message

        ]);
            mail($to, $subject, $bodyHtml, $headers);
    }

    //Fonction permette de recuperer le lien de telechargement via l'ID 
    public function grabFile($id){

        $fake = $id;
        $id = substr($id, 6);
        $transfer = Transfer::findOne([
            'id' => $id
        ]);
        $pathtofile = 'app/transfers/'.$transfer->fake_path;


        echo $this->twig->render('transfers/download.html.twig',[
            'exp_email' => $transfer->exp_email,
            'file' => $transfer->path,
            'fake' => $fake,
            'message' => $transfer->message,
            'size' => $this::formatBytes(filesize($pathtofile))

        ]);
    }

    // Fontion permette de convertir la taille du fichier du octets au Ko, Mo, Go, To
    public function formatBytes($size, $precision = 2){

        $base = log($size, 1024);
        $suffixes = array('octets', 'Ko', 'Mo', 'Go', 'To');   
        return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)];
    }

}

?>