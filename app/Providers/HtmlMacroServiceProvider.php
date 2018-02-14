<?php

namespace App\Providers;

use Collective\Html\FormBuilder;
use Collective\Html\HtmlBuilder;
use Illuminate\Support\ServiceProvider;

/**
 * Cette classe se charge de créer dynamiquement des morceaux de html.
 *
 * Class HtmlMacroServiceProvider
 * @package App\Providers
 */
class HtmlMacroServiceProvider extends ServiceProvider
{
    /**
     * Enregistre les services de l'application.
     *
     * @return void
     */
    public function register()
    {
        $this->registerFormControl();
        $this->registerFormFileInput();
        $this->registerFormSelectFromDB();
        $this->registerFormSelectFromDBSelected();
        $this->registerFormSelectFromArray();
        $this->registerFormSelectFromArraySelected();
        $this->registerFormSelect2Choices();
        $this->registerFormSelect2ChoicesSelected();
        $this->registerFormSearchBar();
        $this->registerFormSearchBarWithParam();
        $this->registerFormRadioYesOrNo();
        $this->registerHtmlListInfo();
        $this->registerHtmlListYesOrNo();
        $this->registerHtmlListStatus();
        $this->registerHtmlOrderStatus();
        $this->registerHtmlButtonBack();
        $this->registerHtmlRouteWithIcon();
        $this->registerHtmlRouteWithIconBlank();
    }

    /**
     * Ok.
     */
    private function registerFormControl()
    {
        FormBuilder::macro('Control', function ($type, $errors, $name, $placeholder, $label) {
            $value = \Request::old($name) ? \Request::old($name) : null;
            $attributes = ['class' => 'form-control', 'placeholder' => $placeholder];
            return sprintf('
                <div class="row">
                    <div class="col-sm-10 col-sm-offset-1">
				        <div class="form-group %s">
				        <label for="' . $name . '">' . $label . '</label>
					        %s
					        %s
				        </div>
				    </div>
				</div>',
                $errors->has($name) ? 'has-error' : '',
                call_user_func_array(['Form', $type], [$name, $value, $attributes]),
                $errors->first($name, '<small class="help-block" style="color:red">:message</small>')
            );
        });
    }

    /**
     * Request old impossible sur un fichier text.
     */
    private function registerFormFileInput()
    {
        FormBuilder::macro('FileInput', function ($name, $errors, $label) {
            $errors->has($name) ? $errClass = 'has-error' : $errClass = '';
            $errorMsg = $errors->first($name, '<small class="help-block" style="color:red">:message</small>');
            return
                '<div class="row">
                        <div class="col-sm-10 col-sm-offset-1">
                            <div class="form-group ' . $errClass . '">
                                <label for="' . $name . '">' . $label . '</label>
                                <div class="input-group"><label class="input-group-btn">
                                <span class="btn btn-default">Choisir&hellip;
                                <div style="display: none">
                                ' . FormBuilder::file($name) . '
                                </div>
                                </span>
                                </label>
                                    <input type="text" class="form-control" readonly>
                                </div>
                                ' . $errorMsg . '
                            </div>
                        </div>
                    </div>';
        });
    }

    /**
     * Ok.
     */
    private function registerFormSelectFromDB()
    {
        FormBuilder::macro('SelectFromDB', function ($name1, $name2, $errors, $collection, $keyOptionValue, $keyOptionLabel, $label) {
            $options = null;
            $errors->has($name1) ? $errClass = 'has-error' : $errClass = '';
            $errorMsg = $errors->first($name2, '<small class="help-block" style="color:red">:message</small>');
            foreach ($collection as $item) {
                if (\Request::old($name2) != $item->$keyOptionValue) {
                    $options = $options . '<option value="' . $item->$keyOptionValue . '">' . $item->$keyOptionLabel . '</option>';
                } else {
                    $options = $options . '<option value="' . $item->$keyOptionValue . '" selected>' . $item->$keyOptionLabel . '</option>';
                }
            }
            return
                '<div class="row">
                    <div class="col-sm-10 col-sm-offset-1">
                        <div class="form-group ' . $errClass . ' ">
                            <label for="' . $name1 . '">' . $label . '</label>
                                <select id=" ' . $name1 . ' " name=" ' . $name2 . '" class="form-control form-control-lg">
                                    <option disabled selected value> -- Choisir une option -- </option> ' .
                $options . '
                                </select>
                                ' . $errorMsg . '
                        </div>
                    </div>
                </div>';
        });
    }

    /**
     * A tester.
     */
    private function registerFormSelectFromDBSelected()
    {
        FormBuilder::macro('SelectFromDBSelected', function ($name1, $name2, $errors, $collection, $sentItem, $keyOptionValue, $keyItemValue, $keyItemLabel, $label) {
            $options = null;
            $errors->has($name1) ? $errClass = 'has-error' : $errClass = '';
            $errorMsg = $errors->first($name2, '<small class="help-block" style="color:red">:message</small>');

            $value = \Request::old($name2) ? \Request::old($name2) : null;

            if ($value === null) {
                foreach ($collection as $item) {
                    $used = null;
                    $options = $options . '<option value="' . $item->$keyOptionValue . '"';
                    if ($sentItem->$keyItemValue == (!$keyOptionValue ? $item : $item->$keyOptionValue)) {
                        $used = 1;
                        $options = $options . 'selected="selected"';
                    }
                    $options = $options . '>' . $item->$keyItemLabel . '</option>';
                    if ($used === null) {
                    }
                }
            } else {
                foreach ($collection as $item) {
                    if (\Request::old($name2) != $item->$keyOptionValue) {
                        $options = $options . '<option value="' . $item->$keyOptionValue . '">' . $item->$keyItemLabel . '</option>';
                    } else {
                        $options = $options . '<option value="' . $item->$keyOptionValue . '" selected>' . $item->$keyItemLabel . '</option>';
                    }
                }
            }
            return
                '<div class="row">
                    <div class="col-sm-10 col-sm-offset-1">
                        <div class="form-group ' . $errClass . ' ">
                            <label for="' . $name1 . '">' . $label . '</label>
                                <select id=" ' . $name1 . ' " name=" ' . $name2 . '" class="form-control form-control-lg">
                                    <option disabled selected value> -- Choisir une option -- </option> ' .
                $options . '
                                </select>
                                ' . $errorMsg . '
                        </div>
                    </div>
                </div>';
        });
    }

    /**
     *
     */
    public function registerFormSelectFromArray()
    {
        FormBuilder::macro('SelectFromArray', function ($name, $errors, $collection, $label) {
            $options = null;
            //foreach ($collection as $key => $value) {
            //    $options = $options . '<option value="' . $key . '">' . $value . '</option>';
            //}

            foreach ($collection as $key => $value) {
                if (\Request::old($name) != $key) {
                    $options = $options . '<option value="' . $key . '">' . $value . '</option>';
                } else {
                    $options = $options . '<option value="' . $key . '" selected>' . $value . '</option>';
                }
            }

            $errors->has($name) ? $errClass = 'has-error' : $errClass = '';
            $errorMsg = $errors->first($name, '<small class="help-block" style="color:red">:message</small>');
            return
                '<div class="row">
                    <div class="col-sm-10 col-sm-offset-1">
                        <div class="form-group ' . $errClass . '">
                            <label for="' . $name . '">' . $label . '</label>
                                <select id="' . $name . '" name="' . $name . '" class="form-control form-control-lg">
                                ' . $options . '
                                </select>
                                ' . $errorMsg . '
                        </div>
                    </div>
                </div>';

        });
    }

    /**
     *
     */
    public function registerFormSelectFromArraySelected()
    {
        FormBuilder::macro('SelectFromArraySelected', function ($name, $selectedValue, $errors, $collection, $label) {
            $options = null;
            foreach ($collection as $key => $value) {
                if ($selectedValue == $value) {
                    $options = $options . '<option value="' . $key . '" selected>' . $value . '</option>';
                } else {
                    $options = $options . '<option value="' . $key . '">' . $value . '</option>';
                }
            }
            $errors->has($name) ? $errClass = 'has-error' : $errClass = '';
            $errorMsg = $errors->first($name, '<small class="help-block" style="color:red">:message</small>');
            return
                '<div class="row">
                    <div class="col-sm-10 col-sm-offset-1">
                        <div class="form-group ' . $errClass . '">
                            <label for="' . $name . '">' . $label . '</label>
                                <select id="' . $name . '" name="' . $name . '" class="form-control form-control-lg">
                                <option disabled selected value> -- Choisir une option -- </option>
                                ' . $options . '
                                </select>
                                ' . $errorMsg . '
                        </div>
                    </div>
                </div>';

        });
    }


    /**
     *
     */
    private function registerFormSelect2Choices()
    {
        FormBuilder::macro('Select2Choices', function ($name, $errors, $labelTrue, $labelFalse, $label) {
            $errors->has($name) ? $errClass = 'has-error' : $errClass = '';
            $errorMsg = $errors->first($name, '<small class="help-block" style="color:red">:message</small>');
            return
                '<div class="row">
                    <div class="col-sm-10 col-sm-offset-1">
                        <div class="form-group ' . $errClass . '">
                            <label for="' . $name . '">' . $label . '</label>
                                <select id="' . $name . '" name="' . $name . '" class="form-control form-control-lg">
                                    <option disabled selected value> -- Choisir une option -- </option>
                                    <option value="1">' . $labelTrue . '</option>
                                    <option value="0">' . $labelFalse . '</option>
                                </select>
                                ' . $errorMsg . '
                        </div>
                    </div>
                </div>';
        });
    }

    /**
     * Ok.
     */
    private function registerFormSelect2ChoicesSelected()
    {
        FormBuilder::macro('Select2ChoicesSelected', function ($name, $errors, $item, $labelTrue, $labelFalse, $label) {
            $options = null;
            $errors->has($name) ? $errClass = 'has-error' : $errClass = '';
            $errorMsg = $errors->first($name, '<small class="help-block" style="color:red">:message</small>');

            $value = \Request::old($name) ? \Request::old($name) : null;

            if ($value === null) {
                if ($item->$name === 1 || $item->$name === '1' || $item->name === true) {
                    $options = '<option value="1" selected="selected">' . $labelTrue . '</option>
                            <option value="0">' . $labelFalse . '</option>';
                } else {
                    $options = '<option value="1">' . $labelTrue . '</option>
                <option value="0" selected="selected">' . $labelFalse . '</option>';
                }
            } else {
                if ($value === 1 || $value == '1') {
                    $options = '<option value="1" selected="selected">' . $labelTrue . '</option>
                            <option value="0">' . $labelFalse . '</option>';
                } else {
                    $options = '<option value="1">' . $labelTrue . '</option>
                <option value="0" selected="selected">' . $labelFalse . '</option>';
                }
            }

            return
                '<div class="row">
                    <div class="col-sm-10 col-sm-offset-1">
                        <div class="form-group ' . $errClass . '">
                            <label for="' . $name . '">' . $label . '</label>
                            <select id="' . $name . '" name="' . $name . '" class="form-control form-control-lg">' .
                $options
                . '</select> 
                            ' . $errorMsg . '
                        </div>
                    </div>
                </div>';

        });
    }

    /**
     *
     */
    private function registerFormSearchBar()
    {
        FormBuilder::macro('SearchBar', function ($name, $route, $placeholder) {
            return
                FormBuilder::open(['method' => 'GET', 'route' => $route, 'class' => 'form-horizontal', 'role' => 'search']) . '
                    <div class="input-group"> ' .
                FormBuilder::text($name, '', ['class' => 'form-control', 'placeholder' => $placeholder]) . '
                        <span class="input-group-btn"><button class="btn btn-default" type="submit">Chercher</button></span>
                    </div>' .
                FormBuilder::close() . '<br>';
        });
    }

    /**
     *
     */
    private function registerFormSearchBarWithParam()
    {
        FormBuilder::macro('SearchBarWithParam', function ($name, $route, $param, $placeholder) {
            return
                FormBuilder::open(['method' => 'GET', 'route' => [$route, $param], 'class' => 'form-horizontal', 'role' => 'search']) . '
                    <div class="input-group"> ' .
                FormBuilder::text($name, '', ['class' => 'form-control', 'placeholder' => $placeholder]) . '
                        <span class="input-group-btn"><button class="btn btn-default" type="submit">Chercher</button></span>
                    </div>' .
                FormBuilder::close() . '<br>';
        });
    }

    /**
     *
     */
    private function registerFormRadioYesOrNo()
    {
        FormBuilder::macro('RadioYesOrNo', function ($errors, $name, $label) {
            $errors->has($name) ? $errClass = 'has-error' : $errClass = '';
            return
                '<div class="row">
                    <div class="col-sm-10 col-sm-offset-1">
                           <div class="form-group ' . $errClass . '">
                            <label for="' . $name . '">' . $label . '</label>
                               <span style="padding-left: 20px; padding-right: 10px">Oui</span>' . FormBuilder::radio($name, 1, false, ['class' => 'form-check-input']) . '
                               <span style="padding-left: 20px; padding-right: 10px">Non</span>' . FormBuilder::radio($name, 0, true, ['class' => 'form-check-input']) .
                $errors->first($name, '<small class="help-block" style="color:red">:message</small>') . '
                           </div>
                    </div>
                </div>';
        });
    }

    /**
     *
     */
    private function registerHtmlListInfo()
    {
        HtmlBuilder::macro('ListInfo', function ($name, $info) {
            return '<p>' . $name . ' : ' . $info . '</p>';
        });
    }

    /**
     *
     */
    private function registerHtmlListYesOrNo()
    {
        HtmlBuilder::macro('ListYesOrNo', function ($name, $status) {
            if ($name == 'Incident' || $name == 'incident' || $name == 'traité' || $name == 'Traité') {
                if ($status === 1 || $status === '1' || $status === true) {
                    return '<p>' . $name . ' : <span style="color: red">Oui</span></p>';
                } elseif ($status === 0 || $status === '0' || $status === false) {
                    return '<p>' . $name . ' : <span style="color: blue">Non</span></p>';
                } else {
                    return '<p>' . $name . ' : <span style="color: red">Urgent</span></p>';
                }
            } else {
                if ($status === 1 || $status === '1' || $status === true) {
                    return '<p>' . $name . ' : <span style="color: green ">Oui</span></p>';
                } elseif ($status === 0 || $status === '0' || $status === false) {
                    return '<p>' . $name . ' : <span style="color: red">Non</span></p>';
                } else {
                    return '<p>' . $name . ' : <span style="color: blue"> En cours</span></p>';
                }
            }
        });
    }

    /**
     *
     */
    private function registerHtmlListStatus()
    {
        HtmlBuilder::macro('ListStatus', function ($status) {
            if ($status === 1 || $status === '1') {
                return '<p>Status : <span style="color: green">Acceptée</span>';
            } elseif ($status === 0 || $status === '0') {
                return '<p>Status : <span style="color: red">Déclinée</span>';
            } else {
                return '<p>Status : <span style="color: blue">En cours</span>';
            }
        });
    }

    /**
     *
     */
    private function registerHtmlOrderStatus()
    {
        HtmlBuilder::macro('OrderStatus', function ($order) {
            if ($order->delivered == '1' && $order->accepted == '1' && $order->incident == '0') {
                return '<span style="color: green">Délivrée</span>';
            } elseif ($order->accepted == '1' && $order->delivered == '0' && $order->incident == '0') {
                return '<span style="color: green;">Acceptée</span>';
            } elseif ($order->accepted == '0' && $order->delivered == '0' && $order->incident == '0') {
                return '<span style="color: red">Déclinée</span>';
            } elseif ($order->accepted == '1' && $order->delivered == '0' && $order->incident == '1') {
                return '<span style="color: red">Incident avant livraison</span>';
            } elseif ($order->accepted == '1' && $order->delivered == '1' && $order->incident == '1') {
                return '<span style="color: red">Incident après livraison</span>';
            } elseif ($order->accepted == null && $order->incident == '0' && $order->incident == '0') {
                return '<span style="color: blue;">En attente</span>';
            }
        });
    }

    /**
     *
     */
    private function registerHtmlButtonBack()
    {
        HtmlBuilder::macro('BackButton', function () {
            return '<a href="javascript:history.back()" class="btn btn-default">
                        <span class="glyphicon glyphicon-circle-arrow-left"></span> Retour
                    </a>';
        });
    }

    /**
     *
     */
    private function registerHtmlRouteWithIcon()
    {
        HtmlBuilder::macro('RouteWithIcon', function ($route, $label, $var, $class, $glyphicon) {
            return '<a href="' . route($route, $var) . '" class="btn ' . $class . '">
                        <span class="glyphicon glyphicon-' . $glyphicon . '"></span><span> ' . $label . '</span>
                    </a>';
        });
    }

    /**
     *
     */
    private function registerHtmlRouteWithIconBlank()
    {
        HtmlBuilder::macro('RouteWithIconBlank', function ($route, $label, $var, $class, $glyphicon) {
            return '<a href="' . route($route, $var) . '" class="btn ' . $class . '" target="_blank">
                        <span class="glyphicon glyphicon-' . $glyphicon . '"></span><span> ' . $label . '</span>
                    </a>';
        });
    }

}
