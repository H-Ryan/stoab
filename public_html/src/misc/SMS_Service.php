<?php
/**
 * User: Samuil
 * Date: 25-08-2015
 * Time: 4:12 PM
 */
class SMS_Service
{
    var $url = "";
    var $to = "";
    var $text = "";

    function SMS_Service() {}

    public function generateSMS()
    {
        $data = array(
            'username'  => "stoab",
            'password'  => "Sarvari71",
            'from'      => "stoab",
            'to'        => $this->to,
            'text'      => $this->text
        );
        $this->url = "https://www.rebvoice.com/sadafa/sendsms.php?".http_build_query($data);
        return $this;
    }

    public function sendSMS() {
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