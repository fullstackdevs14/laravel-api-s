<?php

namespace App\Repositories;

use App\Countries;
use App\Excuses;
use App\Hooks;
use App\MenuCategories;
use App\Openings;
use App\PartnerCategories;
use App\Taxes;

/**
 * Cette classe sert de dépôt et gère les opérations courantes pour différents models, qui servent d'outils pour accèder
 * à des données qui sont très peut modifiées en base de données application.
 *
 * Class ToolsRepository
 * @package App\Repositories
 */
class ToolsRepository
{
    /**
     * C'est un model.
     *
     * @var Taxes
     */
    private $taxes;
    /**
     * C'est un model.
     *
     * @var Hooks
     */
    private $hooks;
    /**
     * C'est un model.
     *
     * @var Excuses
     */
    private $excuses;
    /**
     * C'est un model.
     *
     * @var Openings
     */
    private $openings;
    /**
     * C'est un model.
     *
     * @var Countries
     */
    private $countries;
    /**
     * C'est un model.
     *
     * @var MenuCategories
     */
    private $menuCategories;
    /**
     * C'est un model.
     *
     * @var PartnerCategories
     */
    private $partnerCategories;

    /**
     * ToolsRepository constructor.
     * @param Taxes $taxes
     * @param Hooks $hooks
     * @param Excuses $excuses
     * @param Openings $openings
     * @param Countries $countries
     * @param MenuCategories $menuCategories
     * @param PartnerCategories $partnerCategories
     */
    public function __construct
    (
        Taxes $taxes,
        Hooks $hooks,
        Excuses $excuses,
        Openings $openings,
        Countries $countries,
        MenuCategories $menuCategories,
        PartnerCategories $partnerCategories
    )
    {
        $this->taxes = $taxes;
        $this->hooks = $hooks;
        $this->excuses = $excuses;
        $this->openings = $openings;
        $this->countries = $countries;
        $this->menuCategories = $menuCategories;
        $this->partnerCategories = $partnerCategories;
    }

    /**
     * Cette fonction retourne toutes les catégories actives (pour les menus).
     *
     * @return \Illuminate\Support\Collection
     */
    public function getActiveCategories()
    {
        return $this->menuCategories->where('active', 1)->get();
    }

    /**
     * Cette fonction retourne l'id d'une catégorie pour une catégorie renseignée sous forme de cha$ine de caractères.
     *
     * @param $category
     * @return mixed
     */
    public function getCategoryIdWithCategory
    (
        $category
    )
    {
        $menuCategories = $this->menuCategories->get();
        foreach ($menuCategories as $cat) {
            if ($cat->category == $category) {
                $category_id = $cat->id;
            }
        }
        return $category_id;
    }

    /**
     * Cette fonction retourne toutes les taxes actives.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getActiveTaxes()
    {
        return $this->taxes->where('active', 1)->get();
    }

    /**
     * Cette fonction retourne tous les horaires d'ouverture qu'il est possible d'enregistré pour un partenaire.
     * Cela permet de mofifier les options des formulaire en modifiant directement la base de données.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getOpenings()
    {
        return $this->openings->get();
    }

    /**
     * Cette fonctoion retourne toutes les différentes catégories de partenaires.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getPartnerCategories()
    {
        return $this->partnerCategories->get();
    }

    /**
     * Cette fonction retourne les messages d'excuses actifs (message utilisés pour l'enregistrement d'un incident en base
     * de données application.
     *
     * @return mixed
     */
    public function getActiveExcuses()
    {
        return $this->excuses->where('active', 1)->get();
    }

    /**
     * Cette fonction retourne les différents hooks qu'il est possible d'utiliser pour d'éclencher l'envoi d'un email
     * lorsqu'une anomalie est consatée par l'api MangoPay.
     *
     * @return mixed
     */
    public function getHooks()
    {
        return $this->hooks->get();
    }

    /**
     * Cette fonction retourne la liste des pays ainsi que leur code d'indentification enregistré en base de données
     * application.
     *
     * @return mixed
     */
    public function getCountries()
    {
        return $this->countries->get();
    }

}