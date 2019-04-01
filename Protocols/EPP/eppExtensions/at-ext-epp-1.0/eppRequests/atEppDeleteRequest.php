<?php
namespace Metaregistrar\EPP;


class atEppDeleteRequest extends eppDeleteRequest
{
    use atEppCommandTrait;

    protected $atEppExtensionChain = null;

    function __construct($deleteinfo, atEppExtensionChain $atEppExtensionChain=null) {
        $this->atEppExtensionChain = $atEppExtensionChain;
        parent::__construct($deleteinfo);
        $this->validateExtensionChain($atEppExtensionChain);
        $this->setAtExtensions();
        $this->addSessionId();
    }

    /**
     * Validates the extension parameter against the allowed values;
     *
     * @param $atEppExtensionChain
     * @throws \atEppException, If the the request contained an invalid parameter.
     */
    protected function validateExtensionChain($atEppExtensionChain)
    {
        if (
            $atEppExtensionChain != null &&
            (
                strcmp($atEppExtensionChain['schedule_date'], atEppConstants::domainDeleteScheduleNow) ||
                strcmp($atEppExtensionChain['schedule_date'], atEppConstants::domainDeleteScheduleExpiration)
            )

        ) return;

        throw new \atEppException(
            "Invalid parameter for schedule date in domain delete request extension. Must be either \n" .
            "Value must be either" . atEppConstants::domainDeleteScheduleNow . " or " . atEppConstants::domainDeleteScheduleExpiration
        );
    }
}