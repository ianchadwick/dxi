<?php namespace Dxi\Exceptions;

class MissingRequiredParamsException extends DxiException {

    /**
     * List of required params
     *
     * @var array
     */
    private $required;

    /**
     * Missing params
     *
     * @var array
     */
    private $missing;

    /**
     * Set the required params
     *
     * @param array $required
     * @return $this
     */
    public function setRequiredParams(array $required)
    {
        $this->required = $required;
        return $this;
    }

    /**
     * Set the missing params
     *
     * @param array $missing
     * @return $this
     */
    public function setMissingParams(array $missing)
    {
        $this->missing = $missing;
        return $this;
    }

    /**
     * Return a list of the required params for the command
     *
     * @return array
     */
    public function getRequiredParams()
    {
        return $this->required;
    }

    /**
     * Get the missing params
     *
     * @return array
     */
    public function getMissingParams()
    {
        return $this->missing;
    }
}