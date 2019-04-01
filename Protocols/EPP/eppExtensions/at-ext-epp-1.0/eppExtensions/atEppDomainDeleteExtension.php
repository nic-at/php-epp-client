<?php
/**
 * Created by PhpStorm.
 * User: martinha
 * Date: 01/04/2019
 * Time: 14:56
 */

namespace Metaregistrar\EPP;


class atEppDomainDeleteExtension extends atEppExtensionChain
{
    /*
    |--------------------------------------------------------------------------
    | atEppDomainDeleteExtension
    |--------------------------------------------------------------------------
    |
    | Adds the at-exz-domain:scheduledate extension.
    | This can be either 'now' or 'expiration'
    |
    */
    protected $suExtArguments=[];

    /**
     * Domain extension part of the atEppExtensionChain
     *
     * @param array $domainExtArguments
     * @param atEppExtensionChain|null $additionalEppExtension
     */
    public function __construct(array $domainExtArguments=[], atEppExtensionChain $additionalEppExtension=null)
    {
        if (!is_null($additionalEppExtension))
        {
            parent::__construct($additionalEppExtension);
        }
        $this->suExtArguments= $domainExtArguments;
    }

    /**
     * Extends the atEppExtensionChain by a delete schedule-date element.
     *
     * @param eppRequest $request
     * @param \DOMElement $extension
     */
    public function setEppRequestExtension(eppRequest $request, \DOMElement $extension)
    {
        $atDomainFacets = $request->createElement('at-ext-domain:delete');
        $atDomainFacets->setAttribute('xmlns:at-ext-domain', atEppConstants::namespaceAtExtDomain);
        $atDomainFacets->setAttribute('xsi:schemaLocation', atEppConstants::schemaLocationAtExtDomain);

        if (isset($this->suExtArguments['schedule_date']))
        {
            /* No attributre name for schedule-date, see: http://www.nic.at/xsd/at-ext-domain-1.0 */
            $scheduleDate = $request->createElement('at-ext-domain:scheduledate');
            $scheduleDate->appendChild(new \DOMText($this->suExtArguments['schedule_date']));
        }

        $extension->appendchild($atDomainFacets);

        if (!is_null($this->additionalEppExtension))
        {
            $this->additionalEppExtension->setEppRequestExtension($request, $extension);
        }
    }
}