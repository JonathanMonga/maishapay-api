<?php
/**
 * Created by PhpStorm.
 * User: Maishapay Inc.
 * Date: 23/02/2019
 * Time: 20:39
 */

require_once 'Osms.php';
require_once 'engine.php';

class SmsGateway {

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
    private $qte_unite;

    private function Encapsulate($field)
    {

        if (isset($_POST[$field])) {

            return $this->format_text($_POST[$field]);
        } else {
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
    public function getBody()
    {
        return $this->pin = $this->Encapsulate('body');
    }

    private function format_text($text)
    {
        return htmlentities(utf8_decode(htmlspecialchars($text)));
    }

    /**
     * SmsGateway constructor.
     * @param string $header
     */
    public function __construct($header = '', $test)
    {
        $config = array(
            'clientId' => 'r9MSiKjfBghqvEsUBQNeypNf1hAi2PDS',
            'clientSecret' => 'rewtuGqP7ngTIUG2',
            'token' => 'cjlNU2lLamZCZ2hxdkVzVUJRTmV5cE5mMWhBaTJQRFM6cmV3dHVHcVA3bmdUSVVHMg=='
        );

        $osms = new Osms($config);

        $this->header = $this->Encapsulate('ent');

        $this->result = array('resultat' => 0, 'message' => 'ERROR SmsGateway.');

        switch ($this->header) {
            case 'message':
                $this->router($osms, $this->getTelephone(), $this->getBody(), $test);
                break;
        }
    }

    public function result()
    {
        echo json_encode($this->result);
    }

    public function router($osms, $telephone, $body, $test)
    {
        $router_body = str_replace("*", "#", $body);

        if("INTERNAL_MESSAGE" == explode("#", $router_body)[1]) {
            $rawBody = $router_body;
            $routerSMS = 'INTERNAL_MESSAGE';
        } else {
            $rawBody = mb_strtolower($router_body);
            $routerSMS = explode("#", $rawBody)[1];
        }

        if ($routerSMS == '222') {
        	if(sizeof(explode("#", $rawBody)) == 4) {
        		$password = explode("#", $rawBody)[2];
        	    $this->solde_sms($osms, $telephone, $password, $test);
        	    $this->result();
        	} else {
        		$this->result = array('resultat' => 0, 'message' => 'Router error');
        		$this->result();
        	}
        } else if ($routerSMS == '223') {
        	if(sizeof(explode("#", $rawBody)) == 4) {
        		$password = explode("#", $rawBody)[2];
        	    $this->solde_epargne_sms($osms, $telephone, $password, $test);
        	    $this->result();
        	} else {
        		$this->result = array('resultat' => 0, 'message' => 'Router error');
        	}
        } else if ($routerSMS == '224') {
            if(sizeof(explode("#", $rawBody)) == 7) {
        		list($vide, $routerSMS, $agent, $amount, $currency, $password, $vide) = explode("#", $rawBody);

                if($agent != $telephone) {
                    $this->retrait_sms($osms, $telephone, $agent, $amount, $currency, $password, $test);
                    $this->result();
                } else {
                    $this->result = array('resultat' => 0, 'message' => 'Router error');
                    $this->result();
                }
            } else {
        		$this->result = array('resultat' => 0, 'message' => 'Router error');
        		$this->result();
        	}
        } else if ($routerSMS == '225') {
            if(sizeof(explode("#", $rawBody)) == 4) {
                $token = explode("#",$rawBody)[2];
                $this->confirm_mobile_money_transaction($osms, $telephone, $token, $test);
                $this->result();
            } else {
                $this->result = array('resultat' => 0, 'message' => 'Router error');
                $this->result();
            }
        }  else if ($routerSMS == 'INTERNAL_MESSAGE') {
            $internal_message = explode("#",$rawBody)[2];
            $this->send_internal_message($osms, $internal_message, $test);
            $this->result();
        } else if ($routerSMS == 'depot') {
            $transactionId = explode("#", $rawBody)[2];
            $amount = explode("#", $rawBody)[3];
            $currency = explode("#", $rawBody)[4] == "cdf" ? "fc" : "usd";
            $sender_numero = explode("#", $rawBody)[5];
            
            if (isset($transactionId) && isset($amount) && isset($currency) && isset($sender_numero)) {
                $this->mobile_money_transaction_sms($osms, $telephone, $sender_numero, $transactionId, $amount, $currency, "AIRTEL CD", $test);
                $this->result();
            } else {
                $this->result = array('resultat' => 0, 'message' => 'Router error');
                $this->result();
            }
        } else {
            $this->result = array('resultat' => 0, 'message' => 'Router error');
            $this->result();
        }
    }

    private function send_internal_message($osms, $internal_message, $test)
    {
        $response = $osms->getTokenFromConsumerKey();
        $telephone = '243972435000';

        if(!$test)
            $this->sendSMS($telephone, $internal_message, $osms, $response);
        else
            echo $internal_message;
    }

    private function solde($user, $osms, $response, $telephone, $test)
    {
        $solde = R::findOne('solde', 'telephone=?', [$telephone]);

        $message = "Maishapay trans ID : ".date('\M\S\C.j.m.Y.h.i.s').
                "\n".$user['firstname']." ".$user['name']."".
                "\nSolde Courant CDF : ".$solde['fc']." FC.".
                "\nSolde Courant USD : ".$solde['usd']." USD.";

        if(!$test) 
        	$this->sendSMS($telephone, $message, $osms, $response);
        else
        	$this->result = array('resultat' => 1, 'message' => $message);
    }

    private function solde_epargne($user, $osms, $response, $telephone, $test = true)
    {
        $epargne = R::findOne('epargne1', 'telephone=?', [$telephone]);

        if($epargne) {
            $message = "Maishapay trans ID : " . date('\M\S\E.j.m.Y.h.i.s') .
                "\n" . $user['firstname'] . " " . $user['name'] . "" .
                "\nSolde Epargne CDF : " . $epargne['fc'] . " FC." .
                "\nSolde Epargne USD : " . $epargne['usd'] . " USD";
        } else {
            $message = "Maishapay trans ID : " . date('\M\S\E.j.m.Y.h.i.s') .
                "\n" . $user['firstname'] . " " . $user['name'] . "" .
                "\nVous n'avez pas de compte d'epagne.";
        }

        if(!$test)
        	$this->sendSMS($telephone, $message, $osms, $response);
        else
        	$this->result = array('resultat' => 1, 'message' => $message);
    }

    private function solde_sms($osms, $telephone, $pin, $test)
    {
        $response = $osms->getTokenFromConsumerKey();

        $user = R::findOne('user', 'telephone=?', [$telephone]);

        if ($user) {
        	if (password_verify($pin, $user['password'])) {
                    $this->solde($user, $osms, $response, $telephone, $test);
                } else {
                    $this->result = array('resultat' => 0, 'message' => 'ERROR SOLDE COURANT - BAD PASSWORD');
                }
        } else {
            $this->result = array('resultat' => 0, 'message' => 'ERROR SOLDE COURANT - USER NOT FOUND');
        }
    }

    private function solde_epargne_sms($osms, $telephone, $pin, $test)
    {
        $response = $osms->getTokenFromConsumerKey();

        $user = R::findOne('user', 'telephone=?', [$telephone]);

        if ($user) {
            	if (password_verify($pin, $user['password'])) {
                       $this->solde_epargne($user, $osms, $response, $telephone, $test);
                } else {
                    $this->result = array('resultat' => 0, 'message' => 'ERROR EPARGNE - BAD PASSWORD');
                }
        } else {
            $this->result = array('resultat' => 0, 'message' => 'ERROR EPARGNE - USER NOT FOUND');
        }
    }

    /**
     * Methode de confirmation de retrait par sms
     */

    public function retrait_sms($osms, $expediteur, $agent, $montant, $monnaie, $pin, $test)
    {
        $response = $osms->getTokenFromConsumerKey();

        $transaction_id = date('\M\R\T.j.m.Y.h.i.s');

        $agentUser = R::findOne('user', 'telephone=?', [$agent]);
        $expediteurUser = R::findOne('user', 'telephone=?', [$expediteur]);

        if($expediteurUser && $agentUser && !$montant == '' && !$monnaie == '' && !$pin == '') {
        		if(password_verify($pin, $expediteurUser['password'])) {
                if ($monnaie == 'fc') {
					if(floatval($montant >= 1000)) {
                    if ($this->result = $this->updateSoldeFC($expediteur, $agent, $montant, 2)['resultat'] == 1) {
                        $soldeAgent = R::findOne('solde', 'telephone=?', [$agent]);
                        $soldeExpediteur = R::findOne('solde', 'telephone=?', [$expediteur]);

                        $messageExpeditaire = "Maishapay trans ID : ".$transaction_id.
                            "\n".$expediteurUser->firstname." ".$expediteurUser->name." ".
                            "\nVous avez retiré ".$montant." ".strtoupper($monnaie)." ".
                            "\nAu pres de ".$agent.",".$agentUser->firstname." ".$agentUser->name." ".
                            "\nVotre solde ".strtoupper($monnaie)." disponible est ".$soldeExpediteur->fc." ".strtoupper($monnaie)."".
							"\nCout : ".$this->maishapay_commission($montant, strtolower($monnaie))." ".strtoupper($monnaie);

                        $messageAgent = "Maishapay trans ID : ".$transaction_id.
                            "\n".$expediteurUser->firstname." ".$expediteurUser->name." ".
                            "a retiré ".$montant." ".strtoupper($monnaie)." au pres de vous.".
							"\n".$agent.",".$agentUser->firstname." ".$agentUser->name." ".
                            "\nVotre solde ".strtoupper($monnaie)." disponible est ".$soldeAgent->fc." ".strtoupper($monnaie)."".
							"\nCout : ".$this->maishapay_commission($montant, strtolower($monnaie))." ".strtoupper($monnaie);

                        if(!$test){
                            $this->sendSMS($agent, $messageAgent, $osms, $response);
                            $this->sendSMS($expediteur, $messageExpeditaire, $osms, $response);
                        } else {
                            echo $messageExpeditaire;
                            echo $messageAgent;
                        }
                    } else {
                        $messageExpeditaire = "Maishapay trans ID : ".$transaction_id. "\nVotre solde est insuffisant. Veuillez faire un depot pour sur compte.";

                        if(!$test){
                             $this->result = array('resultat' => 0, 'message' => 'ERROR RETRAIT - SOLDE INSUFISSANT');
                             $this->sendSMS($expediteur, $messageExpeditaire, $osms, $response);
                        } else {
                            echo $messageExpeditaire;
                        }
                    }
				} else {
						$this->result = array('resultat' => 0, 'message' => 'ERROR RETRAIT - BAD AMOUNT');
				}
                } else {
					if(floatval($montant >= 1)) {
                    if ($this->result = $this->updateSoldeUSD($expediteur, $agent, $montant, 2)['resultat'] == 1) {
                        $soldeAgent = R::findOne('solde', 'telephone=?', [$agent]);
                        $soldeExpediteur = R::findOne('solde', 'telephone=?', [$expediteur]);

                        $messageExpeditaire = "Maishapay trans ID : ".$transaction_id.
                            "\n".$expediteurUser->firstname." ".$expediteurUser->name." ".
                            "\nVous avez retiré ".$montant." ".strtoupper($monnaie)." ".
                            "\nAu pres de ".$agent.",".$agentUser->firstname." ".$agentUser->name." ".
                            "\nVotre solde ".strtoupper($monnaie)." disponible est ".$soldeExpediteur->usd." ".strtoupper($monnaie)."".
							"\nCout : ".$this->maishapay_commission($montant, strtolower($monnaie))." ".strtoupper($monnaie);

                        $messageAgent = "Maishapay trans ID : ".$transaction_id.
                            "\n".$expediteur.",".$expediteurUser->firstname." ".$expediteurUser->name." ".
                            "a retiré ".$montant." ".strtoupper($monnaie)." au pres de vous.".
							"\n".$agent.",".$agentUser->firstname." ".$agentUser->name." ".
                            "\nVotre solde ".strtoupper($monnaie)." disponible est ".$soldeAgent->usd." ".strtoupper($monnaie)."".
							"\nCout : ".$this->maishapay_commission($montant, strtolower($monnaie))." ".strtoupper($monnaie);


                        if(!$test){
                            $this->sendSMS($agent, $messageAgent, $osms, $response);
                            $this->sendSMS($expediteur, $messageExpeditaire, $osms, $response);
                        } else {
                            echo $messageExpeditaire;
                            echo $messageAgent;
                        }
                    } else {
                        $messageExpeditaire = "Maishapay trans ID : ".$transaction_id. "\nVotre solde est insuffisant. Veuillez faire un depot pour sur compte.";
                       
                        if(!$test){
                            $this->result = array('resultat' => 0, 'message' => 'ERROR RETRAIT - SOLDE INSUFISSANT');
                            $this->sendSMS($expediteur, $messageExpeditaire, $osms, $response);
                        } else {
                            echo $messageExpeditaire;
                        }
                    }
					} else {
						$this->result = array('resultat' => 0, 'message' => 'ERROR RETRAIT - BAD AMOUNT');
					}
                }
            } else {
                $this->result = array('resultat' => 0, 'message' => 'ERROR RATRAIT - BAD PASSWORD');
            }
        } else {
            $this->result = array('resultat' => 0, 'message' => 'ERROR RATRAIT - SOME THINGS IS BAD');
        }
    }

    private function transfert_compte($expediteur, $destinateur, $montant, $monnaie){
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
 
    private function maishapay_commission($montant, $monnaie){
		
        if ($monnaie == 'usd') {
            if ($montant >= 10) {
				$fee = 1.00;
                return $fee;
            } else {
				$fee = 0.500;
                return $fee;
            }
        } else if ($monnaie == 'fc') {
            if ($montant >= 10000) {
				$fee = 1000;
                return $fee;
            } else {
				$fee = 500;
                return $fee;
            }
        }
    }

    private function updateSoldeUSD($expediteur, $destinateur, $montant, $type){
        if ($this->result = $this->transfert_compte($expediteur, $destinateur, $montant, 'usd')['resultat'] == 1) {

            $solde_expediteur = R::findOne('solde', 'telephone=?', [$expediteur]);
            $solde_destinateur = R::findOne('solde', 'telephone=?', [$destinateur]);

            $updateSoldeExpediteur = R::load('solde', $solde_expediteur->getID());
            $updateSoldeExpediteur->usd = $solde_expediteur['usd'] - $montant - $this->maishapay_commission($montant, 'usd'); // - commission

            // update solde
            $updateSoldeDestinateur = R::load('solde', $solde_destinateur->getID());
            $updateSoldeDestinateur->usd = $solde_destinateur['usd'] + $montant;

            if (R::store($updateSoldeExpediteur))
                if (R::store($updateSoldeDestinateur)) {

                    // journalisation
                    $this->journalisation($expediteur, $destinateur, $montant, 'usd', $type);
                    return $this->result = array('resultat' => 1, 'fee' => $this->maishapay_commission($montant, 'usd'));
                }

            return $this->result = array('resultat' => 2, 'message' => 'Oparation failed');
        } else {
            return $this->result;
        }
    }

    private function updateSoldeFC($expediteur, $destinateur, $montant, $type){
        if ($this->result = $this->transfert_compte($expediteur, $destinateur, $montant, 'fc')['resultat'] == 1) {

            $solde_expediteur = R::findOne('solde', 'telephone=?', [$expediteur]);
            $solde_destinateur = R::findOne('solde', 'telephone=?', [$destinateur]);

            $updateSoldeExpediteur = R::load('solde', $solde_expediteur->getID());
            $updateSoldeExpediteur->fc = $solde_expediteur['fc'] - $montant - $this->maishapay_commission($montant, 'fc'); // - commission

            // update solde fc
            $updateSoldeDestinateur = R::load('solde', $solde_destinateur->getID());
            $updateSoldeDestinateur->fc = $solde_destinateur['fc'] + $montant;

            if (R::store($updateSoldeExpediteur))
                if (R::store($updateSoldeDestinateur)) {
                    $this->journalisation($expediteur, $destinateur, $montant, 'fc', $type);
                    return $this->result = array('resultat' => 1, 'fee' => $this->maishapay_commission($montant, 'fc'));
                }
            return $this->result = array('resultat' => 2, 'message' => 'Oparation failed');

        } else {
            return $this->result  = array('resultat' => 2, 'message' => 'Oparation failed');
        }
    }

    private function journalisation($expediteur, $destinateur, $montant, $monnaie, $type){
        $journal = R::dispense('journal');
        $journal->expediteur = $expediteur;
        $journal->destinateur = $destinateur;
        $journal->montant = $montant;
        $journal->monnaie = $monnaie;
        $journal->type_journal = $type;
        $journal->status = 'C';

        $cout = R::dispense('couttransaction');
        $cout->montant = $this->maishapay_commission($montant, $monnaie);
        $cout->monnaie = $monnaie;
        $cout->current_date = new DateTime('now');

        $cout[] = $journal;
        R::store($cout);

    }

    private function checkSolde($telephone, $montant, $monnaie){
        if ($monnaie == 'fc')

            return $this->result = R::count('solde', 'telephone=? AND fc > ?', [$telephone, ($montant + $this->maishapay_commission($montant, 'fc'))]);

        else if ($monnaie == 'usd')

            return $this->result = R::count('solde', 'telephone=? AND usd > ?', [$telephone, ($montant + $this->maishapay_commission($montant, 'usd'))]);
    }

    private function checkUser($telephone){
        $this->result = R::findOne('user', 'telephone=?', [$telephone]);

        if ($this->result) {
            $this->result['resultat'] = 1;
            return $this->result;
        } else
            return $this->result = array('resultat' => 0, 'message' => 'User not found');
    }

    private function checkTelephone($telephone, $type = 'user')
    {
        return $user = R::count($type, 'telephone=?', [$telephone]);
    }

    private function sendSMS($receiver, $message, $osms, $response){

        if(array_key_exists('access_token', $response)) {
            $osms->setToken($response['access_token']);
            $response = $osms->sendSms('tel:+243859152704', 'tel:+' . $receiver, $message);

            if (empty($response['error'])) {
                $this->result = array('resultat' => 1, 'message' => 'OK');
            } else {
                $this->result = array('resultat' => 0, 'message' => $response['error']);
            }
        } else {
            $this->result = array('resultat' => 0, 'message' => 'ERROR SEND SMS.');
        }
    }

    /**
     * Methode de confirmation de transaction par mobile money
     */
    
    public function confirm_mobile_money_transaction($osms, $telephone, $token, $test)
    {
        $response = $osms->getTokenFromConsumerKey();
        $maishapay_transaction_id = date('\M\D\A.j.m.Y.h.i.s');

        if (R::findOne('user', 'telephone=?', [$telephone])) {
            $mobileMoneyTransaction = R::findOne('mobile_money', 'token=?', [$token]);
            $solde = R::findOne('solde', 'telephone=?', [$telephone]);

            if ($this->checkMobileMoneyTokenAndStatus($token)) {
                    $monnaie = strtolower($mobileMoneyTransaction->currency);
                
                    $updateSolde = R::load('solde', $solde->getID());
                    $updateSolde->$monnaie = $solde[$monnaie] + $mobileMoneyTransaction->amount;
                
                    $mobile_money = R::load('mobile_money', $mobileMoneyTransaction->getID());
                    $mobile_money->confirm_user = $telephone;
                    $mobile_money->status = 1;

                    if(R::store($mobile_money)) {
                        if(R::store($updateSolde)) {
                            $this->journalisation(null, $telephone, $mobileMoneyTransaction->amount, $monnaie, 4);
                            $updateSolde = R::findOne('solde', 'telephone=?', [$telephone]);

                            if($monnaie == 'fc') {
                                $message = "Maishapay trans ID : ".$maishapay_transaction_id."\nMerci d'avoir confirmer votre dépot. Votre solde disponible est ".$updateSolde->fc." ".strtoupper($monnaie);
            
                                if(!$test)
                                    $this->sendSMS($telephone, $message, $osms, $response);
                                else
                                    $this->result = array('resultat' => 1, 'message' => $message);
                           } else {
                                $message = "Maishapay trans ID : ".$maishapay_transaction_id."\nMerci d'avoir confirmer votre dépot. Votre solde disponible est ".$updateSolde->usd." ".strtoupper($monnaie);
            
                                if(!$test)
                                    $this->sendSMS($telephone, $message, $osms, $response);
                                else
                                    $this->result = array('resultat' => 1, 'message' => $message);
                            }
                        }
                    }
            } else {
                $this->result = array('resultat' => 0, 'message' => 'Le code a deja ete utiliser.');
            }
        } else {
            $this->result = array('resultat' => 0, 'message' => 'Mot de passe ou numero incorrecte');
        }
    }
    
    /**
     * Methode de demande de transaction par mobile money
     */
    
    public function mobile_money_transaction_sms($osms, $from, $sender_numero, $transactionId, $amount, $currency, $operatorName, $test)
    {
        $response = $osms->getTokenFromConsumerKey();
        $token = rand(123456, 987654);
        $maishapay_transaction_id = date('\M\D\A.j.m.Y.h.i.s');

        if ($this->checkMobileMoneyTransactionId($transactionId)) {
            $this->result = array('resultat' => 0, 'message' => 'Numero de transaction a déjà ete utiliser');
        } else {
        $mobile_money = R::dispense('mobile_money');
        $mobile_money->from = $from;
        $mobile_money->transaction_id = $transactionId;
        $mobile_money->sent_timestamp = new DateTime('now');
        $mobile_money->amount = $amount;
        $mobile_money->currency = $currency;
        $mobile_money->operator_name = $operatorName;
        $mobile_money->status = 0;
        $mobile_money->token = $token;
        $mobile_money->sender_numero = $sender_numero;

        if(R::store($mobile_money)) {
            $message = "Maishapay trans ID : ".$maishapay_transaction_id."\nNous avons reçu votre démande de dépot, confirmer votre ce dépot avec ce code :".$token;
            
            if(!$test)
                $this->sendSMS($sender_numero, $message, $osms, $response);
            else
               $this->result = array('resultat' => 1, 'message' => $message);
        } else {
            $message = "Maishapay trans ID : ".$maishapay_transaction_id."\nEchec de dépot.";
            
            if(!$test)
                $this->sendSMS($sender_numero, $message, $osms, $response);
            else
                $this->result = array('resultat' => 1, 'message' => $message);
        }
        }
    }
    
    private function checkMobileMoneyTokenAndStatus($token, $type = 'mobile_money')
    {
        return $mobileMoneyTransaction = R::findOne($type, 'token=? AND status = 0', [$token]);
    }

    private function checkMobileMoneyTransactionId($transactionId, $type = 'mobile_money')
    {
        return $mobileMoneyTransaction = R::count($type, 'transaction_id=?', [$transactionId]);
    }
}