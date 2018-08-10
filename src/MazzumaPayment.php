<?php

namespace BaffourAdu\Mazzuma;

use BaffourAdu\Mazzuma\Exception\AmountValidateException;
use BaffourAdu\Mazzuma\Exception\TelephoneValidateException;

/**
 * Class MazzumaPayment
 *
 * The main class for API consumption
 *
 * @package BaffourAdu\Mazzuma
 */
class MazzumaPayment
{
    /** @var string The API access token */
    private $key;
    /** @var string The Network directional flow of payment */
    private $flow;
    /** @var string The network of the Sender */
    private $payeeNetwork;
    /** @var string The Sender Telephone Number */
    private $from;
    /** @var string The Reciever Telephone Number */
    private $to;
    /** @var integer The amount being Transfered */
    private $amount;
    /** @var integer The response from the API */
    private $apiResponse;

    /** @var string The API URL */
    private $api = 'https://client.teamcyst.com/api_call.php';

        
    /**
     * Creates a new MazzumaPayment Instance
     *
     */
    public function __construct($key)
    {
        $this->key = $key;
    }

    /**
     * Calls the API to process the transaction
     *
     * @return object The Response from the API Call
     */
    public function send()
    {
        if (!function_exists('curl_version')) {
            return "CURL isn't enabled on your Server !";
        }

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
        $response = curl_exec($ch);
        $this->apiResponse = json_decode($response, true);

        return $this->apiResponse;
    }

    /**
     * Parses the Payment Details into Json for API call
     * @param string $paymentDirectionalFlow  The Network directional flow of payment
     * @param string $payeeNetwork The Network Operator of the Sender
     * @param string $APIKey The API access key, as obtained on https://dashboard.mazzuma.com/
     * @param string $payee The Sender Telephone Number
     * @param string $reciever The Recievers Telephone Number
     * @param integer $amount The amount been transacted
     *
     * @return string
     */
    private function parsePaymentDetails(
        $paymentDirectionalFlow,
        $payeeNetwork,
        $APIKey,
        $payee,
        $reciever,
        $amount
    ) {
        if (empty($amount) ||
            empty($payeeNetwork) ||
            empty($reciever) ||
            empty($payee) ||
            empty($paymentDirectionalFlow) ||
            empty($APIKey)) {
            return "Invalid Input !";
        }

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
     * @return MazzumaPayment
     */
    public function from($payee)
    {
        $this->validateTelephone($payee);
        $this->from = trim($payee);

        return $this;
    }

    /**
     * returns the Sender
     *
     * @return string
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * Sets the Reciever
     * @param $reciever string The telephone Number of the Reciever
     *
     * @return MazzumaPayment
     */
    public function to($reciever)
    {
        $this->validateTelephone($reciever);
        $this->to = trim($reciever);

        return $this;
    }

    /**
     * returns the Reciever
     *
     * @return string
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * Sets the Amount
     * @param $totalAmount string The amount to be sent
     *
     * @return MazzumaPayment
     */
    public function amount($totalAmount)
    {
        $this->validateAmount($totalAmount);
        $this->amount = $totalAmount;

        return $this;
    }

    /**
     * returns the Amount
     * @param $totalAmount string The amount to be sent
     *
     * @return integer
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Sets the Sender Network
     * @param $paymentFlow string The flow of the Payment
     *
     * @return MazzumaPayment
     */
    public function transfer($paymentFlow)
    {
        $this->payeeNetwork = $this->getSenderNetwork($paymentFlow);

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
        if (!$this->apiResponse['status'] == 'success') {
            return false;
        }

        return true;
    }

    /**
     * Gets the Sender Network from the payment Flow
     *
     * @return boolean
     */
    private function getSenderNetwork($paymentFlow)
    {
        $networks = explode("_", trim($paymentFlow));

        return strtolower($networks[0]);
    }

    /**
     * returns the Reciever
     *
     * @return string
     */
    public function getPayeeNetwork()
    {
        return $this->payeeNetwork;
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
     * returns the Flow of the Transaction
     *
     * @return string
     */
    public function getFlow()
    {
        return $this->flow;
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
        if (preg_match("/^\d{3}\d{3}\d{4}$/", $telephone)) {
            return true;
        } else {
            throw new TelephoneValidateException('Telephone Number is Invalid.');
        }
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
