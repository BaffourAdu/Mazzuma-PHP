<?php

namespace BaffourAdu\Mazzuma;

use BaffourAdu\Mazzuma\Exception\AmountValidateException;
use BaffourAdu\Mazzuma\Exception\TelephoneValidateException;

/**
 * Class Mazzuma
 *
 * The main class for API consumption
 *
 * @package BaffourAdu\Mazzuma
 */
class MazzumaPayment
{
    /** @var string The API access token */
    private $key = null;
    /** @var string The Network directional flow of payment */
    private $flow = null;
    /** @var string The network of the Sender */
    private $payeeNetwork = null;
    /** @var string The Sender Telephone Number */
    private $from = null;
    /** @var string The Reciever Telephone Number */
    private $to = null;
    /** @var integer The amount being Transfered */
    private $amount = null;
    /** @var integer The response from the API */
    private $apiResponse = null;

    /** @var string The API URL */
    private $api = 'https://client.teamcyst.com/api_call.php';


        
    /**
     * Creates a new MazzumaPayment Instance
     *
     * @return object
     */
    public function __construct($key)
    {
        $this->key = $key;

        return $this;
    }

    /**
     * Calls the API to process the transaction
     *
     * @return object The Response from the API Call
     */
    public function send()
    {
        $data = $this->parsePaymentDetails(
            $this->flow,
            $this->payeeNetwork,
            $this->key,
            $this->from,
            $this->to,
            $this->amount
        );
        
        $additionalHeaders = array(
            'Content-Type: application/json'
         );

        $ch = curl_init($this->api);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $additionalHeaders);
        $this->apiResponse = curl_exec($ch);

        return $this->apiResponse;
    }

    /**
     * Parses the Payment Details into Json for API call
     * @param $paymentDirectionalFlow string The Network directional flow of payment
     * @param $payeeNetwork string The Network Operator of the Sender
     * @param $APIKey string The API access key, as obtained on https://dashboard.mazzuma.com/
     * @param $payee string The Sender Telephone Number
     * @param $reciever string The Recievers Telephone Number
     * @param $amount integer The amount been transacted
     * @return object
     */
    private function parsePaymentDetails(
        $paymentDirectionalFlow,
        $payeeNetwork,
        $APIKey,
        $payee,
        $reciever,
        $amount
    ) {
        $data = [
            "price"=> $amount,
            "network"=> $payeeNetwork,
            "recipient_number"=> $reciever,
            "sender"=> $payee,
            "option"=> $paymentDirectionalFlow,
            "apikey"=> $APIKey
        ];

        $json_data = json_encode($data);

        return $json_data;
    }

    /**
     * Sets the Sender
     * @param $payee string The telephone Number of the Sender
     *
     * @return object
     */
    public function from($payee)
    {
        $this->validateTelephone($payee);
        $this->from = $payee;

        return $this;
    }

    /**
     * Sets the Reciever
     * @param $reciever string The telephone Number of the Reciever
     *
     * @return object
     */
    public function to($reciever)
    {
        $this->validateTelephone($reciever);
        $this->to = $reciever;

        return $this;
    }

    /**
     * Sets the Amount
     * @param $totalAmount string The amount to be sent
     *
     * @return object
     */
    public function amount($totalAmount)
    {
        $this->validateAmount($totalAmount);
        $this->amount = $totalAmount;

        return $this;
    }


    /**
     * Sets the Sender Network
     * @param $paymentFlow string The flow of the Payment
     *
     * @return object
     */
    public function transfer($paymentFlow)
    {
        $networks = explode("_", $paymentFlow);
        $this->payeeNetwork = strtolower($networks[0]);

        $this->setPaymentRoute($paymentFlow);

        return $this;
    }

    /**
     * Checks if payment was successful
     *
     * @return boolean
     */
    public function isSuccessful()
    {
        if (!$this->apiResponse->status == 'success') {
            return false;
        }

        return true;
    }

    /**
     * Sets the Option Parameter in the Data Payload
     *`
     * @param string $paymentDirection The flow of the Payment
     *
     * @return string Returns the ioption value for the payload
     */
    private function setPaymentRoute($paymentDirection)
    {
        switch ($paymentDirection) {
            case 'MTN_TO_MTN':
                $this->flow = 'rmtm';
                break;
            case 'MTN_TO_AIRTEL':
                $this->flow = 'rmta';
                break;
            case 'MTN_TO_VODAFONE':
                $this->flow = 'rmtv';
                break;
            case 'AIRTEL_TO_MTN':
                $this->flow = 'ratm';
                break;
            case 'AIRTEL_TO_AIRTEL':
                $this->flow = 'rata';
                break;
            case 'AIRTEL_TO_VODAFONE':
                $this->flow = 'ratv';
                break;
            case 'VODAFONE_TO_MTN':
                $this->flow = 'rvtm';
                break;
            case 'VODAFONE_TO_AIRTEL':
                $this->flow = 'rvta';
                break;
            case 'VODAFONE_TO_VODAFONE':
                $this->flow = 'rvtv';
                break;
            default:
                $this->flow = null;
                break;
        }
    }

    /**
     * Validates The telephone Numbers
     *
     * @param string $telephone The telephone number of a reciever or sender
     *
     * @return boolean
     */
    private function validateTelephone($telephone)
    {
        if (!is_string($telephone)) {
            throw new TelephoneValidateException('Telephone Number must be a String.');
        }
        if (strlen($telephone) != 10) {
            throw new TelephoneValidateException('Telephone Number is Invalid.');
        }
        return true;
    }

    /**
     * Validates The Amount
     *
     * @param string $amount The amount been transacted
     *
     * @return boolean
     */
    private function validateAmount($amount)
    {
        if (!is_numeric($amount)) {
            throw new AmountValidateException('Amount must be a number.');
        }

        return true;
    }
}
