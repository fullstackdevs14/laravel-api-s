<?php

namespace App\Repositories;

use App\MenuCategories;
use App\Partner;
use App\PartnerMenu;

/**
 * Cette classe sert de dépôt et gère les opérations courantes pour le model "PartnerMenu".
 *
 * Class PartnerMenuRepository
 * @package App\Repositories
 */
class PartnerMenuRepository
{
    /**
     * C'est un model.
     *
     * @var PartnerMenu
     */
    private $partnerMenu;

    /**
     * C'est un model.
     *
     * @var Partner
     */
    private $partner;

    /**
     * C'est un dépôt.
     *
     * @var ToolsRepository
     */
    private $toolsRepository;

    /**
     * PartnerMenuRepository constructor.
     * @param PartnerMenu $partnerMenu
     * @param Partner $partner
     * @param ToolsRepository $toolsRepository
     */
    public function __construct
    (
        PartnerMenu $partnerMenu,
        Partner $partner,
        ToolsRepository $toolsRepository
    )
    {
        $this->partnerMenu = $partnerMenu;
        $this->partner = $partner;
        $this->toolsRepository = $toolsRepository;
    }

    /**
     * Cette fonction retourne l'item dont l'id est renseigné dans les paramètres renseignés si l'item appratient bien
     * au partenaire dont l'id est renseigné dans les paramètres.
     *
     * C'est un moyen de sécurisé un peu plus la commande.
     *
     * @param $partner_id
     * @param $item_id
     * @return mixed
     */
    public function secureFindItemForAPartner
    (
        $partner_id,
        $item_id
    )
    {
        $item = $this->partner->findOrFail($partner_id)->menu->where('id', $item_id)->first();
        return $item;
    }

    /**
     * Cette fonction créer un nouvel item dans la table "partners_menus".
     *
     * @param $partner_id
     * @param $name
     * @param $quantity
     * @param $price
     * @param $HHPrice
     * @param $tax
     * @param $alcohol
     * @param $category_id
     * @param $ingredients
     * @param $availability
     * @return $this|\Illuminate\Database\Eloquent\Model
     */
    public function newItem
    (
        $partner_id,
        $name,
        $quantity,
        $price,
        $HHPrice,
        $tax,
        $alcohol,
        $category_id,
        $ingredients,
        $availability
    )
    {
        $item = $this->partnerMenu->create([
            'partner_id' => $partner_id,
            'name' => $name,
            'quantity' => $quantity,
            'price' => $price,
            'HHPrice' => $HHPrice,
            'tax' => $tax,
            'alcohol' => $alcohol,
            'category_id' => $category_id,
            'ingredients' => $ingredients,
            'availability' => $availability
        ]);
        $item->save();

        return $item;
    }

    /**
     * Cette fonction met à jour un item dans la table "partners_menus".
     *
     * @param $item_id
     * @param $partner_id
     * @param $name
     * @param $quantity
     * @param $price
     * @param $HHPrice
     * @param $tax
     * @param $alcohol
     * @param $category_id
     * @param $ingredients
     * @param $availability
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    public function updateItem
    (
        $item_id,
        $partner_id,
        $name,
        $quantity,
        $price,
        $HHPrice,
        $tax,
        $alcohol,
        $category_id,
        $ingredients,
        $availability

    )
    {
        $item = $this->partnerMenu->findOrFail($item_id);
        $item->partner_id = $partner_id;
        $item->name = $name;
        $item->quantity = $quantity;
        $item->price = $price;
        $item->HHPrice = $HHPrice;
        $item->tax = $tax;
        $item->alcohol = $alcohol;
        $item->category_id = $category_id;
        $item->ingredients = $ingredients;
        $item->availability = $availability;
        $item->update();

        return $item;
    }

    /**
     * Cette fonction supprime un item de la table "partners_menus".
     *
     * @param $item_id
     * @return bool|null
     */
    public function deleteItem
    (
        $item_id
    )
    {
        $item = $this->partnerMenu->findOrFail($item_id)->delete();
        return $item;
    }

    /**
     * Cette fonction retourne le menu d'un partner pour un affichage dans le back office.
     *
     * @param $partner_id
     * @return array
     */
    public function getMenuSortByCategories
    (
        $partner_id
    )
    {
        $menu = $this->partner->findOrFail($partner_id)->menu->sortBy(function ($item) {
            return $item->category_id . '-' . $item->name;
        })->groupBy('category_id');

        $categories = MenuCategories::all();

        $newMenu = [];
        foreach ($menu as $category => $items) {
            foreach ($categories as $cat) {
                if ($category == $cat->id) {
                    $newMenu[$cat->category] = $items;
                }
            }
        }
        $menu = $newMenu;

        return $menu;
    }

    /**
     * Cette fonction retourne le menu d'un partner pour un affichage dans l'interface du partenaire..
     *
     * @param $partner_id
     * @return object
     */
    public function getMenuSortByCategoriesForPartner
    (
        $partner_id
    )
    {
        $partner = $this->partner->findOrFail($partner_id);

        $menu = $partner->menu()->get()
            ->sortBy(function ($item) {
                return $item->category_id . '-' . $item->name;
            })
            ->groupBy('category_id');

        $categories = $this->toolsRepository->getActiveCategories();

        $newMenu = [];
        foreach ($menu as $category => $items) {
            foreach ($categories as $cat) {
                if ($category == $cat->id) {
                    $newMenu[$cat->category] = $items;
                }
            }
        }

        $menu = $newMenu;

        $newMenu = [];
        foreach ($menu as $key => $value) {
            array_push($newMenu, ['category' => $key] + ['items' => $value]);
        }

        $lastMenu = (object)['menu' => $newMenu];

        foreach ($lastMenu->menu as $category) {
            foreach ($category['items'] as $item) {
                $item['price'] = floatval($item['price']);
                $item['HHPrice'] = floatval($item['HHPrice']);
                $item['category'] = $category['category'];
            }
        }

        return $lastMenu;
    }

}