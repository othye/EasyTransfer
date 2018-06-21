<?php

namespace Model;

use DateTime;

class Transfer extends \PicORM\Model
{
    protected static $_tableName = 'transfer';
    protected static $_primaryKey = 'id';
    protected static $_relations = array();

    protected static $_tableFields = array(
        'exp_email',
        'dest_email',
        'path',
        'fake_path',
        'message',
        'creation_date'
    );
    public $id;
    public $exp_email;
    public $dest_email;
    public $path;
    public $fake_path;
    public $message;
    public $creation_date;


        function __construct()
    {
        $now = new DateTime;
        $this->creation_date = $now->format('Y-m-d H:i:s');
    }

 //    protected static function defineRelations()
	// {
	// 	self::addRelationOneToMany('id', Todo::class, 'category_id');
	// }

}