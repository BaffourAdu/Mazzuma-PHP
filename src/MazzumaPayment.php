<?php

namespace BaffourAdu\Mazzuma;

use BaffourAdu\Mazzuma\Exceptions;
use GuzzleHttp\Client;

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
    private static $key = null;
    /** @var string The Network directional flow of payment */
    private static $flow = null;
    /** @var string The network of the Sender */
    private static $payeeNetwork = null;

    /** @var string The Sender Telephone Number */
    private $from = null;
    /** @var string The Reciever Telephone Number */
    private $to = null;
    /** @var integer The amount being Transfered */
    private $amount = null;
    
    /** @var string The API URL */
    private $API = 'https://client.teamcyst.com/api_call.php';


        
    /**
     * Creates a new MazzumaPayment Instance
     * @return object
     */
    public function __construct($key)
    {
        self::$key = $key;

        return self::$key;
    }

    /**
     * Calls the API to process the transaction
     *
     * @return object The Response from the API Call
     */
    public function now()
    {
        $data = $this->parsePaymentDetails(self::$flow, self::$payeeNetwork, self::$key, $this->from, $this->to, $this->amount);
        
        $additional_headers = array(
            'Content-Type: application/json'
         );

        $ch = curl_init($this->API);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $additional_headers);
        $api_response = curl_exec($ch);

        return $api_response;
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
    public function parsePaymentDetails($paymentDirectionalFlow, $payeeNetwork, $APIKey, $payee, $reciever, $amount)
    {
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
        self::validateTelephone($payee);
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
        self::validateTelephone($reciever);

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
        self::validateAmount($totalAmount);

        $this->amount = $totalAmount;
        return $this;
    }



    /**
     * Sets the Sender Network
     * @param $paymentFlow string The flow of the Payment
     *
     * @return object
     */
    public static function recieve($paymentFlow): self
    {
        $networks = explode("_", $paymentFlow);
        self::$payeeNetwork = strtolower($networks[0]);

        self::setPaymentRoute($paymentFlow);

        return new static(self::$key, self::$payeeNetwork, self::$flow);
    }

    /**
     * Sets the Option Parameter in the Data Payload
     *
     * @param string $paymentDirection The flow of the Payment
     *
     * @return string Returns the ioption value for the payload
     */
    public static function setPaymentRoute($paymentDirection)
    {
        switch ($paymentDirection) {
            case 'MTN_TO_MTN':
                self::$flow = 'rmtm';
                break;
            case 'MTN_TO_AIRTEL':
                self::$flow = 'rmta';
                break;
            case 'MTN_TO_VODAFONE':
                self::$flow = 'rmtv';
                break;
            case 'AIRTEL_TO_MTN':
                self::$flow = 'ratm';
                break;
            case 'AIRTEL_TO_AIRTEL':
                self::$flow = 'rata';
                break;
            case 'AIRTEL_TO_VODAFONE':
                self::$flow = 'ratv';
                break;
            case 'VODAFONE_TO_MTN':
                self::$flow = 'rvtm';
                break;
            case 'VODAFONE_TO_AIRTEL':
                self::$flow = 'rvta';
                break;
            case 'VODAFONE_TO_VODAFONE':
                self::$flow = 'rvtv';
                break;
            default:
                self::$flow = null;
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
    private static function validateTelephone($telephone)
    {
        if (!is_string($telephone)) {
            throw new \InvalidArgumentException('Telephone Number must be a String.');
        }
        if (strlen($telephone) < 10) {
            throw new \InvalidArgumentException('Telephone Number is too short, and thus invalid.');
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
    private static function validateAmount($amount)
    {
        if (!is_numeric($amount)) {
            throw new \InvalidArgumentException('Amount must be a number.');
        }

        return true;
    }
}
