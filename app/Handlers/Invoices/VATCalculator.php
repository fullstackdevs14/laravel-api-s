<?php

namespace App\Handlers\Invoices;

class VATCalculator
{
    static public function get_ht_amount_from_ttc_and_tax($TTCAmount, $TaxRate)
    {
        //[Montant HT] = [Montant TTC] / (1 + ([Taux TVA] / 100))
        $HTAmount = $TTCAmount / (1 + ($TaxRate / 100));
        return $HTAmount;
    }

    static public function get_ttc__amount_from_ht_and_tax($HTAmount, $TaxRate)
    {
        //[Montant TTC] = [Montant HT] x (1 + ([Taux TVA] / 100))
        $TTCAmount = $HTAmount * (1 + ($TaxRate / 100));
        return $TTCAmount;
    }

    static public function get_vat_amount_from_ttc_and_tax($TTCPrice, $TaxRate)
    {
        //$TaxAmount = VATCalculator::get_ht_amount_from_ttc_and_tax($TTCPrice, $TaxRate) * (1 + ($TaxRate / 100));
        //$TaxAmount = $TTCPrice - $HTAmount;
        $TaxAmount = $TTCPrice - VATCalculator::get_ht_amount_from_ttc_and_tax($TTCPrice, $TaxRate);
        return $TaxAmount;
    }

    static public function get_vat_amount_from_ht_amount_and_vat($HTAmount, $TaxRate)
    {
        //[Montant TVA] = [Montant HT] x ([Taux TVA] / 100)
        $TaxAmount = $HTAmount * ($TaxRate / 100);
        return $TaxAmount;
    }

}