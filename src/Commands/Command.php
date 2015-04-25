<?php namespace Dxi\Commands;

use Dxi\Dxi;
use Dxi\Exceptions\MissingRequiredParamsException;

abstract class Command {

    /**
     * @var Dxi
     */
    private $dxi;

    /**
     * @var array
     */
    protected $params = [];

    /**
     * @param Dxi $dxi
     */
    public function __construct(Dxi $dxi)
    {
        $this->dxi = $dxi;
    }

    /**
     * @return mixed
     */
    public function fire()
    {
        return $this->dxi->fire($this);
    }

    /**
     * Set params for the command
     *
     * @param array $params
     * @return $this
     */
    public function setParams(array $params)
    {
        $this->params = $params;
        return $this;
    }

    /**
     * Check the required params and return the payload if there are no errors
     *
     * @return array
     */
    public function getPayload()
    {
        $required = $this->getRequiredParams();

        $missingParams = [];

        foreach ($required as $value) {
            if (! is_array($value)) {
                // if an array is passed, at least one of the items must exist
                $value = array($value);
            }

            $passed = false;
            foreach ($value as $requiredParam) {
                if (isset($this->params[$requiredParam])) {
                    $passed = true;
                    break;
                }
            }

            if (! $passed) {
                $missingParams[] = $value;
            }
        }

        if ($missingParams) {
            // flatten the array
            $missing = [];
            array_walk_recursive($missingParams, function($a) use (&$missing) { $missing[] = $a; });

            throw (new MissingRequiredParamsException())
                ->setMissingParams($missing)
                ->setRequiredParams($required);
        }

        return $this->params;
    }

    /**
     * Get the required params
     *
     * @return array
     */
    abstract public function getRequiredParams();

    /**
     * Get the method for the command
     *
     * @return string
     */
    abstract public function getMethod();

    /**
     * Get the action for the command
     *
     * @return string
     */
    abstract public function getAction();

    /**
     * Get the path for the url
     *
     * @return mixed
     */
    abstract public function getUrlPath();
}