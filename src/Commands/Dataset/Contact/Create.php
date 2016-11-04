<?php namespace Dxi\Commands\Dataset\Contact;

use Dxi\Commands\Command;

class Create extends Command {

    /**
     * Get the payload for the command
     *
     * @return array
     */
    public function getRequiredParams()
    {
        return [
            'firstname',
            'dataset',
            [
                'ddi_home',
                'ddi_mobile',
                'ddi_work'
            ]
        ];
    }

    /**
     * Set the phone number
     *
     * @param $phone
     * @return $this
     */
    public function getPhone($phone)
    {
        $phoneType = (preg_match('/^(07|\+447)/', $phone, $ret) ? 'ddi_mobile' : 'ddi_home');
        $this->params[$phoneType] = $phone;
        return $this;
    }

    /**
     * Get the method for the command
     *
     * @return string
     */
    public function getMethod()
    {
        return 'ecnow_records';
    }

    /**
     * Get the url path
     *
     * @return string
     */
    public function getUrlPath()
    {
        return '/ecnow.php';
    }

    /**
     * Get the action for the command
     *
     * @return string
     */
    public function getAction()
    {
        return 'create';
    }
}