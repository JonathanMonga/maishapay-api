<?php

/**
 * Created by PhpStorm.
 * User: davidkazad
 * Date: 23/11/2018
 * Time: 13:26
 */

/*
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
*/

require_once 'engine.php';
require_once 'Mailer.php';
require_once 'SmsGateway.php';

R::setup('mysql:host=localhost;dbname=cp973977_maishapay-api', 'root', '');
//R::setup('mysql:host=localhost;dbname=cp973977_maishapay-api','cp973977','Landry@22');
//R::setup('mysql:host=localhost;dbname=cp973977_test-maishapay','cp973977','Landry@22');

class Transaction
{
    private $header;
    private $result;

    private $telephone;
    private $pin;
    private $expediteur;
    private $destinateur;
    private $montant;
    private $monnaie;
    private $nom;
    private $prenom;
    private $ville;
    private $adresse;
    private $email;
    private $devise;
    private $code_secret;
    private $date_cloture;
    private $beneficiaire;
    private $tel_agent;
    private $mt;
    private $code_pin;
    private $subject;
    private $body;
    private $type_epargne;

    // payment process
    private $client_api_key;
    private $payment_amount;
    private $payment_devise;
    private $payment_description;

    private $page_callback_success;
    private $page_callback_failure;
    private $page_callback_cancel;
    private $logo_url;

    private $payment_token;

    //create_merchant
    private $merchant_name;
    private $merchant_phone;
    private $project_name;
    private $merchant_email;
    private $project_type;
    private $project_description;
    private $project_logo;
    private $project_redirect_url;
    private $project_callback_url;


    private function Encapsulate($field)
    {

        if (isset($_POST[$field])) {

            return $this->format_text($_POST[$field]);
        } else {

            //echo $_POST[$field];
            echo json_encode(array('resultat' => 0, 'message' => 'Maishapay Encapsulation Fields Error! : ' . $field . ' not found exception'));
            exit(0);
        }
    }

    /**
     * @return mixed
     */
    public function getTelephone()
    {
        return $this->telephone = $this->Encapsulate('telephone');
    }

    /**
     * @return mixed
     */
    public function getPin()
    {
        return $this->pin = $this->Encapsulate('pin');
    }

    /**
     * @return mixed
     */
    public function getExpediteur()
    {
        return $this->expediteur = $this->Encapsulate('expeditaire');
    }

    /**
     * @return mixed
     */
    public function getDestinateur()
    {
        return $this->destinateur = $this->Encapsulate('destinataire');
    }

    /**
     * @return mixed
     */
    public function getMontant()
    {
        return $this->montant = $this->Encapsulate('montant');
    }

    /**
     * @return mixed
     */
    public function getMonnaie()
    {
        return $this->monnaie = $this->Encapsulate('monnaie');
    }

    /**
     * @return mixed
     */
    public function getNom()
    {
        return $this->nom = $this->Encapsulate('nom');
    }

    /**
     * @return mixed
     */
    public function getPrenom()
    {
        return $this->prenom = $this->Encapsulate('prenom');
    }

    /**
     * @return mixed
     */
    public function getVille()
    {
        return $this->ville = $this->Encapsulate('ville');
    }

    /**
     * @return mixed
     */
    public function getAdresse()
    {
        return $this->adresse = $this->Encapsulate('adresse');
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email = $this->Encapsulate('email');
    }

    /**
     * @return mixed
     */
    public function getCodeSecret()
    {
        return $this->code_secret = $this->Encapsulate('code_secret');
    }

    /**
     * @return mixed
     */
    public function getDevise()
    {
        return $this->devise = $this->Encapsulate('devise');
    }

    /**
     * @return mixed
     */
    public function getDateCloture()
    {
        return $this->date_cloture = $this->Encapsulate('date_cloture');
    }

    /**
     * @return mixed
     */
    public function getBeneficiaire()
    {
        return $this->beneficiaire = $this->Encapsulate('beneficiaire');
    }

    /**
     * @return mixed
     */
    public function getTelAgent()
    {
        return $this->tel_agent = $this->Encapsulate('tel_agent');
    }

    /**
     * @return mixed
     */
    public function getMt()
    {
        return $this->mt = $this->Encapsulate('mt');
    }

    /**
     * @return mixed
     */
    public function getCodePin()
    {
        return $this->code_pin = $this->Encapsulate('code_pin');
    }

    /**
     * @return mixed
     */
    public function getSubject()
    {
        return $this->subject = $this->Encapsulate('subject');
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body = $this->Encapsulate('body');
    }

    /**
     * @return mixed
     */
    public function getClientApiKey()
    {
        return $this->client_api_key = $this->Encapsulate('client_api_key');
    }

    /**
     * @return mixed
     */
    public function getPaymentAmount()
    {
        return $this->payment_amount = $this->Encapsulate('payment_amount');
    }

    /**
     * @return mixed
     */
    public function getPaymentDevise()
    {
        return $this->payment_devise = $this->Encapsulate('payment_devise');
    }

    /**
     * @return mixed
     */
    public function getPaymentDescription()
    {
        return $this->payment_description = $this->Encapsulate('payment_description');
    }

    /**
     * @return mixed
     */
    public function getPageCallbackSuccess()
    {
        return $this->page_callback_success = $this->Encapsulate('page_callback_success');
    }

    /**
     * @return mixed
     */
    public function getPageCallbackFailure()
    {
        return $this->page_callback_failure = $this->Encapsulate('page_callback_failure');
    }

    /**
     * @return mixed
     */
    public function getPageCallbackCancel()
    {
        return $this->page_callback_cancel = $this->Encapsulate('page_callback_cancel');
    }

    /**
     * @return mixed
     */
    public function getLogoUrl()
    {
        return $this->logo_url = $this->Encapsulate('logo_url');
    }

    /**
     * @return mixed
     */
    public function getPaymentToken()
    {
        return $this->payment_token = $this->Encapsulate('token');
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name = $this->Encapsulate('name');
    }

    /**
     * @return mixed
     */
    public function getProjectName()
    {
        return $this->project_name = $this->Encapsulate('project_name');
    }

    /**
     * @return mixed
     */
    public function getMerchantEmail()
    {
        return $this->merchant_email = $this->Encapsulate('merchant_email');
    }


    /**
     * @return mixed
     */
    public function getProjectType()
    {
        return $this->project_type = $this->Encapsulate('project_type');
    }

    /**
     * @return mixed
     */
    public function getProjectDescription()
    {
        return $this->project_description = $this->Encapsulate('project_description');
    }

    /**
     * @return mixed
     */
    public function getProjectLogo()
    {
        return $this->project_logo = $this->Encapsulate('project_logo');
    }

    /**
     * @return mixed
     */
    public function getProjectRedirectUrl()
    {
        return $this->project_redirect_url = $this->Encapsulate('project_redirect_url');
    }

    /**
     * @return mixed
     */
    public function getProjectCallbackUrl()
    {
        return $this->project_callback_url = $this->Encapsulate('project_callback_url');
    }

    /**
     * @return mixed
     */
    public function getMerchantName()
    {
        return $this->merchant_name = $this->Encapsulate('merchant_name');
    }

    /**
     * @return mixed
     */
    public function getMerchantPhone()
    {
        return $this->merchant_phone = $this->Encapsulate('merchant_phone');
    }

    /**
     * @return mixed
     */
    public function getTypeEpargne()
    {
        return $this->type_epargne= $this->Encapsulate('type_epargne');
    }




    private function format_text($text)
    {
        return htmlentities(utf8_decode(htmlspecialchars($text)));
    }

    /**
     * Transaction constructor.
     * @param string $header
     */
    public function __construct($header = '')
    {
        $this->header = $this->Encapsulate('ent');

        $this->result = array('resultat' => 0, 'message' => 'Maishapay warning: data not found exception');

        switch ($this->header) {
            case 'solde' :
                $this->solde($this->getTelephone());
                $this->result();
                break;
            case 'solde_epargne_perso' :
                $this->solde_epargne_perso($this->getTelephone());
                $this->result();
                break;
            case 'profil':
                $this->profile($this->getTelephone());
                $this->result();
                break;
            case 'login':
                $this->login($this->getTelephone(), $this->getPin());
                $this->result();
                break;
            case 'inscription':
                $this->insccription($this->getTelephone(), $this->getNom(), $this->getPrenom(), $this->getEmail(), $this->getAdresse(), $this->getVille(), $this->getCodePin());
                $this->result();
                break;

            case 'creation_compte_epargne_perso':
                $this->creation_compte_epargne_perso($this->getTelephone(), $this->getDevise(), $this->getDateCloture(), $this->getCodeSecret());
                $this->result();
                break;
            case 'creation_compte_epargne_avenir':
                $this->creation_compte_avenir($this->getTelephone(), $this->getDevise(), $this->getDateCloture(), $this->getBeneficiaire(), $this->getCodeSecret());
                $this->result();
                break;
            case 'transfert-compte':
                $this->transfert_compte($this->getExpediteur(), $this->getDestinateur(), $this->getMontant(), $this->getMonnaie());
                $this->result();
                break;
            case 'transfert-compte-confirmation':
                $this->transfert_compte_confirmation($this->getExpediteur(), $this->getDestinateur(), $this->getMontant(), $this->getMonnaie(), $this->getPin());
                $this->result();
                break;

            case 'retrait':
                $this->transfert_compte($this->getExpediteur(), $this->getDestinateur(), $this->getMontant(), $this->getMonnaie());
                $this->result();
                break;
            case 'confirmation-retrait':
                $this->transfert_compte_confirmation($this->getExpediteur(), $this->getDestinateur(), $this->getMontant(), $this->getMonnaie(), $this->getPin());
                $this->result();
                break;

            case 'rapport':
                $this->rapport($this->getTelephone());
                $this->result();
                break;
            case 'upd_profil':
                $this->update_profile($this->getTelephone(), $this->getNom(), $this->getPrenom(), $this->getEmail(), $this->getAdresse(), $this->getVille(), $this->getPin());
                $this->result();
                break;
            case 'taux':
                $this->taux();
                $this->result();
                return;
                break;
            case 'conversion_monnaie':
                $this->conversion($this->getMontant(), $this->getMonnaie());
                $this->result();
                break;
            case 'nous_contacter':
                $this->contact($this->getSubject(), $this->getBody(), $this->getEmail());
                $this->result();
                break;
            case 'pin_perdu':
                $this->pin_perdu($this->getTelephone(), $this->getEmail());
                $this->result();
                break;
            case 'request_payment':
                $this->request_payment($this->getClientApiKey(), $this->getPaymentAmount(), $this->getPaymentDevise());
                $this->result();
                break;
                
            case 'web_request_payment':
                $this->request_payment($this->getClientApiKey(), $this->getPaymentAmount(), $this->getPaymentDevise());
                //$this->result();
                break;

            case 'request_completed':
                $this->request_completed($this->getClientApiKey(), $this->getPaymentToken());
                $this->result();
                break;

            case 'attempt_payment':
                $this->attempt_payment($this->getClientApiKey(), $this->getPaymentToken());
                $this->result();
                break;
            case 'confirm_payment':
                $this->confirm_payment($this->getClientApiKey(), $this->getPaymentToken(), $this->getExpediteur(), $this->getDestinateur(), $this->getMontant(), $this->getMonnaie(), $this->getPin());
                $this->result();
                break;

            case 'web_attempt_payment':
                $this->attempt_payment($this->getClientApiKey(), $this->getPaymentToken());
                $this->result();
                break;
            case 'web_confirm_payment':
                $this->confirm_payment($this->getClientApiKey(), $this->getPaymentToken(), $this->getExpediteur(), $this->getDestinateur(), $this->getMontant(), $this->getMonnaie(), $this->getPin());
                $this->result();
                break;

            case 'create_project':
                $this->create_project($this->getMerchantName(), $this->getMerchantPhone(), $this->getMerchantEmail(), $this->getProjectName(), $this->getProjectType(), $this->getProjectDescription(), $this->getProjectLogo(), $this->getProjectRedirectUrl(), $this->getProjectCallbackUrl());
                break;
            case 'create_merchant':
                $this->create_merchant($this->getTelephone(), $this->getNom(), $this->getPrenom(), $this->getEmail(), $this->getAdresse(), $this->getVille(), $this->getCodePin());
                $this->result();
                break;

            case 'confirmation_transfert_epargne':
                $this->confirmation_transfert_epargne($this->getTelephone(), $this->getTypeEpargne(), $this->getMontant(),$this->getMonnaie(),'');
                $this->result();
                break;

            case 'transfert_epargne':
                $this->transfert_epargne($this->getTelephone(), $this->getTypeEpargne(), $this->getMontant(),$this->getMonnaie());
                $this->result();
                break;
            case 'message':
                $test = false;
                new SmsGateway('android-client', $test);
                break;
            case 'depot':
                $this->depot($this->getTelephone(), $this->getMontant(),$this->getMonnaie());
                $this->result();
                break;
            default :
                $this->result;
        }
    }

    public function result()
    {
        return $this->result;
    }

    public function data()
    {
        return $this->result;
    }
    public function jsonData()
    {
        return json_encode($this->result);
    }
    public function stringData()
    {
        echo json_encode($this->result);
    }

    public function solde($telephone)
    {
        $solde = R::findOne('solde', 'telephone=?', [$telephone]);

        $this->result = array('resultat' => 0, 'Ce numero n\'exist pas');

        if ($solde) {

            $this->result = array('resultat' => 1, "FC" => $solde['fc'], "USD" => $solde['usd']);
        }

        return $this->result;

    }

    public function solde_epargne_perso($telephone)
    {
        $epargne = R::findOne('epargne1', 'telephone=?', [$telephone]);
        $this->result = array('resultat' => 0);

        if ($epargne)

            $this->result = array("resultat" => 1, "FC" => $epargne['fc'], "USD" => $epargne['usd']);

        return $this->result;
    }

    public function profile($telephone)
    {

        $profile = R::findOne('user', 'telephone=?', [$telephone]);
        $this->result = array('nbresultat' => 0);
        if ($profile) {

            $this->result = array("nbresultat" => 1, "nom" => $profile['name'], "prenom" => $profile['firstname'], "email" => $profile['email'], "adresse" => $profile['adresse'], "ville" => $profile['ville']);
        }

        return $this->result;

        // profile

        /**/
    }

    public function login($telephone, $pin)
    {

        $user = R::findOne('user', 'telephone=?', [$telephone]);

        if ($user)
            if (password_verify($pin, $user['password']))

                return $this->result = array("resultat" => 1, "nom" => $user['name'], "prenom" => $user['firstname'], "telephone" => $user['telephone'], "email" => $user['email'], "adresse" => $user['adresse'], "ville" => $user['ville']);

        return $this->result = array('resultat' => 0, 'message' => "Nom d'utilisateur ou mot de passe incorrecte");
    }

    public function insccription($telephone, $nom, $prenom, $email, $adresse, $ville, $password, $usertype = 'A')
    {
        $this->result = array('resultat' => 0);

        if ($this->checkTelephone($telephone))
            return $this->result = array('resultat' => 2, 'message' => 'Numero de telephone déjà utiliser');
        if ($this->checkEmail($email))
            return $this->result = array('resultat' => 3, 'message' => 'Addresse email déjà utiliser');

        $user = R::dispense('user');
        $user->current_date = new DateTime();
        $user->name = $nom;
        $user->firstname = $prenom;
        $user->telephone = $telephone;
        $user->email = $email;
        $user->adresse = $adresse;
        $user->ville = $ville;
        $user->password = password_hash($password, PASSWORD_DEFAULT);
        $user->usertype = $usertype;
        $user->status = 'A';

        $id = R::store($user);

        if ($id) {

            $solde = R::dispense('solde');
            $solde->telephone = $telephone;
            $solde->usd = 0;
            $solde->fc = 500;
            R::store($solde);
			
			//to be deleted
			$journal = R::dispense('journal');
        $journal->expediteur = '2223';
        $journal->destinateur = $telephone;
        $journal->montant = 500;
        $journal->monnaie = 'FC';
        $journal->type_journal = 1;
        $journal->status = 'C';
		R::store($journal);

            return $this->result = array("resultat" => 1, "nom" => $nom, "prenom" => $prenom, "ville" => $ville, "telephone" => $telephone, 'message' => 'inscription effectueée avec succès!');

			
        }

        return $this->result;
    }

    private function checkEmail($email, $type = 'user')
    {
        return $user = R::count($type, 'email=?', [$email]);
    }

    private function checkTelephone($telephone, $type = 'user')
    {
        return $user = R::count($type, 'telephone=?', [$telephone]);
    }

    private function checkUser($telephone)
    {
        $this->result = R::findOne('user', 'telephone=?', [$telephone]);

        if ($this->result) {
            $this->result['resultat'] = 1;
            return $this->result;
        } else
            return $this->result = array('resultat' => 0, 'message' => 'User not found');
    }

    private function checkMerchant($telephone)
    {
        $this->result = $this->checkUser($telephone);

        if ($this->result['resultat'] == 1) {

            if ($this->result['usertype'] != 'merchant')
                $this->result['resultat'] = 2;

        }

        return $this->result;
    }

    public function checkSolde($telephone, $montant, $monnaie)
    {
        if ($monnaie == 'FC')

            return $this->result = R::count('solde', 'telephone=? AND fc > ?', [$telephone, $montant + $this->maishapay_commission($montant, 'FC')]);

        else if ($monnaie == 'USD')

            return $this->result = R::count('solde', 'telephone=? AND usd > ?', [$telephone, $montant + $this->maishapay_commission($montant, 'USD')]);

    }


    public function creation_compte_epargne_perso($telephone, $devise, $end_date, $code_secret)
    {

        if ($this->checkTelephone($telephone, 'epargne1'))
            //return $this->result = array('resultat' => 0, 'message' => 'Vous avez déjà un compte epargne personnel');
            return $this->result = 0;

        $epargne = R::dispense('epargne1');
        $epargne->telephone = $telephone;
        $epargne->password = sha1($code_secret);
        $epargne->end_date = $end_date;
        $epargne->usd = 0.0;
        $epargne->fc = 0.0;
        $epargne->status = 'A';

        if (R::store($epargne)) {
            return $this->result = 1;
        }

        return $this->result = 0;
    }

    public function creation_compte_avenir($telephone, $devise, $end_date, $beneficiaire, $code_secret)
    {

        if ($this->checkTelephone($telephone, 'epargne2'))
            //return $this->result = array('resultat' => 0, 'message' => 'Vous avez déjà un compte epargne avenir');
            return $this->result = 0;

        $epargne = R::dispense('epargne2');
        $epargne->telephone = $telephone;
        $epargne->password = $code_secret;
        $epargne->end_date = $end_date;
        $epargne->usd = 0.0;
        $epargne->fc = 0.0;
        $epargne->status = 'A';

        if (R::store($epargne))
            return $this->result = 1;

        return $this->result = 0;
    }

    public function transfert_compte($expediteur, $destinateur, $montant, $monnaie)
    {

        //if ($destinateur == $expediteur)
        //return $this->result = array('resultat' => 0, 'message' => 'Vous ne pouvez pas transferer l\'argent a vous meme');

        if ($user = $this->checkUser($destinateur)) {

            if (!$this->checkTelephone($expediteur))
                return $this->result = array('resultat' => 0, 'message' => "Numero de l' expediteur non trouvé");

            if (!$this->checkTelephone($destinateur))

                return $this->result = array('resultat' => 0, 'message' => "Numero de destinateur non trouvé");

            if ($this->checkSolde($expediteur, $montant, $monnaie))

                return $this->result = array("resultat" => 1, "nom" => $user['name'], "prenom" => $user['firstname'], 'message' => 'Vous etes sur le point de transferer ' . $montant . ' ' . $monnaie . ' à ' . $user['firstname'] . ' ' . $user['name']);
            else

                return $this->result = array("resultat" => 2, 'message' => 'solde insufissant');

        } else {

            return $this->result = array('resultat' => 0, 'message' => 'Numero du destinateur non trouvé');
        }
    }

    private function maishapay_commission($montant, $monnaie)
    {
        if ($monnaie == 'USD') {
            if ($montant >= 10) {
                return 1.00;
            } else {
                return 0.500;
            }
        } else if ($monnaie == 'FC') {
            if ($montant >= 10000) {
                return 1000;
            } else {
                return 500;
            }
        }
    }

    public function transfert_compte_confirmation($expediteur, $destinateur, $montant, $monnaie, $pin)
    {

        if ($this->login($expediteur, $pin)['resultat'] == 1) {

            if ($monnaie == 'USD')

                $this->updateSoldeUSD($destinateur, $expediteur, $montant);

            else if ($monnaie == 'FC')

                $this->updateSoldeFC($destinateur, $expediteur, $montant);
        }

        return $this->result;
    }

    private function updateSoldeUSD($destinateur, $expediteur, $montant)
    {
        if ($this->transfert_compte($expediteur, $destinateur, $montant, 'USD')['resultat'] == 1) {

            $solde_expediteur = R::findOne('solde', 'telephone=?', [$expediteur]);
            $solde_destinateur = R::findOne('solde', 'telephone=?', [$destinateur]);

            $updateSoldeExpediteur = R::load('solde', $solde_expediteur->getID());
            $updateSoldeExpediteur->usd = $solde_expediteur['usd'] - $montant - $this->maishapay_commission($montant, 'USD'); // - commission

            // update solde
            $updateSoldeDestinateur = R::load('solde', $solde_destinateur->getID());
            $updateSoldeDestinateur->usd = $solde_destinateur['usd'] + $montant;

            if (R::store($updateSoldeExpediteur))
                if (R::store($updateSoldeDestinateur)) {

                    // journalisation
                    $this->journalisation($expediteur, $destinateur, $montant, 'USD');
                    return $this->result = array('resultat' => 1, 'message' => 'transfert effectue avec success');
                }
            return $this->result = array('resultat' => 2, 'message' => 'Oparation failed');

        }
    }

    private function updateSoldeFC($destinateur, $expediteur, $montant)
    {
        if ($this->transfert_compte($expediteur, $destinateur, $montant, 'FC')['resultat'] == 1) {

            $solde_expediteur = R::findOne('solde', 'telephone=?', [$expediteur]);
            $solde_destinateur = R::findOne('solde', 'telephone=?', [$destinateur]);

            $updateSoldeExpediteur = R::load('solde', $solde_expediteur->getID());
            $updateSoldeExpediteur->fc = $solde_expediteur['fc'] - $montant - $this->maishapay_commission($montant, 'FC'); // - commission

            // update solde fc
            $updateSoldeDestinateur = R::load('solde', $solde_destinateur->getID());
            $updateSoldeDestinateur->fc = $solde_destinateur['fc'] + $montant;

            if (R::store($updateSoldeExpediteur))
                if (R::store($updateSoldeDestinateur)) {
                    $this->journalisation($expediteur, $destinateur, $montant, 'FC');
                    return $this->result = array('resultat' => 1, 'message' => 'transfert effectue avec success');
                }
            return $this->result = array('resultat' => 2, 'message' => 'Oparation failed');

        }
    }

    private function journalisation($expediteur, $destinateur, $montant, $monnaie)
    {
        $journal = R::dispense('journal');
        $journal->expediteur = $expediteur;
        $journal->destinateur = $destinateur;
        $journal->montant = $montant;
        $journal->monnaie = $monnaie;
        $journal->type_journal = 1;
        $journal->status = 'C';


        $cout = R::dispense('couttransaction');
        $cout->montant = $this->maishapay_commission($montant, $monnaie);
        $cout->monnaie = $monnaie;
        $cout->current_date = new DateTime();

        $cout[] = $journal;
        R::store($cout);

    }

    public function taux()
    {

        if ($taux = R::findOne('taux'))

            return $this->result = $taux['usd'];

        return $this->result = 0;
    }

    public function conversion($montant, $monnaie)
    {

        if ($monnaie == "USD") {
            $this->result = number_format(($montant * $this->taux()), 5, ".", " ") . " CDF";
        } else {

            $this->result = number_format(($montant / $this->taux()), 6, ".", " ") . " USD";
        }
        return $this->result;

    }

    public function update_profile($telephone, $nom, $prenom, $email, $adresse, $ville, $pin)
    {

        $user = R::findOne('user', 'telephone=?', [$telephone]);

        if ($user) {

            if (!password_verify($pin, $user['password']))
                return $this->result = 2;

            if ($this->checkEmail($email))
                return $this->result = 3;

            $updateUser = R::load('user', $user->getID());
            $updateUser->name = $nom;
            $updateUser->firstname = $prenom;
            $updateUser->email = $email;
            $updateUser->adresse = $adresse;
            $updateUser->ville = $ville;

            if (R::store($updateUser)) {

                return $this->result = 1;

            } else {
                return $this->result = 0;
            }
        }

        return $this->result = 4;

    }

    public function rapport($telephone)
    {

        $logs = R::findAll('journal', 'destinateur=? OR expediteur=? order by id desc', [$telephone, $telephone]);
        $this->result = array("resultat" => 1);

        if ($logs)
            foreach ($logs as $log) {
                if ($log['type_journal'] == 1) {

                    $destinateur = R::findOne('user', 'telephone=?', [$log['destinateur']]);
                    $expediteur = R::findOne('user', 'telephone=?', [$log['expediteur']]);

                    if ($destinateur && $expediteur) {
                        if ($log['expediteur'] == $telephone)
                            $this->result['transactions'][] = array(
                                "type_jrn" => "e",
                                "date_jrn" => $log['current_date'],
                                "heure_jrn" => '',
                                "montant_jrn" => $log['montant'],
                                "monnaie_jrn" => $log['monnaie'],
                                "telephone_dest" => $log['destinateur'],
                                "nom_dest" => $destinateur['name'],
                                "prenom_dest" => $destinateur['firstname']
                            );
                        else
                            $this->result['transactions'][] = array(
                                "type_jrn" => "r",
                                "date_jrn" => $log['current_date'],
                                "heure_jrn" => '',
                                "montant_jrn" => $log['montant'],
                                "monnaie_jrn" => $log['monnaie'],
                                "telephone_dest" => $log['expediteur'],
                                "nom_dest" => $expediteur['name'],
                                "prenom_dest" => $expediteur['firstname']
                            );
                    }
                }
            }

        return $this->result;
    }

    public function pin_perdu($telephone, $email)
    {

        $user = R::findOne('user', 'telephone=? AND email=?', [$telephone, $email]);

        if ($user) {

            $number = rand(123456, 987654);
            $pin = R::dispense('codes');
            $pin->email = $email;
            $pin->pin = $number;

            R::store($pin);

            return $this->result = Mailer::sendmail("Reinitialiser votre mot de passe", Mailer::pin_pedu_template($telephone, $number), $email, 'Maishapay Message');

        }

        return $this->result = array('resultat' => 0, 'message' => 'Numero de telephone ou Email introvable');
    }

    public function contact($subject, $body, $email)
    {

        $to = 'contact@maishapay.online';
        $html = "<html><body><h1><u>" . $email . "</u></h1><div style='height:auto; overflow:auto'><div>Objet : " . $subject . "</div><div>Body : " . $body . "</div></div></body></html> ";
        return $this->result = Mailer::contact($subject, $body, $to, "Maishapay User");
    }


    public function recharge($telephone, $qte_unite, $code_credit, $reseau)
    {
        $recharge = R::dispense('recharge');
        $recharge->vendor = $telephone;
        $recharge->client = "pending";
        $recharge->qte_unite = $qte_unite;
        $recharge->code_credit = $code_credit;
        $recharge->reseau = $reseau;
        $recharge->status = 1;

        if (R::store($recharge))
            return $this->result = array('resultat' => 1, 'message' => 'transfer success');
        return $this->result = array('resultat' => 0, 'message' => 'transfer failed');

    }

    public function recharge_moi($telephone, $qte_unite, $reseau)
    {
        $recharge = R::findOne('recharge', 'qte_unite=? AND reseau=? AND status=?', [$qte_unite, $reseau, 1]);

        if (!$recharge)
            return $this->result = array('resultat' => 0, 'message' => 'Oops! la cabine est fermée.');

        $updateRecharge = R::load('recharge', $recharge->getID());
        $updateRecharge->status = 0;
        $updateRecharge->client = $telephone;
        R::store($updateRecharge);

        return $this->result = array('resultat' => 1, 'code_credit' => $recharge['code_credit'], '');
    }

    function request_payment($apiKey, $amount, $monnaie)
    {

        $token = sha1(uniqid(rand() . time(), "urn:maishapay:token"));

        $payment_request = R::dispense('payments');
        $payment_request->api_key = $apiKey;
        $payment_request->montant = $amount;
        $payment_request->monnaie = $monnaie;
        $payment_request->token = $token;

        $project = R::findOne('projects', 'api_key=?', [$apiKey]);

        if ($project) {

            R::store($payment_request);

            $this->result = array('resultat' => '1', 'token' => $token, 'api_key' => $apiKey, 'montant' => $amount, 'monnaie' => $monnaie, 'api_info' => $project, 'message' => 'Your payment request is pending');

        } else {

            $this->result = array('resultat' => '0', 'data' => null, 'message' => "Merchant api doesn't much");

        }

        return $this->result;
        //}
    }

    function request_completed($apiKey, $token)
    {

        $this->result = R::findOne('payments', 'api_key=? AND token=? AND status=?', [$apiKey, $token, 1]);
        if ($this->result) {
            $this->result['resultat'] = 1;
            $this->result['message'] = 'Payment completed!';
            $this->result['id'] = 'null';
            $this->result['status'] = 'completed';
            return $this->result;
        } else {
            return $this->result = array('resultat' => 0, "message" => 'Payment still pending...');
        }
    }

    function attempt_payment($api_key, $token)
    {
        // 0 for transaction pending
        $transaction = R::findOne('payments', 'api_key=? AND token=? AND status=0', [$api_key, $token]);

        if ($transaction) {

            $project = R::findOne('projects', 'api_key=?', [$api_key]);

            if ($project) {

                $this->result = array("resultat" => 1, "message" => 'Payment is pending', 'apiData' => $project, 'transactionData' => $transaction);

            } else {

                $this->result = array("resultat" => 0, "message" => 'Merchant api not found exception');
            }

        } else {
            $this->result = array("resultat" => 0, "message" => 'Payment request not found exception');
        }

        return $this->result;
    }

    function confirm_payment($api_key, $token, $client, $merchant, $montant, $monnaie, $pin)
    {

        $pending_payment = $this->attempt_payment($api_key, $token);

        if ($pending_payment['resultat'] == 1) {

            $paymentId = $pending_payment['transactionData']['id'];

            $res = $this->transfert_compte_confirmation($client, $merchant, $montant, $monnaie, $pin);

            if ($res['resultat'] == 1) {

                $transaction = R::load('payments', $paymentId);
                $transaction->status = 1;

                R::store($transaction);

                $this->result = array("resultat" => 1, "message" => 'Payment request success');

            } else {

                $this->result = array("resultat" => 2, "message" => 'Payment request failure');
            }

        } else {

            $this->result = array("resultat" => 0, "message" => "Payment pending does'nt much");
        }

        return $this->result;

    }

    public function create_merchant($telephone, $nom, $prenom, $email, $adresse, $ville, $password)
    {

        $this->result = $this->checkMerchant($telephone);

        if ($this->result['resultat'] == 1) {

            return $this->result = array("resultat" => 0, "message" => "Ce numero de telephone est déjà utilié");

        } else if ($this->result['resultat'] == 2) {

            //$res = sendmail('Maisahapay merchant creation', 'confirm your account before using', $email);
            $merchant = R::dispense('user');
            $merchant->id = $this->result['id'];
            $merchant->usertype = 'merchant';

            R::store($merchant);

            $res = sendmail('Maisahapay Notification', "Votre compte marchant viens d'etre activer\n Un mail de confirmation vous a ete envoyer", $email);
            $res = sendmail('Confirmez votre compte Maisahapay', "http://maishapay.online/activation?email=" . $email, $email);

            return $this->result = array("resultat" => 2, "message" => "Votre compte marchant viens d'etre activer");

        } else {

            $this->result = $this->insccription($telephone, $nom, $prenom, $email, $adresse, $ville, $password, 'merchant');

            if ($this->result['resultat'] == 1) {
                $res = sendmail('Maisahapay merchant creation', 'confirm your account before using', $email);
                $res = sendmail('Confirmez votre compte Maisahapay', "http://maishapay.online/activation?email=" . $email, $email);

                return $this->result = array("resultat" => 1, "message" => "Vous venez de creez un compte marchant\n Un mail de confirmation vous a ete envoyer");
            }
        }

        return $this->result;

    }

    function create_project($name, $phone, $project_email, $project_name, $project_type, $project_description, $project_logo, $project_redirect_url, $project_callback_url)
    {

        $api_key = 'urn:maishapay:' . sha1(uniqid(rand() . time()));

        $project = R::dispense('projects');

        $project->merchant_name = $name;
        $project->merchant_phone = $phone;
        $project->merchant_email = $project_email;
        $project->api_key = $api_key;
        $project->project_name = $project_name;
        $project->project_type = $project_type;
        $project->project_description = $project_description;
        $project->project_token = sha1(uniqid(rand() . time()));
        $project->project_logo = $project_logo;
        $project->project_redirect_url = $project_redirect_url;
        $project->project_callback_url = $project_callback_url;

        $this->result = $this->checkMerchant($phone);

        if ($this->result['resultat'] == 1)

            if (R::store($project)) {

                $mailTo = sendmail('Maisahapay Projects', create_project_template($api_key, 'http://maishapay.online/project?email=' . $this->email), $project_email);
                return $this->result = array('resultat' => 1/*, 'api_key' => $api_key*/, 'message' => "operation effectué avec succès! un email contenant votre API-KEY vous a été envoyé");
            } else if ($this->result['resultat'] == 2)
                return $this->result = array('resultat' => 2/*, 'api_key' => $api_key*/, 'message' => "Vous n'etes pas encore marchant");


        return $this->result = array('resultat' => 0, 'message' => 'operantion fialed!');

    }


    function transfert_epargne($telephone, $type_transfert, $montant, $monnaie)
    {

        if ($type_transfert == "p") {

            $epargne = R::findOne('epargne1', 'telephone=?', [$telephone]);

        } else {

            $epargne = R::findOne('epargne2', 'telephone=?', [$telephone]);
        }

        if (!$epargne) {

            return $this->result = array("resultat" => 0, 'message' => 'compte epargne introubable');

        } else {

            if ($this->checkSolde($telephone, $montant, $monnaie)) {

                return $this->result = array('resultat' => 1, 'message' => 'tranfert possible');

            } else {

                return $this->result = array('resultat' => 2, 'message' => 'solde insuffisant');

            }

        }
    }


    function confirmation_transfert_epargne($telephone, $type_transfert, $montant, $monnaie, $pin)
    {
        //if (!$this->result = $this->login($telephone, $pin)['resultat'] == 1)
            //return $this->result;

        $this->result = $this->transfert_epargne($telephone, $type_transfert, $montant, $monnaie);

        if ($this->result['resultat'] == 1) {
            if ($type_transfert=='p')
                $this->result = $this->updateSoldeEpargne($telephone, 'epargne1', $montant, $monnaie);
            else{
                $this->result = $this->updateSoldeEpargne($telephone, 'epargne2', $montant, $monnaie);
            }
        }
    }

    private function updateSoldeEpargne($telephone, $type_epargne, $montant, $monnaie)
    {
		$monnaie = strtolower($monnaie);
        $solde_expediteur = R::findOne('solde', 'telephone=?', [$telephone]);
        $solde_destinateur = R::findOne($type_epargne, 'telephone=?', [$telephone]);

        $updateSoldeExpediteur = R::load('solde', $solde_expediteur->getID());
        $updateSoldeExpediteur->$monnaie = $solde_expediteur[$monnaie] - $montant; //- $this->maishapay_commission($montant, 'FC'); // - commission

        // update solde fc
        $updateSoldeDestinateur = R::load($type_epargne, $solde_destinateur->getID());
        $updateSoldeDestinateur->$monnaie = $solde_destinateur[$monnaie] + $montant;

        if (R::store($updateSoldeExpediteur))
            if (R::store($updateSoldeDestinateur)) {
                //$this->journalisation($telephone, $telephone, $montant, $monnaie);
                return $this->result = array('resultat' => 1, 'message' => 'transfert effectue avec success');
            }
        return $this->result = array('resultat' => 2, 'message' => 'Oparation failed');
    }

    public function depot($telephone, $montant, $monnaie) {
        $monnaie = strtolower($monnaie);
        $solde_expediteur = R::findOne('solde', 'telephone=?', [$telephone]);

        $updateSoldeExpediteur = R::load('solde', $solde_expediteur->getID());
        $updateSoldeExpediteur->$monnaie = $solde_expediteur[$monnaie] + $montant; 

        if (R::store($updateSoldeExpediteur)) {
            $this->journalisation($telephone, $telephone, $montant, $monnaie);
            return $this->result = array('resultat' => 1, 'message' => 'depot effectue avec success');
        }
    }
}
