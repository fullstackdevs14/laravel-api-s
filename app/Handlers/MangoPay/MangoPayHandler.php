<?php

namespace App\Handlers\MangoPay;

use App\Partner;
use App\OrderInfo;
use App\ApplicationUser;
use MangoPay\Money;
use MangoPay\PayIn;
use MangoPay\PayOut;
use MangoPay\Refund;
use MangoPay\Wallet;
use MangoPay\Address;
use MangoPay\UserLegal;
use MangoPay\UserNatural;
use MangoPay\MangoPayApi;
use MangoPay\BankAccount;
use MangoPay\LegalPersonType;
use MangoPay\CardRegistration;
use MangoPay\PayInPaymentType;
use MangoPay\PayInExecutionType;
use Illuminate\Support\Facades\Log;
use MangoPay\BankAccountDetailsIBAN;
use MangoPay\PayInPaymentDetailsCard;
use MangoPay\PayInExecutionDetailsDirect;
use MangoPay\PayOutPaymentDetailsBankWire;

use MangoPay\Libraries\Exception;
use MangoPay\Libraries\ResponseException;

/**
 * Gère les méthodes de la librairie MangoPay.
 * Tous les appels des méthodes qui suivent sont faits vers la base de données ManngoPay.
 *
 * Class MangoPayHandler
 * @package App\Handlers\MangoPay
 */
class MangoPayHandler
{
    /**
     * Obtient l'objet « PayIn » grâce à l'identifiant « payInId » de la table « orders_info ».
     * Retourne un objet même si l'identifiant est nul.
     *
     * @param MangoPayApi $mangoPayApi
     * @param OrderInfo $orderInfo
     * @return PayIn Get status of transaction even if payIn doesn't exist.
     */
    public function getPayIn
    (
        MangoPayApi $mangoPayApi,
        OrderInfo $orderInfo
    )
    {
        if ($orderInfo->payInId !== null) {
            return $mangoPayApi->PayIns->Get($orderInfo->payInId);
        } else {
            return $payIn = (object)array('Status' => 'NO TRANSACTION');
        }
    }

    /**
     * Créer un utilisateur.
     *
     * @param MangoPayApi $mangoPayApi
     * @param string $firstName
     * @param string $lastName
     * @param timestamp $birthday
     * @param countryIso $nationality
     * @param countryIso $countryOfResidence
     * @param string $email
     * @return UserLegal|UserNatural
     */
    public function createUser
    (
        MangoPayApi $mangoPayApi,
        $firstName,
        $lastName,
        $birthday,
        $nationality,
        $countryOfResidence,
        $email
    )
    {
        $mangoUser = new UserNatural();
        $mangoUser->PersonType = 'NATURAL';
        $mangoUser->FirstName = $firstName;
        $mangoUser->LastName = $lastName;
        $mangoUser->Birthday = $birthday;
        $mangoUser->Nationality = $nationality;
        $mangoUser->CountryOfResidence = $countryOfResidence;
        $mangoUser->Email = $email;

        $mangoUser = $mangoPayApi->Users->Create($mangoUser);

        return $mangoUser;
    }

    /**
     * Créer un utilisateur légal.
     *
     * @param MangoPayApi $mangoPayApi
     * @param string $companyName
     * @param string $email
     * @param string $LegalRepresentativeEmail
     * @param string $legalRepresentativeFirstName
     * @param string $legalRepresentativeLastName
     * @param timestamp $legalRepresentativeBirthday
     * @param countryIso $legalRepresentativeNationality
     * @param countryIso $legalRepresentativeCountryOfResidence
     * @param string $LegalRepresentativeAddress_addressLine1
     * @param string $LegalRepresentativeAddress_city
     * @param string $LegalRepresentativeAddress_region
     * @param string $LegalRepresentativeAddress_postalCode
     * @param string $LegalRepresentativeAddress_countryCode
     * @param string $HeadquartersAddress_addressLine1
     * @param string $HeadquartersAddress_city
     * @param string $HeadquartersAddress_region
     * @param string $HeadquartersAddress_postalCode
     * @param countryIso $HeadquartersAddress_countryCode
     * @return UserLegal
     */
    public function createLegalUser
    (
        MangoPayApi $mangoPayApi,
        $companyName,
        $email,
        $LegalRepresentativeEmail,
        $legalRepresentativeFirstName,
        $legalRepresentativeLastName,
        $legalRepresentativeBirthday,
        $legalRepresentativeNationality,
        $legalRepresentativeCountryOfResidence,
        $LegalRepresentativeAddress_addressLine1,
        $LegalRepresentativeAddress_city,
        $LegalRepresentativeAddress_region,
        $LegalRepresentativeAddress_postalCode,
        $LegalRepresentativeAddress_countryCode,
        $HeadquartersAddress_addressLine1,
        $HeadquartersAddress_city,
        $HeadquartersAddress_region,
        $HeadquartersAddress_postalCode,
        $HeadquartersAddress_countryCode
    )
    {
        $user = new UserLegal();
        $user->Name = $companyName;
        $user->Email = $email;
        $user->LegalPersonType = LegalPersonType::Business;
        $user->LegalRepresentativeEmail = $LegalRepresentativeEmail;
        $user->LegalRepresentativeFirstName = $legalRepresentativeFirstName;
        $user->LegalRepresentativeLastName = $legalRepresentativeLastName;
        $user->LegalRepresentativeBirthday = $legalRepresentativeBirthday;
        $user->LegalRepresentativeNationality = $legalRepresentativeNationality;
        $user->LegalRepresentativeCountryOfResidence = $legalRepresentativeCountryOfResidence;
        $user->LegalRepresentativeAddress = new Address();
        $user->LegalRepresentativeAddress->AddressLine1 = $LegalRepresentativeAddress_addressLine1;
        $user->LegalRepresentativeAddress->City = $LegalRepresentativeAddress_city;
        $user->LegalRepresentativeAddress->Region = $LegalRepresentativeAddress_region;
        $user->LegalRepresentativeAddress->PostalCode = $LegalRepresentativeAddress_postalCode;
        $user->LegalRepresentativeAddress->Country = $LegalRepresentativeAddress_countryCode;
        $user->HeadquartersAddress = new Address();
        $user->HeadquartersAddress->AddressLine1 = $HeadquartersAddress_addressLine1;
        $user->HeadquartersAddress->City = $HeadquartersAddress_city;
        $user->HeadquartersAddress->Region = $HeadquartersAddress_region;
        $user->HeadquartersAddress->PostalCode = $HeadquartersAddress_postalCode;
        $user->HeadquartersAddress->Country = $HeadquartersAddress_countryCode;

        $result = $mangoPayApi->Users->Create($user);

        return $result;
    }

    /**
     * Met à jour un utilisateur légal.
     *
     * @param MangoPayApi $mangoPayApi
     * @param int $mango_id
     * @param string $companyName
     * @param string $email
     * @param string $legalRepresentativeFirstName
     * @param string $legalRepresentativeLastName
     * @param timestamp $legalRepresentativeBirthday
     * @param countryIso $legalRepresentativeNationality
     * @param countryIso $legalRepresentativeCountryOfResidence
     * @return UserLegal|UserNatural
     */
    public function updateLegalUser
    (
        MangoPayApi $mangoPayApi,
        $mango_id,
        $companyName,
        $email,
        $legalRepresentativeFirstName,
        $legalRepresentativeLastName,
        $legalRepresentativeBirthday,
        $legalRepresentativeNationality = "FR",
        $legalRepresentativeCountryOfResidence = "FR"
    )
    {
        $userLegal = new UserLegal();
        $userLegal->Name = $companyName;
        $userLegal->LegalRepresentativeEmail = $email;
        $userLegal->LegalRepresentativeFirstName = $legalRepresentativeFirstName;
        $userLegal->LegalRepresentativeLastName = $legalRepresentativeLastName;
        $userLegal->LegalRepresentativeBirthday = $legalRepresentativeBirthday;
        $userLegal->LegalRepresentativeNationality = $legalRepresentativeNationality;
        $userLegal->LegalRepresentativeCountryOfResidence = $legalRepresentativeCountryOfResidence;
        $userLegal->LegalPersonType = "BUSINESS";
        $userLegal->Id = $mango_id;

        $result = $mangoPayApi->Users->Update($userLegal);

        return $result;
    }

    /**
     * Créé un porte-monnaie virtuel pour un utilisateur.
     *
     * @param MangoPayApi $mangoPayApi
     * @param int $user_Id
     * @param string $walletDescription
     * @param string $currency
     * @return Wallet
     */
    public function createWallet
    (
        MangoPayApi $mangoPayApi,
        $user_Id,
        $walletDescription,
        $currency
    )
    {
        $wallet = new Wallet();
        $wallet->Owners = [$user_Id];
        $wallet->Description = $walletDescription;
        $wallet->Currency = $currency;
        $result = $mangoPayApi->Wallets->Create($wallet);

        return $result;
    }

    /**
     * Créé un compte en banque pour un utilisateur
     *
     * @param MangoPayApi $mangoPayApi
     * @param int $mango_id
     * @param string $iban
     * @param string $bic
     * @param string $ownerName
     * @param string $address
     * @param string $city
     * @param string $country
     * @param string $postalCode
     * @return mixed
     */
    public function createBankAccountIBAN
    (
        MangoPayApi $mangoPayApi,
        $mango_id,
        $iban,
        $bic,
        $ownerName,
        $address,
        $city,
        $postalCode,
        $country = 'FR'
    )
    {
        try {

            $bankAccount = new BankAccount();
            $bankAccount->Type = "IBAN";
            $bankAccount->Details = new BankAccountDetailsIBAN();
            $bankAccount->Details->IBAN = $iban;
            $bankAccount->Details->BIC = $bic;
            $bankAccount->OwnerName = $ownerName;
            $bankAccount->OwnerAddress = new Address();
            $bankAccount->OwnerAddress->AddressLine1 = $address;
            $bankAccount->OwnerAddress->City = $city;
            $bankAccount->OwnerAddress->Country = $country;
            $bankAccount->OwnerAddress->PostalCode = $postalCode;

            $result = $mangoPayApi->Users->CreateBankAccount($mango_id, $bankAccount);

            return $result;
        } catch (ResponseException $e) {
            return ['error' => reset($e->GetErrorDetails()->Errors)];
        }

    }

    /**
     * Obtient l'identifiant du compte en banque à partir de la base de données MangPay  et la stocke dans la table « partners ».
     *
     * @param Partner $partner
     * @param int $bankAccount_id
     * @return Partner
     */
    public function setUsedBankInPartnerBdd
    (
        Partner $partner,
        $bankAccount_id
    )
    {
        $partner->mango_bank_id = $bankAccount_id;
        $partner->update();

        return $partner;
    }

    /**
     * Met à jour le compte en banque comme inactif dans la base de données MangoPay.
     * Attention : cette opération est irréversible.
     *
     * @param MangoPayApi $mangoPayApi
     * @param int $mango_id
     * @param int $bankAccount_id
     * @return BankAccount
     */
    public function deactivateBankAccount
    (
        MangoPayApi $mangoPayApi,
        $mango_id,
        $bankAccount_id
    )
    {
        $bankAccount = $mangoPayApi->Users->GetBankAccount($mango_id, $bankAccount_id);
        $bankAccount->Id = $bankAccount_id;
        $bankAccount->Active = false;
        $result = $mangoPayApi->Users->UpdateBankAccount($mango_id, $bankAccount);

        return $result;
    }

    /**
     * Première étape pour enregistrer une carte.
     * L'objet retourné est indispensable pour pouvoir poster les données de la carte vers le serveur tokenizer.
     * Voir: https://docs.mangopay.com/endpoints/v2.01/cards#e177_the-card-registration-object
     *
     * @param MangoPayApi $mangoPayApi
     * @param int $user_id
     * @param string $currency
     * @return CardRegistration
     */
    public function cardPreRegistration
    (
        MangoPayApi $mangoPayApi,
        $user_id,
        $currency
    )
    {
        $cardRegister = new CardRegistration();
        $cardRegister->UserId = $user_id;
        $cardRegister->Currency = $currency;

        $result = $mangoPayApi->CardRegistrations->Create($cardRegister);

        return $result;
    }

    /**
     * Renseigne la carte comme utilisée dans la table « application_users » à la colonne « mango_card_id ».
     *
     * @param ApplicationUser $applicationUser
     * @param int $mango_card_id
     * @return ApplicationUser
     */
    public function setUsedCardInApplicationBdd
    (
        ApplicationUser $applicationUser,
        $mango_card_id
    )
    {
        $applicationUser->mango_card_id = $mango_card_id;
        $applicationUser->update();

        return $applicationUser;
    }

    /**
     * Créer un objet « payIn » dans la base de données MangoPay, qui est retourné à l'API Application.
     * Le statut de cet objet est important. Si le statut est « SUCCESS », alors le paiement est valide. Sinon le paiement est invalide.
     *
     * Cette méthode est uniquement utilisée dans le contrôleur  « PartnerOrdersController », car le paiement n'est déclenché que quand le partenaire décide d'accepter la commande.
     *
     * Voir : App\Http\Controllers\API\Orders\PartnerOrdersController.
     *
     * @param MangoPayApi $mangoPayApi
     * @param ApplicationUser $applicationUser
     * @param Partner $partner
     * @param OrderInfo $orderInfo
     * @param int $amount
     * @return PayIn
     */
    public function payIn
    (
        MangoPayApi $mangoPayApi,
        ApplicationUser $applicationUser,
        Partner $partner,
        OrderInfo $orderInfo,
        $amount
    )
    {
        $wallets = $mangoPayApi->Users->GetWallets($partner->mango_id);

        $payIn = new PayIn();
        $payIn->CreditedWalletId = $wallets[0]->Id;
        $payIn->AuthorId = $applicationUser->mango_id;
        $payIn->Tag = $orderInfo->id;
        $payIn->PaymentType = PayInPaymentType::Card;
        $payIn->PaymentDetails = new PayInPaymentDetailsCard();
        $payIn->DebitedFunds = new Money();
        //TODO: allow to change currency.
        $payIn->DebitedFunds->Currency = "EUR";
        $payIn->DebitedFunds->Amount = $amount * 100;
        $payIn->Fees = new Money();
        //TODO: allow to change currency.
        $payIn->Fees->Currency = "EUR";
        $payIn->Fees->Amount = $amount * $partner->fees;
        $payIn->ExecutionType = PayInExecutionType::Direct;
        $payIn->ExecutionDetails = new PayInExecutionDetailsDirect();
        $payIn->ExecutionDetails->SecureModeReturnURL = 'https://www.sipperapp.com/after-3d-validation';
        $payIn->ExecutionDetails->CardId = $applicationUser->mango_card_id;

        $result = $mangoPayApi->PayIns->Create($payIn);

        $orderInfo->PayInId = $result->Id;
        $orderInfo->update();

        return $result;
    }

    /**
     * Identique à la méthode payIn.
     * Cette méthode est utilisée pour le paiement de l'utilisateur demandant le partage d'une addition quand l'autre utilisateur accepte.
     *
     * @param MangoPayApi $mangoPayApi
     * @param ApplicationUser $applicationUser
     * @param Partner $partner
     * @param OrderInfo $orderInfo
     * @param int $amount
     * @return PayIn
     */
    public function payInShared_1
    (
        MangoPayApi $mangoPayApi,
        ApplicationUser $applicationUser,
        Partner $partner,
        OrderInfo $orderInfo,
        $amount
    )
    {
        $wallets = $mangoPayApi->Users->GetWallets($partner->mango_id);

        $payIn = new PayIn();
        $payIn->CreditedWalletId = $wallets[0]->Id;
        $payIn->AuthorId = $applicationUser->mango_id;
        $payIn->Tag = $orderInfo->id;
        $payIn->PaymentType = PayInPaymentType::Card;
        $payIn->PaymentDetails = new PayInPaymentDetailsCard();
        $payIn->DebitedFunds = new Money();
        $payIn->DebitedFunds->Currency = "EUR";
        $payIn->DebitedFunds->Amount = $amount * 100 + 20;
        $payIn->Fees = new Money();
        $payIn->Fees->Currency = "EUR";
        $payIn->Fees->Amount = $amount * $partner->fees + 20;
        $payIn->ExecutionType = PayInExecutionType::Direct;
        $payIn->ExecutionDetails = new PayInExecutionDetailsDirect();
        $payIn->ExecutionDetails->SecureModeReturnURL = 'https://www.sipperapp.com/after-3d-validation';
        $payIn->ExecutionDetails->CardId = $applicationUser->mango_card_id;

        $result = $mangoPayApi->PayIns->Create($payIn);

        $orderInfo->PayInId = $result->Id;
        $orderInfo->update();

        return $result;
    }

    /**
     * Identique à la méthode payIn.
     * Cette méthode est utilisée pour le paiement de l'utilisateur acceptant le partage d'une addition.
     *
     * @param MangoPayApi $mangoPayApi
     * @param ApplicationUser $applicationUser
     * @param Partner $partner
     * @param OrderInfo $orderInfo
     * @param int $amount
     * @return PayIn
     */
    public function payInShared_2
    (
        MangoPayApi $mangoPayApi,
        ApplicationUser $applicationUser,
        Partner $partner,
        OrderInfo $orderInfo,
        $amount
    )
    {
        $wallets = $mangoPayApi->Users->GetWallets($partner->mango_id);

        $payIn = new PayIn();
        $payIn->CreditedWalletId = $wallets[0]->Id;
        $payIn->AuthorId = $applicationUser->mango_id;
        $payIn->Tag = $orderInfo->id;
        $payIn->PaymentType = PayInPaymentType::Card;
        $payIn->PaymentDetails = new PayInPaymentDetailsCard();
        $payIn->DebitedFunds = new Money();
        $payIn->DebitedFunds->Currency = "EUR";
        $payIn->DebitedFunds->Amount = $amount * 100 + 20;
        $payIn->Fees = new Money();
        $payIn->Fees->Currency = "EUR";
        $payIn->Fees->Amount = $amount * $partner->fees + 20;
        $payIn->ExecutionType = PayInExecutionType::Direct;
        $payIn->ExecutionDetails = new PayInExecutionDetailsDirect();
        $payIn->ExecutionDetails->SecureModeReturnURL = 'https://www.sipperapp.com/after-3d-validation';
        $payIn->ExecutionDetails->CardId = $applicationUser->mango_card_id;

        $result = $mangoPayApi->PayIns->Create($payIn);

        $orderInfo->payInId_share_bill = $result->Id;
        $orderInfo->update();

        return $result;
    }

    /**
     * Log les résultats dans le fichier suivant : storage/logs/mangoPay.log.
     *
     * @param $results
     */
    public function logResults
    (
        $results
    )
    {
        Log::useFiles(storage_path() . '/logs/mangoPay.log');
        Log::info([$results]);
    }

    /**
     * Rembourse le montant renseigné.
     * Des garde-fous sont mis en place à la fois sur l'API Application est sur l'API MangoPay.
     *
     * @param MangoPayApi $mangoPayApi
     * @param OrderInfo $orderInfo
     * @param ApplicationUser $applicationUser
     * @param int $amount
     * @return Refund
     */
    public function refund
    (
        MangoPayApi $mangoPayApi,
        OrderInfo $orderInfo,
        ApplicationUser $applicationUser,
        $amount
    )
    {
        $amount = intval($amount);

        $refund = new Refund();
        $refund->AuthorId = $applicationUser->mango_id;
        $refund->DebitedFunds = new Money();
        $refund->DebitedFunds->Currency = "EUR";
        $refund->DebitedFunds->Amount = $amount * 100 - $amount * 100 * $orderInfo->fees / 100;
        $refund->Fees = new Money();
        $refund->Fees->Currency = "EUR";
        $refund->Fees->Amount = -$amount * 100 * $orderInfo->fees / 100;

        $result = $mangoPayApi->PayIns->CreateRefund($orderInfo->payInId, $refund);

        return $result;
    }

    /**
     * Rembourse le montant renseigné pour une note partagée.
     * L'id de l'objet « payIn » doit être renseignée, car pour une note partagée il existe deux objets « payIn ».
     *
     * @param MangoPayApi $mangoPayApi
     * @param OrderInfo $orderInfo
     * @param int $payInId
     * @param ApplicationUser $applicationUser
     * @param int $amount
     * @return Refund
     */
    public function refundSharedBill
    (
        MangoPayApi $mangoPayApi,
        OrderInfo $orderInfo,
        $payInId,
        ApplicationUser $applicationUser,
        $amount
    )
    {
        $refund = new Refund();
        $refund->AuthorId = $applicationUser->mango_id;
        $refund->DebitedFunds = new Money();
        $refund->DebitedFunds->Currency = "EUR";
        $refund->DebitedFunds->Amount = $amount * 100 - $amount * 100 * $orderInfo->fees / 100;
        $refund->Fees = new Money();
        $refund->Fees->Currency = "EUR";
        $refund->Fees->Amount = -$amount * 100 * $orderInfo->fees / 100;

        $result = $mangoPayApi->PayIns->CreateRefund($payInId, $refund);

        return $result;
    }


    /**
     * Reverse le montant total du chiffre d'affaires fait par un partenaire sur son compte bancaire.
     *
     * @param MangoPayApi $mangoPayApi
     * @param Partner $partner
     * @param Wallet $wallet
     * @return bool|PayOut
     */
    public function payOut
    (
        MangoPayApi $mangoPayApi,
        Partner $partner,
        Wallet $wallet
    )
    {

        try {

            if ($wallet->Balance->Amount > 0) {
                $PayOut = new PayOut();
                $PayOut->AuthorId = $partner->mango_id;
                $PayOut->DebitedWalletID = $wallet->Id;
                $PayOut->DebitedFunds = new Money();
                $PayOut->DebitedFunds->Currency = "EUR";
                $PayOut->DebitedFunds->Amount = $wallet->Balance->Amount;
                $PayOut->Fees = new Money();
                $PayOut->Fees->Currency = "EUR";
                $PayOut->Fees->Amount = 0;
                $PayOut->PaymentType = "BANK_WIRE";
                $PayOut->MeanOfPaymentDetails = new PayOutPaymentDetailsBankWire();
                $PayOut->MeanOfPaymentDetails->BankAccountId = $partner->mango_bank_id;
                $result = $mangoPayApi->PayOuts->Create($PayOut);

                return $result;
            } else {
                return false;
            }

        } catch (ResponseException $e) {
            dd($e->GetCode(), $e->GetMessage(), $e->GetErrorDetails());

        } catch (Exception $e) {
            dd($e->GetMessage());

        }
    }

    /**
     * Cette méthode retourne l'objet Payout depuis l'API MangoPay.
     *
     * @param MangoPayApi $mangoPayApi
     * @param $payOut_id
     * @return PayOut
     */
    public function getPayOut
    (
        MangoPayApi $mangoPayApi,
        $payOut_id
    )
    {
        return $mangoPayApi->PayOuts->Get($payOut_id);
    }

}