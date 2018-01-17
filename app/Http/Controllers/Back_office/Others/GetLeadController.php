<?php

namespace App\Http\Controllers\Back_office\Others;

use App\Http\Controllers\Controller;

/**
 * Cette classe n'est plus utilisée. Elle servait à lister les leads provenant de base de données éxtérieures.
 *
 * Class GetLeadController
 * @package App\Http\Controllers\Back_office\Others
 */
class GetLeadController extends Controller
{

    public function __construct()
    {}

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    //public function index(){
    //    $db = new \PDO('mysql:host=sipperapcathomas.mysql.db;dbname=sipperapcathomas;charset=utf8', 'sipperapcathomas', 'Sipper1617');
    //    $req = $db->prepare('SELECT * FROM db_email ORDER BY db_id DESC');
    //    $req->execute();
    //    $records = $req->fetchAll();
//
    //    //$records = DB::connection("mysql2")->select("SELECT * FROM `db_email` ORDER BY `db_id` DESC");
//
    //    $count = 0;
    //    foreach ($records as $record){
    //        $count ++;
    //    }
//
    //    return view('others.leads.index', compact('records', 'links', 'count'));
    //}
}
