<?php
namespace Metaregistrar\EPP;


class atEppTransferRequest extends eppTransferRequest
{
    use atEppCommandTrait;

    protected $atEppExtensionChain = null;

    function __construct($operation, $object,atEppExtensionChain $atEppExtensionChain=null)
    {
        $this->atEppExtensionChain = $atEppExtensionChain;
        parent::__construct($operation, $object);
        $this->setAtExtensions();
        $this->addSessionId();
    }


	public function setDomainRequest(eppDomain $domain) {
		#
		# Object create structure
		#
		$transfer = $this->createElement('transfer');
		$transfer->setAttribute('op', self::OPERATION_REQUEST);
		$this->domainobject = $this->createElement('domain:transfer');
		$this->domainobject->appendChild($this->createElement('domain:name', $domain->getDomainname()));
		if ($domain->getPeriod()) {
			$domainperiod = $this->createElement('domain:period', $domain->getPeriod());
			$domainperiod->setAttribute('unit', eppDomain::DOMAIN_PERIOD_UNIT_Y);
			$this->domainobject->appendChild($domainperiod);
		}
		if (strlen($domain->getAuthorisationCode())) {
			$authinfo = $this->createElement('domain:authInfo');
			$authinfo->appendChild($this->createElement('domain:pw', $domain->getAuthorisationCode()));
			$this->domainobject->appendChild($authinfo);
		}
		$transfer->appendChild($this->domainobject);
		$this->getCommand()->appendChild($transfer);
	}

}