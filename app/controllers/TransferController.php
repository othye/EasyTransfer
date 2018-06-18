<?php

namespace Controllers;

use Core\Controllers\Controller;
use Model\Transfer;

class TransferController extends Controller {

    /**
     * Index method
     *
     * @param string $page
     * @return void
     */
    public function index($page = "1") 
    {
        //$transfers = Transfer::find();

        echo $this->twig->render('transfers/index.html.twig');
    }


    /**
     * Deleting category
     *
     * @param int $id
     * @return void
     */
    // public function delete($id) 
    // {
    //     $category = Category::findOne([
    //         'id' => $id
    //     ]);

    //     $category->delete();

    //     $this->flashbag->set('alert', [
    //         'type' => 'success',
    //         'msg' => 'Category deleted !'
    //     ]);

    //     $this->url->redirect('categories');
    // }

    /**
     * Add category
     *
     * @return void
     */
    public function add()
    {
        $transfer = new Transfer();


        $transfer->exp_email = $_POST['exp_email'];
        $transfer->dest_email = $_POST['dest_email'];
        $file = $_FILES['uploadFile']['name'];

        $path = 'app/transfers/';
        var_dump( $_FILES['uploadFile']['name']);
        var_dump($path);
        if (move_uploaded_file($_FILES['uploadFile']['tmp_name'], $path.$file)) {
            $transfer->path = $file;

            $transfer->message = $_POST['message'];

            $transfer->save();


            $this->flashbag->set('alert', [
                'type' => 'success',
                'msg' => 'transfer added !'
            ]);

            //$this->url->redirect('');
            echo $this->twig->render('transfers/result.html.twig',[
                
                'file' => $file,
                'path' => $path,
                'dest_email' => $_POST['dest_email']
            ]);
        }else{
            die();
        }
        



    }

}