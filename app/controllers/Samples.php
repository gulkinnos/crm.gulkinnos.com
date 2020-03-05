<?php
/**
 * Created by PhpStorm.
 * User: Aleksandr Golubev <aka>gulkinnos@gmail.com
 * Date: 6/14/2018
 * Time: 4:40 PM
 */
namespace App\Controllers;
use Core\Controller;
use Core\DB;

class Samples extends Controller
{

    public function __construct($controller, $action)
    {
        parent::__construct($controller, $action);
    }

    public function indexAction()
    {

        $db = DB::getInstance();

        $this->view->render('samples/index');

//        $db->setDebugMode(1);
        /*Find example*/
        /**
        $contacts= $db->findFirst('contacts',[
        'conditions' => ['fname = ?', 'lname = ?'],
        //        'conditions' => 'lname = "Clinton"'],
        'bind' => ['bill', 'clinton'],
        'order' => 'lname, fname',
        'limit' => 5,
        ]);
        /**/


        /*Query example */
        /**
        $contactsQ = $db->query("SELECT * FROM contacts ORDER BY lname, fname;");
        /**/

        /*Get columns example*/
        /**
        $columns = $db->get_columns('contacts');
        /**/

        /*Insert example*/
        /*
        $db = DB::getInstance();

        $fields = [
            'fname' => 'Toni',
            'lname' => 'Parham',
            'email' => 'Toni@parham4545.com',
        ];


        $contactsQ = $db->insert('contacts', $fields);


        /**/
        /*Update example*/
        /**
         * $db = DB::getInstance();
         *
         * $fields = [
         * 'fname' => 'Antoinette',
         * 'lname' => 'Parham',
         * 'email' => 'Antoinette@parham4545.com',
         * ];
         *
         *
         * $contactsQ = $db->update('contacts', 4, $fields);
         * /**/

        /**/
        /*Delete example*/
        /**
         * $db = DB::getInstance();
         *
         * $contactsQ = $db->delete('contacts', 2);
         * /**/

    }
}