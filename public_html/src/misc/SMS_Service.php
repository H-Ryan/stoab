<?php
/**
 * User: Samuil
 * Date: 25-08-2015
 * Time: 4:12 PM.
 */
class SMS_Service
{
    public $url = '';
    public $to = '';
    public $text = '';

    public function __construct()
    {
    }

    public function generateSMS()
    {
        $data = array(
            'username' => 'c4tolk',
            'password' => 'Sarvari.71',
            'from' => 'c4tolk',
            'to' => $this->to,
            'text' => $this->text,
        );
        $this->url = 'https://www.rebvoice.com/reteye/sendsms.php?'.http_build_query($data); //myaccount

        return $this;
    }

    public function sendSMS()
    {
        return $this->url;
    }

    /**
     * @param string $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @param string $to
     */
    public function setTo($to)
    {
        $this->to = '0046'.$to;
    }
}
