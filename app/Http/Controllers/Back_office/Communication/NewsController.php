<?php

namespace App\Http\Controllers\Back_office\Communication;

use App\Http\Controllers\Controller;
use Session;

/**
 * Cette classe sert à suivre les flux rss de différents sites.
 * Elle permet de cibler des posts intéréssant pour les envoyer sur les RS.
 *
 * Class NewsController
 * @package App\Http\Controllers\Back_office\Communication
 */
class NewsController extends Controller
{
    /**
     * Liste de tous les posts encoyés à la vue.
     *
     * @var array
     */
    private $posts = [];

    /**
     * Message d'erreur envoyé en session flash, lorsqu'une source insérée renvoi une erreur.
     * C'est normalement une mauvaise mise en place du flux rss par le site.
     *
     * @var array
     */
    private $errorSources;

    /**
     * Cette fonction extrait les informations des flux rss grâce à la méthode privée extractFeeds() et les prépare pour l'affichage.
     * ELle trie les publication par date et change le format d'affichage de la date.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $this->extractFeeds('https://www.paris-friendly.fr/flux-rss-les-bons-plans.xml');
        $this->extractFeeds('https://www.aperodujeudi.com/feed/');
        $this->extractFeeds('https://www.sortiraparis.com/rss/sortir');
        $this->extractFeeds('https://feeds.feedburner.com/parisbouge');
        $this->extractFeeds('http://happyfoodblog.fr/feed/');
        $this->extractFeeds('https://www.pariszigzag.fr/category/sortir-paris/bars-cafes-terrasses-paris/feed');
        $this->extractFeeds('http://www.sofizz.fr/index.php/feed/');
        $this->extractFeeds('https://www.anousparis.fr/a-boire/feed/');
        $this->extractFeeds('http://www.lefigaro.fr/rss/figaro_flash-actu.xml');
        $this->extractFeeds('https://mademoisellebonplan.fr/feed/');
        $this->extractFeeds('https://www.society19.com/fr/feed/');
        $this->extractFeeds('https://quefaire.paris.fr/rss/activites.xml');
        $this->extractFeeds('https://vl-media.fr/categories/sorties/feed/');
        $this->extractFeeds('https://www.urbanizer.fr/feed');

        $posts = $this->posts;

        foreach ($posts as $key => $value) {
            $sort[$key] = strtotime($value['pubDate']);
            $posts[$key]['pubDate'] = date('d-m-Y', strtotime($posts[$key]['pubDate']));
            $posts[$key]['description'] = mb_strimwidth( $posts[$key]['description'], 0, 300, "...");
        }

        try {
            array_multisort($sort, SORT_DESC, $posts);
        } catch (\Exception $e) {
            Session::flash('error', 'Pas de nouveau contenu.');
        }

        return view('communication.feeds', compact('posts'));
    }

    /**
     * Cette fonction retoune le groupe facebook.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function onGoToFB()
    {
        return redirect()->away('https://www.facebook.com/groups/1382341051775988/');
    }

    /**
     * Cette méthode va chercher les flux rss et en extrait les informations.
     * Elle effectue une requête et vérifie que le status est bien un code 200 et que le fichier xml est exploitable.
     * Si une erreur survient : l'url dont provient l'erreur est passée dans un message flash d'erreur.
     *
     * @param $source
     * @return bool|array
     */
    private function extractFeeds($source)
    {
        try {
            $headers = get_headers($source);
        } catch (\Exception $e) {
            $this->errorSources = $this->errorSources . '<br />' . $source;
            Session::flash('error', 'Le(s) lien(s) : <br /> ' . $this->errorSources . '<br /><br /> n\'a / n\'ont pas pu être exploité(s).');
            return false;
        }

        $response = substr($headers[0], 9, 3);
        if ($response != '200') {
            $this->errorSources = $this->errorSources . '<br />' . $source;
            Session::flash('error', 'Le(s) lien(s) : <br /> ' . $this->errorSources . '<br /><br /> n\'a / n\'ont pas pu être exploité(s).');
            return false;
        }

        try {
            $data = simplexml_load_string(file_get_contents($source));
        } catch (\Exception $e) {
            $this->errorSources = $this->errorSources . '<br />' . $source;
            Session::flash('error', 'Le(s) lien(s) : <br /> ' . $this->errorSources . '<br /><br /> n\'a / n\'ont pas pu être exploité(s).');
            return false;
        }

        if (count($data) == 0) {
            return false;
        }

        foreach ($data->channel->item as $item) {
            if (
                strpos($item->title, 'Restaurant ') !== false ||
                strpos($item->title, 'Restaurants ') !== false ||
                strpos($item->title, 'restaurant ') !== false ||
                strpos($item->title, 'restaurants ') !== false ||
                strpos($item->title, 'Bar ') !== false ||
                strpos($item->title, 'Bars ') !== false ||
                strpos($item->title, 'bar ') !== false ||
                strpos($item->title, 'bars ') !== false ||
                strpos($item->title, 'soirée ') !== false ||
                strpos($item->title, 'Soirée ') !== false ||
                strpos($item->title, 'soirées ') !== false ||
                strpos($item->title, 'Soirées ') !== false ||
                strpos($item->title, 'Café ') !== false ||
                strpos($item->title, 'café ') !== false ||
                strpos($item->title, 'brunch ') !== false ||
                strpos($item->title, 'Brunch ') !== false ||
                strpos($item->title, 'cocktail ') !== false ||
                strpos($item->title, 'Cocktail ') !== false ||
                strpos($item->title, 'cocktails ') !== false ||
                strpos($item->title, 'Cocktails ') !== false ||
                strpos($item->title, 'bière ') !== false ||
                strpos($item->title, 'Bière ') !== false ||
                strpos($item->title, 'bières ') !== false ||
                strpos($item->title, 'Bières ') !== false ||
                strpos($item->title, 'Vin ') !== false ||
                strpos($item->title, 'vin ') !== false ||
                strpos($item->title, 'Vins ') !== false ||
                strpos($item->title, 'vins ') !== false
            ) {
                array_push($this->posts, [
                    'title' => (string)strip_tags($item->title),
                    'link' => (string)strip_tags($item->link),
                    'pubDate' => (string)strip_tags($item->pubDate),
                    'description' => (string)strip_tags($item->description)
                ]);
            }
        }
    }

}
