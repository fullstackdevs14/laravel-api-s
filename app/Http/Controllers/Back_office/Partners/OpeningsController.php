<?php

namespace App\Http\Controllers\Back_office\Partners;

use App\Http\Controllers\Controller;
use App\Http\Requests\OpeningsRequest;
use App\Partner;
use App\Repositories\ToolsRepository;
use Session;

/**
 * Cette classe permet la modification des horaires d'ouverture d'un partenaire.
 *
 * Class OpeningsController
 * @package App\Http\Controllers\Back_office\Partners
 */
class OpeningsController extends Controller
{
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
     * OpeningsController constructor.
     * @param Partner $partner
     * @param ToolsRepository $toolsRepository
     */
    public function __construct
    (
        Partner $partner,
        ToolsRepository $toolsRepository
    )
    {
        $this->partner = $partner;
        $this->toolsRepository = $toolsRepository;
    }

    /**
     * Cette fonction retourne le formulaire permettant la modifaction des horaires d'un parteanire en base de données application.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit
    (
        $id
    )
    {
        $openings = $this->toolsRepository->getOpenings();
        $partnerOpenings = $this->partner->findOrFail($id)->openings;
        $partner = $this->partner->findOrFail($id);

        return view('partners.openings.edit', compact('partnerOpenings', 'partner', 'openings'));
    }

    /**
     * Cette fonction enregistre en base de données application les horaires soumis via la requête.
     *
     * @param OpeningsRequest $request
     * @param $partner_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function update
    (
        OpeningsRequest $request,
        $partner_id
    )
    {
        $openings = $this->partner->findOrFail($partner_id)->openings;

        $openings->monday1 = $request['monday1'];
        $openings->monday2 = $request['monday2'];
        $openings->monday3 = $request['monday3'];
        $openings->monday4 = $request['monday4'];
        $openings->tuesday1 = $request['tuesday1'];
        $openings->tuesday2 = $request['tuesday2'];
        $openings->tuesday3 = $request['tuesday3'];
        $openings->tuesday4 = $request['tuesday4'];
        $openings->wednesday1 = $request['wednesday1'];
        $openings->wednesday2 = $request['wednesday2'];
        $openings->wednesday3 = $request['wednesday3'];
        $openings->wednesday4 = $request['wednesday4'];
        $openings->thursday1 = $request['thursday1'];
        $openings->thursday2 = $request['thursday2'];
        $openings->thursday3 = $request['thursday3'];
        $openings->thursday4 = $request['thursday4'];
        $openings->friday1 = $request['friday1'];
        $openings->friday2 = $request['friday2'];
        $openings->friday3 = $request['friday3'];
        $openings->friday4 = $request['friday4'];
        $openings->saturday1 = $request['saturday1'];
        $openings->saturday2 = $request['saturday2'];
        $openings->saturday3 = $request['saturday3'];
        $openings->saturday4 = $request['saturday4'];
        $openings->sunday1 = $request['sunday1'];
        $openings->sunday2 = $request['sunday2'];
        $openings->sunday3 = $request['sunday3'];
        $openings->sunday4 = $request['sunday4'];
        $openings->update();

        Session::flash('message', 'Le partenaire a été modifié.');

        return redirect()->route('partner.edit', $partner_id);
    }

}
