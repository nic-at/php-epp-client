<?php
/**
 * Created by PhpStorm.
 * User: martinha
 * Date: 10/04/2019
 * Time: 09:17
 */

namespace Metaregistrar\EPP;

use atEppException;

/**
 * Class atEppWithdrawRequest represents the at-registry specific withdraw request where the responsibility of
 * a domain is delegated back to nic.at
 *
 * @package Metaregistrar\EPP
 */
class atEppWithdrawRequest extends eppRequest
{
    use atEppCommandTrait;

    function __construct(eppDomain $withdrawDomain, bool $zoneDeletion) {
        parent::__construct();

        $this->setDomain($withdrawDomain, $zoneDeletion);
        $this->addSessionId();
    }

    function __destruct() {
        parent::__destruct();
    }

    public function setDomain(eppDomain $domain, bool $zoneDeleteion) {
        if (!strlen($domain->getDomainname())) {
            throw new atEppException('atEppWithdrawRequest domain object does not contain a valid domain name');
        }

        /* Creating the withdraw object */
        $this->domainobject = $this->createElement('withdraw');

        $domainWithdraw = $this->createElement('domain:withdraw');
        $domainWithdraw->setAttribute('xmlns:xsi', atEppConstants::namespaceAtExtDomain);
        $domainWithdraw->setAttribute('xsi:schemaLocation', atEppConstants::schemaLocationAtExt);

        $domainWithdraw->appendChild($this->createElement('domain:name', $domain->getDomainname()));
        $domainWithdraw->appendChild($this->createElement('domain:zd')->setAttribute('value', $zoneDeleteion));

        $this->domainobject->appendChild($domainWithdraw);
        $this->getCommand()->appendChild($this->domainobject);
    }

}