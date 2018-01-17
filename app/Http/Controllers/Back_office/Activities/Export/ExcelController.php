<?php

namespace App\Http\Controllers\Back_office\Activities\Export;

use App\ApplicationUser;
use App\Http\Controllers\Controller;
use App\Order;
use App\Partner;
use App\PartnerMenu;
use Excel;
use Illuminate\Support\Facades\Config;
use MangoPay\MangoPayApi;

/**
 * Cette classe se charge de génèrer les différents fichiers excel qu'il est possible de télécharger via le back office.
 *
 * Class ExcelController
 * @package App\Http\Controllers\Back_office\Activities\Export
 */
class ExcelController extends Controller
{
    /**
     * Librairie de l'api Mangopay.
     *
     * @var MangoPayApi
     */
    private $mangoPayApi;

    /**
     * ExcelController constructor.
     * @param MangoPayApi $mangoPayApi
     */
    public function __construct
    (
        MangoPayApi $mangoPayApi
    )
    {
        $this->mangoPayApi = $mangoPayApi;
    }

    /**
     * Cette fonction retourne la vue listant les fichiers excel exportables.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){
        return view('activities.exports.index');
    }

    /**
     * Cette fonction permet l'export du fichier excel -> menus des partenaires.
     *
     * @return excel
     */
    public function menus(){

        Excel::create('PartnerMenus', function($excel){
            $excel->setTitle('PartnerMenus');

            $excel->setCreator(Config::get('constants.company_name'))
                ->setCompany(Config::get('constants.company_name'));

            $excel->setDescription('Liste de tous les menus des partenaires');


            $excel->sheet('PartnerMenus', function($sheet) {

                $sheet->fromArray(PartnerMenu::all());
            });

            $excel->export('xls');
        });

    }

    /**
     * Cette fonction permet l'export du fichier excel -> liste des items commandés.
     *
     * @return excel
     */
    public function orders(){

        Excel::create('Orders', function($excel){
            $excel->setTitle('Orders');

            $excel->setCreator(Config::get('constants.company_name'))
                ->setCompany(Config::get('constants.company_name'));

            $excel->setDescription('Liste de toutes les commandes');


            $excel->sheet('Orders', function($sheet) {

                $sheet->fromArray(Order::all());
            });

            $excel->export('xls');
        });
    }

    /**
     * Cette fonction permet l'export du fichier excel -> liste des utilisateurs de l'application.
     *
     * @return excel
     */
    public function applicationUsers(){

        Excel::create('Application_Users', function($excel){
            $excel->setTitle('Application_Users');

            $excel->setCreator(Config::get('constants.company_name'))
                ->setCompany(Config::get('constants.company_name'));

            $excel->setDescription('Liste de toutes les utilisateurs de l\'application');


            $excel->sheet('Application_Users', function($sheet) {

                $sheet->fromArray(ApplicationUser::all());
            });

            $excel->export('xls');
        });
    }

    /**
     * Cette fonction permet l'export du fichier excel -> liste des partenaires de l'application.
     *
     * @return excel
     */
    public function partners(){

        Excel::create('Partners', function($excel){
            $excel->setTitle('Partners');

            $excel->setCreator(Config::get('constants.company_name'))
                ->setCompany(Config::get('constants.company_name'));

            $excel->setDescription('Liste de toutes les partenaires de l\'application');


            $excel->sheet('Partners', function($sheet) {

                $sheet->fromArray(Partner::all());
            });

            $excel->export('xls');
        });
    }

}
