<?php

namespace Fakturaservice\Edelivery;

use Exception;
use Fakturaservice\Edelivery\{
    util\Logger,
    util\LoggerInterface,
    OIOUBL\NetworkType,
    OIOUBL\EAS,
    OIOUBL\ProfileID,
    OIOUBL\OIOUBL_base
};

class NemLookUpCli
{
    const NEM_API_URL                                   = "https://api.nemhandel.dk/nemhandel-api/";
    const PEPPOL_SML_URL                                = "edelivery.tech.ec.europa.eu/";
    const SCHEME_ID                                     = "iso6523-actorid-upis";
    const BUSINESS_SCOPE_IDENTIFIER                     = "busdox-docid-qns";
    const BUSINESS_SCOPE_INSTANCE_IDENTIFIER_INV        = "urn:oasis:names:specification:ubl:schema:xsd:Invoice-2::Invoice##urn:cen.eu:en16931:2017#compliant#urn:fdc:peppol.eu:2017:poacc:billing:3.0::2.1";
    const BUSINESS_SCOPE_INSTANCE_IDENTIFIER_CRE        = "urn:oasis:names:specification:ubl:schema:xsd:CreditNote-2::CreditNote##urn:cen.eu:en16931:2017#compliant#urn:fdc:peppol.eu:2017:poacc:billing:3.0::2.1";
    const BUSINESS_SCOPE_INSTANCE_IDENTIFIER_INV_RES    = "urn:oasis:names:specification:ubl:schema:xsd:ApplicationResponse-2::ApplicationResponse##urn:fdc:peppol.eu:poacc:trns:invoice_response:3::2.1";
    const BUSINESS_SCOPE_INSTANCE_IDENTIFIER_MLR        = "urn:oasis:names:specification:ubl:schema:xsd:ApplicationResponse-2::ApplicationResponse##urn:fdc:peppol.eu:poacc:trns:mlr:3::2.1";
    const BUSINESS_SCOPE_INSTANCE_IDENTIFIER_ORD_RES    = "urn:oasis:names:specification:ubl:schema:xsd:OrderResponse-2::OrderResponse##urn:fdc:peppol.eu:poacc:trns:order_response:3::2.1";

    private LoggerInterface $_log;
    /**
     * @var mixed
     */
    private $_lookUpEndpoint;
    /**
     * @var mixed
     */
    private $_lookUpCvr;
    private array $_networkTypePriority;
    private array $_profileIdPriority;
    private string $_className;

    /**
     * @throws Exception
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->_className           = basename(str_replace('\\', '/', get_called_class()));
        $this->_log                 = $logger;
        $this->_log->setChannel($this->_className);
        $this->_lookUpEndpoint      = null;
        $this->_lookUpCvr           = null;
        $this->_networkTypePriority = [
            //TODO: Enable this priority when more AS4 endpoints are working
//            NetworkType::NemHandel_AS4,
//            NetworkType::NemHandel_RASP,
//            NetworkType::PEPPOL_AS4,

            NetworkType::NemHandel_RASP,
            NetworkType::NemHandel_AS4,
            NetworkType::PEPPOL_AS4,

//            NetworkType::PEPPOL_AS4,
//            NetworkType::NemHandel_AS4,
//            NetworkType::NemHandel_RASP,
        ];
        $this->_profileIdPriority = [
            ProfileID::procurement_BilSimR_1_0,
            ProfileID::procurement_BilSim_1_0,
            ProfileID::nesubl_profile5_ver2_0,
            ProfileID::reference_Utility_1_0,

            ProfileID::peppol_poacc_bis_mlr_3,

            ProfileID::Peppol_BIS3_Billing,
            ProfileID::peppol_poacc_billing_01_1_0,

            ProfileID::Peppol_BIS3_Invoice_Response,
            ProfileID::peppol_poacc_invoice_response_3,
        ];
    }
    /**
     * @throws Exception
     */
    public function lookupCvr($cvr, &$httpCode)
    {
        $api = "search/lookup/$cvr";

        $lookUpCvr = $this->get($api, $httpCode, 1);
        $this->_lookUpCvr = json_decode($lookUpCvr, true);
    }

    /**
     * @throws Exception
     */
    public function getOwnerServices($endpoint=null, $networkTypeId=null): array
    {
        if(!isset($this->_lookUpEndpoint) && isset($endpoint))
        {
            $this->lookupEndpoint($endpoint, $httpCode);
        }
        $ownerServices = [];
        $this->searchProperty($this->_lookUpEndpoint, "OwnerService", $ownerServices);

        $ownerServices = array_combine(array_column($ownerServices, "NetworkTypeId"), array_values($ownerServices));
        if(NetworkType::getName($networkTypeId) !== "Unknown")
            return $ownerServices[$networkTypeId];
        return $ownerServices;
    }

    /**
     * @throws Exception
     */
    public function getParticipant($endpoint=null, $participantKey=null): array
    {
        if(!isset($this->_lookUpEndpoint) && isset($endpoint))
        {
            $this->lookupEndpoint($endpoint, $httpCode);
        }
        $participant = [];
        $this->searchProperty($this->_lookUpEndpoint, "participant", $participant);

        if(isset($participantKey) && isset($participant[0][$participantKey]))
            return $participant[0][$participantKey];
        return $participant[0]??[];
    }

    /**
     * @throws Exception
     */
    public function getParticipantEndpoint($includeType=true, $cvr = null): array
    {
        if(!isset($this->_lookUpCvr) && isset($cvr))
        {
            $this->lookupCvr($cvr, $httpCode);
        }
        $endpoints = [];
        $this->searchProperty($this->_lookUpCvr, "Key", $endpoints, "participant");

        $keyType = [];
        $this->searchProperty($this->_lookUpCvr, "KeyType", $keyType, "participant");

        if($includeType)
        {
            $endpoints = array_map(function ($a, $b) {
                return EAS::getId($a) . ":" . $b;
            }, $keyType, $endpoints);
        }
        return $endpoints;
    }

    /**
     * @throws Exception
     */
    public function lookupEndpoint($endpoint, &$httpCode): bool
    {
        $this->_log->log("Input NHR endpoint:\t$endpoint", Logger::LV_2);
        $api = "search/networkLookup?" . http_build_query([
                "receiverId"     => $endpoint
            ]);

        $this->_log->log("Calling API:{$api}");
        $lookupEndpoint = $this->get($api, $httpCode);
        $this->_lookUpEndpoint = json_decode($lookupEndpoint, true);
        return !empty($this->_lookUpEndpoint) && !empty($this->_lookUpEndpoint["modtagere"]);
    }

    /**
     * @throws Exception
     */
    public function lookupEndpointPeppol($endpoint, &$httpCode, $documentType=null, &$response = ""): bool
    {
        $this->_log->log("Input PEPPOL endpoint:\t$endpoint", Logger::LV_2);

        $hashOverRecipientID    = "B-" . hash("md5", $endpoint);//B-9b0d086b042f77308607a1d7d1c748c3
        $schemeID               = self::SCHEME_ID;//"iso6523-actorid-upis"
        $SMLDomain              = self::PEPPOL_SML_URL;//"edelivery.tech.ec.europa.eu/";
        $recipientID            = urlencode("{$schemeID}::{$endpoint}");//"iso6523-actorid-upis::0184:40433392"
        $documentType           = (isset($documentType))?("/services/" . urlencode($documentType)):"";
        $api                    = "{$recipientID}{$documentType}";

        $url    = "http://{$hashOverRecipientID}.{$schemeID}.{$SMLDomain}";

        $this->_log->log("Calling URL:{$url}{$api}");
        $response = $this->get($api, $httpCode, 10, $url);

        return ($httpCode == 200);
    }

    /**
     * @throws Exception
     */
    public function getNetworkTypeIds($endpoint = null, $roleName="Customer"): array
    {
        if(!isset($this->_lookUpEndpoint) && isset($endpoint))
        {
            $this->lookupEndpoint($endpoint, $httpCode);
        }
        $roleNames = [];
        $this->searchProperty($this->_lookUpEndpoint, "name", $roleNames, "role");

        $networkTypeIds = [];
        $this->searchProperty($this->_lookUpEndpoint, "networkTypeId", $networkTypeIds);

        if(($roleName === "Customer") || ($roleName === "Supplier"))
        {
            $networkTypeIds = array_intersect_key($networkTypeIds, array_flip(array_keys($roleNames, $roleName)));
        }

        return $this->prioritizeNetworkTypeIds($networkTypeIds);
    }

    /**
     * @throws Exception
     */
    public function getUBLVersion($endpoint): string
    {
        $networkTypeIds = $this->getNetworkTypeIds($endpoint);
        switch ($networkTypeIds[0])
        {
            case NetworkType::NemHandel_RASP:   return OIOUBL_base::UBL_VERSION_2_0;
            default ://If there is no endpoint - default to UBL 2.1
            case NetworkType::NemHandel_AS4:    return OIOUBL_base::UBL_VERSION_2_1;
            case NetworkType::PEPPOL_AS4:       return OIOUBL_base::UBL_PEPPOL_VERSION_EN16931;
        }
    }
    /**
     * @throws Exception
     */
    public function networkTypeCompatible($typeId, $endpoint = null, $roleName="Customer"): bool
    {
        return in_array($typeId, $this->getNetworkTypeIds($endpoint, $roleName));
    }

    /**
     * @throws Exception
     */
    public function getProfileNames($endpoint = null, $networkTypeId=null, $roleName="Customer"): array
    {
        if(!isset($this->_lookUpEndpoint) && isset($endpoint))
        {
            $this->lookupEndpoint($endpoint, $httpCode);
        }
        $roleNames = [];
        $this->searchProperty($this->_lookUpEndpoint, "name", $roleNames, "role");

        $profileNames = [];
        $this->searchProperty($this->_lookUpEndpoint, "name", $profileNames, "profile");

        if($networkTypeId !== null)
        {
            $this->searchProperty($this->_lookUpEndpoint, "networkTypeId", $networkTypeIds);
            if(!empty($networkTypeIds))
            {
                $profileNames = $this->filterArrayByIndexes($profileNames, array_keys($networkTypeIds, $networkTypeId));
            }
        }
        if(($roleName === "Customer") || ($roleName === "Supplier"))
        {
            $profileNames = $this->filterArrayByIndexes($profileNames, array_keys($roleNames, $roleName));
        }

        return $this->prioritizeProfileIds($profileNames);
    }

    private function filterArrayByIndexes($data, $indexes): array {
        $result = array();
        foreach ($indexes as $index) {
            if (array_key_exists($index, $data)) {
                $result[$index] = $data[$index];
            }
        }
        return $result;
    }


        /**
     * @throws Exception
     */
    public function profileNameCompatible($profileName, $endpoint = null, $roleName="Customer"): bool
    {
        return in_array($profileName, $this->getProfileNames($endpoint, null, $roleName));
    }
    private function searchProperty($data, $property, &$result, $searchForParent=null, $parent=null)
    {
        foreach ($data as $key => $value)
        {
            if (($searchForParent === null || $searchForParent === $parent) && ($key === $property) )
            {
                $result[] = $value;
            }
            elseif (is_array($value) || is_object($value))
            {
                $this->searchProperty($value, $property, $result, $searchForParent, $key);
            }
        }
    }

    /**
     * @throws Exception
     */
    private function get($api, &$httpCode, $timeout=0, $url=self::NEM_API_URL)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url . $api,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => $timeout,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);

        if($response === false)
        {
            $curlErr = curl_error($curl);
            $this->_log->log(empty($curlErr)?"No error response received":$curlErr, Logger::LV_1, Logger::LOG_ERR);
            curl_close($curl);
            return false;
        }
        curl_close($curl);
        $this->_log->log("Response code: $httpCode",
            (($httpCode > 201)?Logger::LV_1:Logger::LV_3),
            (($httpCode > 201)?Logger::LOG_WARN:Logger::LOG_OK));
        $this->_log->log("Response: " . (empty($response)?"EMPTY RESPONSE":"\n\n$response\n\n"),
            (($httpCode > 201)?Logger::LV_1:Logger::LV_3),
            (($httpCode > 201)?Logger::LOG_WARN:Logger::LOG_OK));
        return $response;
    }

    /**
     * @throws Exception
     */
    public function cvrValidateDK ($cvr): bool
    {
        $cvr = preg_replace('/[^0-9]/', '', $cvr);
        if (!$cvr || (strlen($cvr) != 8) || ( ((int)$cvr) <  10000000)) return false;

        $sum    = 0;
        $a      = 0;
        $sum += substr ($cvr, $a++, 1) * 2;
        $sum += substr ($cvr, $a++, 1) * 7;
        $sum += substr ($cvr, $a++, 1) * 6;
        $sum += substr ($cvr, $a++, 1) * 5;
        $sum += substr ($cvr, $a++, 1) * 4;
        $sum += substr ($cvr, $a++, 1) * 3;
        $sum += substr ($cvr, $a, 1) * 2;

        $wholes = floor (($sum / 11));
        $remainder = ($sum - ($wholes * 11));

        $lastDigit = ($remainder) ? (11 - $remainder) : 0;

        $ret = ($lastDigit == substr($cvr, 7, 1));

        $this->_log->log(
            "'$cvr' is " . ($ret?"":"NOT"). " a valid CVR",
            ($ret?Logger::LV_3:Logger::LV_1),
            ($ret?Logger::LOG_OK:Logger::LOG_WARN));

        return $ret;
    }

    /**
     * @throws Exception
     */
    public function glnValidateDK($gln): bool
    {
        $gln = preg_replace('/[^0-9]/', '', $gln);
        if (strlen($gln) != 13)
            return false;
        $sum =
            1* $gln[0]+
            3* $gln[1]+
            1* $gln[2]+
            3* $gln[3]+
            1* $gln[4]+
            3* $gln[5]+
            1* $gln[6]+
            3* $gln[7]+
            1* $gln[8]+
            3* $gln[9]+
            1* $gln[10]+
            3* $gln[11];

        $ret = ($gln[12] == (substr((10-($sum%10)), -1)));

        $this->_log->log(
            "'$gln' is " . ($ret?"":"NOT"). " a valid GLN",
            ($ret?Logger::LV_3:Logger::LV_1),
            ($ret?Logger::LOG_OK:Logger::LOG_WARN));

        return $ret;
    }

    /**
     * Sorting function for prioritizing NetworkTypeIds
     *
     * @param array $networkTypeIds
     * @param bool $inputIds
     * @return array
     */
    private function prioritizeNetworkTypeIds(array $networkTypeIds, bool $inputIds=true): array
    {
        if (isset($this->_networkTypePriority))
        {
            $networkTypeIds = array_intersect($networkTypeIds, $this->_networkTypePriority);
            usort($networkTypeIds, function ($a, $b) use ($inputIds)
            {
                return
                    array_search(
                        ($inputIds?$a:NetworkType::getIds($a)),
                        $this->_networkTypePriority) -
                    array_search(
                        ($inputIds?$b:NetworkType::getIds($b)),
                        $this->_networkTypePriority);
            });
        }
        return $networkTypeIds;
    }

    /**
     * Sorting function for prioritizing ProfileIds
     *
     * @param array $profileIds
     * @return array
     */
    private function prioritizeProfileIds(array $profileIds): array
    {
        if (isset($this->_profileIdPriority))
        {
            $profileIds = array_intersect($profileIds, $this->_profileIdPriority);
            usort($profileIds, function ($a, $b)
            {
                return
                    array_search(
                        $a,
                        $this->_profileIdPriority) -
                    array_search(
                        $b,
                        $this->_profileIdPriority);
            });
        }
        return $profileIds;
    }
}