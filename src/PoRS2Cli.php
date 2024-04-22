<?php

namespace Fakturaservice\Edelivery;

use DateTime;
use DOMDocument;
use DOMXPath;
use Exception;
use Fakturaservice\Edelivery\{
    OIOUBL\EndpointID,
    util\Logger,
    util\LoggerInterface
};

class PoRS2Cli
{
    const PARTICIPANT_ADD_TEMPL_PATH    = __DIR__ . "/resources/UpdateParticipantTemplate.xml";
    const PORS_URL                      = "https://registrationservice.nemhandel.dk/nemhandel-pors/";
    const GRP_ID_NemHandel_RASP         = 88110;//109399;
    const GRP_ID_NemHandel_AS4          = 109391;
    const GRP_ID_PEPPOL_AS4             = 109391;
    const SRVC_ID_NemHandel_RASP        = 146061;
    const SRVC_ID_NemHandel_AS4         = 143843;
    const SRVC_ID_PEPPOL_AS4            = 279441;

    private Logger $_log;
    private string $_sslCertPath;
    private string $_sslCertPW;
    private ?array $_participant;
    private DateTime $_today;
    private string $_className;

    /**
     * @throws Exception
     */
    public function __construct(string $sslCertPath, string $sslCertPW, LoggerInterface $logger, int $debugLevel=0)
    {
        $this->_className           = basename(str_replace('\\', '/', get_called_class()));
        $this->_today               = new DateTime();
        $this->_log                 = $logger;//new Logger("PoRS2Cli", $debugLevel);
        $this->_sslCertPath         = $sslCertPath;
        $this->_sslCertPW           = $sslCertPW;
        $this->_participant         = null;

        $this->_log->setChannel($this->_className);
        $this->_log->setLogLevel($debugLevel);
    }

    /**
     * @throws Exception
     */
    public function getGroupId()
    {
        echo $this->get("rest/group/list", $httpCode);
    }


    /**
     * @param $endpoint
     * @param $response
     * @return bool
     * @throws Exception
     */
    private function findParticipant($endpoint, &$response): bool
    {
        if($this->cvrValidateDK($endpoint))
        {
            $endpointType = EndpointID::DK_CVR;
        }
        else if($this->glnValidateDK($endpoint))
        {
            $endpointType = EndpointID::GLN;
        }
        else
        {
            $this->_log->log("Endpoint $endpoint is not a valid endpoint", Logger::LV_1, Logger::LOG_ERR);
            return false;
        }

        $response   = $this->get("rest/participant/$endpointType/$endpoint", $httpCode);
        $response   = trim($response);

        switch($httpCode)
        {
            case "200":
            {
                if(!$this->extractParticipantFromResponse($response))
                {
                    $this->_log->log("Failed to interpret response", Logger::LV_1, Logger::LOG_ERR);
                    return false;
                }
                $this->_log->log("HTTP Status: OK ");
                return true;
            }
            case "404":
            {
                $this->_log->log("HTTP Status: No network type found for endpoint: $endpoint ", Logger::LV_1, Logger::LOG_WARN);
                return false;
            }
            case "405":
            {
                $this->_log->log("HTTP Status: Method not allowed ", Logger::LV_1, Logger::LOG_ERR);
                return false;
            }
            case "409":
            {
                $this->_log->log("HTTP Status: Conflict, if the recipient cannot be uniquely identified. ", Logger::LV_1, Logger::LOG_ERR);
                return false;
            }
            case "500":
            {
                $this->_log->log("HTTP Status: Internal server error ", Logger::LV_1, Logger::LOG_ERR);
                return false;
            }
            default:
            {
                $this->_log->log("HTTP Status: $httpCode ", Logger::LV_1, Logger::LOG_ERR);
                return false;
            }
        }
    }

    /**
     * @param $endpoint
     * @param $unitName
     * @param $unitCVR
     * @return bool
     * @throws Exception
     */
    public function addParticipant($endpoint, $unitName, $unitCVR): bool
    {
        $requestString = $this->createAddParticipantRequestPayload($endpoint, $unitName, $unitCVR);
        $this->_log->log("Created XML:\n\n$requestString");
        $response = $this->put("rest/participant", $httpCode, $requestString);

        switch($httpCode)
        {
            case "200":
            {
                $this->_log->log("HTTP Status: OK, No content ");
                return true;
            }
            case "201":
            {
                $this->_log->log("HTTP Status: Created, with location header set with URI to the created Participant. ");
                return true;
            }
            case "401":
            {
                $this->_log->log("HTTP Status: Unauthorized ", Logger::LV_1, Logger::LOG_ERR);
                $this->_log->log("Response:\n\n$response ", Logger::LV_1, Logger::LOG_ERR);
                return false;
            }
            case "409":
            {
                $this->_log->log("HTTP Status: Conflict, validation error ", Logger::LV_1, Logger::LOG_ERR);
                $this->_log->log("Response:\n\n$response ", Logger::LV_1, Logger::LOG_ERR);
                return false;
            }
            case "415":
            {
                $this->_log->log("HTTP Status: Unsupported media type ", Logger::LV_1, Logger::LOG_ERR);
                return false;
            }
            case "500":
            {
                $this->_log->log("HTTP Status: Internal server error ", Logger::LV_1, Logger::LOG_ERR);
                return false;
            }
            default:
            {
                $this->_log->log("HTTP Status: $httpCode ", Logger::LV_1, Logger::LOG_ERR);
                return false;
            }
        }
    }

    /**
     * @throws Exception
     */
    public function removeParticipant($endpoint): bool
    {
        if(!$this->findParticipant($endpoint, $response))
        {
            $this->_log->log("Failed finding '$endpoint'", Logger::LV_1, Logger::LOG_ERR);
            return false;
        }

        if(($this->_participant["ParticipantId"] == 0))
        {
            $this->_log->log("Failed to interpret response", Logger::LV_1, Logger::LOG_ERR);
            return false;
        }
        else
        {
            if($this->delete("rest/participant/{$this->_participant["ParticipantId"]}", $httpCode) === false)
                return false;
            switch($httpCode)
            {
                case "200":
                {
                    $this->_log->log("HTTP Status: OK, No content ");
                    return true;
                }
                case "401":
                {
                    $this->_log->log("HTTP Status: Unauthorized ", Logger::LV_1, Logger::LOG_ERR);
                    return false;
                }
                case "404":
                {
                    $this->_log->log("HTTP Status: Not found ", Logger::LV_1, Logger::LOG_WARN);
                    return false;
                }
                case "409":
                {
                    $this->_log->log("HTTP Status: Conflict, validation error ", Logger::LV_1, Logger::LOG_ERR);
                    $this->_log->log("Response:\n\n$response ", Logger::LV_1, Logger::LOG_ERR);
                    return false;
                }
                case "500":
                {
                    $this->_log->log("HTTP Status: Internal server error ", Logger::LV_1, Logger::LOG_ERR);
                    return false;
                }
                default:
                {
                    $this->_log->log("HTTP Status: $httpCode ", Logger::LV_1, Logger::LOG_ERR);
                    return false;
                }
            }
        }
    }

    /**
     * @throws Exception
     */
    private function createAddParticipantRequestPayload($endpoint, $unitName, $unitCVR):string
    {
        $participantDocument = new DOMDocument();
        $participantDocument->loadXML(file_get_contents(self::PARTICIPANT_ADD_TEMPL_PATH));

        // Find the <Key> element and set its value to $endpoint
        $keyElement = $participantDocument->getElementsByTagName('Key')->item(0);
        if ($keyElement !== null)
            $keyElement->nodeValue = $endpoint;

        if($this->cvrValidateDK($endpoint))
        {// Find the <KeyType> element and set its value to "DK:CVR"
            $keyTypeElement = $participantDocument->getElementsByTagName('KeyType')->item(0);
            if ($keyTypeElement !== null)
                $keyTypeElement->nodeValue = EndpointID::DK_CVR;
        }
        else if($this->glnValidateDK($endpoint))
        {// Find the <KeyType> element and set its value to "GLN"
            $keyTypeElement = $participantDocument->getElementsByTagName('KeyType')->item(0);
            if ($keyTypeElement !== null)
                $keyTypeElement->nodeValue = EndpointID::GLN;
        }
        else
        {
            $this->_log->log("Failed adding '$endpoint' as a participant", Logger::LV_1, Logger::LOG_ERR);
            return "";
        }
        // Find the <UnitName> element and set its value to $unitName
        $UnitNameElement = $participantDocument->getElementsByTagName('UnitName')->item(0);
        if ($UnitNameElement !== null)
            $UnitNameElement->nodeValue = htmlspecialchars($unitName, ENT_XML1, 'UTF-8');

        // Find the <UnitCVR> element and set its value to $unitName
        $UnitCVRElement = $participantDocument->getElementsByTagName('UnitCVR')->item(0);
        if ($UnitCVRElement !== null)
            $UnitCVRElement->nodeValue = $unitCVR;

        // Find the <ActivationDate> element and set its value to $this->_today
        $ActivationDateElement = $participantDocument->getElementsByTagName('ActivationDate');//->item(0);
        foreach ($ActivationDateElement as $element)
        {
            $element->nodeValue = $this->_today->format("Y-m-d\T00:00:00\+01:00");
        }

        // Save the modified XML back to a string
        return $participantDocument->saveXML();
    }

    /**
     * @throws Exception
     */
    public function getUnitName($endpoint=null): string
    {
        if(($this->_participant === null) && ($endpoint !== null))
        {
            if(!$this->findParticipant($endpoint, $response))
            {
                $this->_log->log("Failed finding '$endpoint'", Logger::LV_1, Logger::LOG_ERR);
                return false;
            }
        }
        return $this->_participant["UnitName"]??"";
    }

    /**
     * @throws Exception
     */
    public function getContactName($endpoint=null): string
    {
        if(($this->_participant === null) && ($endpoint !== null))
        {
            if(!$this->findParticipant($endpoint, $response))
            {
                $this->_log->log("Failed finding '$endpoint'", Logger::LV_1, Logger::LOG_ERR);
                return false;
            }
        }
        return $this->_participant["ContactName"]??"";
    }

    /**
     * @throws Exception
     */
    public function getContactEmail($endpoint=null): string
    {
        if(($this->_participant === null) && ($endpoint !== null))
        {
            if(!$this->findParticipant($endpoint, $response))
            {
                $this->_log->log("Failed finding '$endpoint'", Logger::LV_1, Logger::LOG_ERR);
                return false;
            }
        }
        return $this->_participant["ContactEmail"]??"";
    }
    public function getParticipantBindings($networkTypeId): array
    {
        if(!isset($this->_participant["ParticipantBindings"]))
            return [];
        $filteredArray = array_filter($this->_participant["ParticipantBindings"], function ($item) use ($networkTypeId) {
            return isset($item["NetworkTypeId"]) && $item["NetworkTypeId"] === $networkTypeId;
        });

        return reset($filteredArray);
    }

    /**
     * @throws Exception
     */
    public function getEndpointReference($networkTypeId, $endpoint=null): string
    {
        if(($this->_participant === null) && ($endpoint !== null))
        {
            if(!$this->findParticipant($endpoint, $response))
            {
                $this->_log->log("Failed finding '$endpoint'", Logger::LV_1, Logger::LOG_ERR);
                return false;
            }
        }
        if(!isset($this->_participant["ParticipantBindings"]))
            return "";
        $filteredArray = array_values(array_filter($this->_participant["ParticipantBindings"], function ($item) use ($networkTypeId) {
            return isset($item["NetworkTypeId"]) && $item["NetworkTypeId"] == $networkTypeId;
        }));
        
        return $filteredArray[0]["EndpointReference"]??"";
    }
    public function success(): bool
    {
        return $this->_log->success();
    }
    public function getErrorMsg() : string
    {
        return $this->_log->getErrorMsg();
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
        $sum += substr ($cvr, $a++, 1) * 2;

        $wholes = floor (($sum / 11));
        $remainder = ($sum - ($wholes * 11));

        $lastDigit = ($remainder) ? (11 - $remainder) : 0;

        $ret = ($lastDigit == substr($cvr, 7, 1));

        $this->_log->log(
            "'$cvr' is " . ($ret?"":"NOT"). " a valid CVR",
            ($ret?Logger::LV_2:Logger::LV_1),
            ($ret?Logger::LOG_OK:Logger::LOG_WARN));

        return $ret;
    }

    /**
     * @throws Exception
     */
    public function glnValidateDK($gln): int
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
            ($ret?Logger::LV_2:Logger::LV_1),
            ($ret?Logger::LOG_OK:Logger::LOG_WARN));

        return $ret;
    }

    /**
     * @throws Exception
     */
    private function get($api, &$httpCode)
    {
        $httpHeader = [];

        $this->_log->log("http header:");
        $this->_log->log($httpHeader);

        $curl   = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => self::PORS_URL . $api,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => $httpHeader,
            CURLOPT_SSL_VERIFYPEER => true, // Verify server's certificate
            CURLOPT_SSLCERTTYPE => 'P12',   // Set certificate type to P12 (PFX)
            CURLOPT_SSLCERT => $this->_sslCertPath, // Path to your PFX file
            CURLOPT_SSLCERTPASSWD => $this->_sslCertPW, // Password for the PFX file
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
    private function put(string $api, &$httpCode, $payload=null):string
    {
        $httpHeader     = (isset($payload)?["Content-Type: text/xml"]:[]);

        $this->_log->log("http header:");
        $this->_log->log($httpHeader);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => self::PORS_URL . $api,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'PUT',
            CURLOPT_POSTFIELDS => ($payload??""),
            CURLOPT_HTTPHEADER => $httpHeader,
            CURLOPT_SSL_VERIFYPEER => true, // Verify server's certificate
            CURLOPT_SSLCERTTYPE => 'P12',   // Set certificate type to P12 (PFX)
            CURLOPT_SSLCERT => $this->_sslCertPath, // Path to your PFX file
            CURLOPT_SSLCERTPASSWD => $this->_sslCertPW, // Password for the PFX file
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
    private function delete($api, &$httpCode)
    {
        $httpHeader = [];

        $this->_log->log("http header:");
        $this->_log->log($httpHeader);

        $curl   = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => self::PORS_URL . $api,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'DELETE',
            CURLOPT_HTTPHEADER => $httpHeader,
            CURLOPT_SSL_VERIFYPEER => true, // Verify server's certificate
            CURLOPT_SSLCERTTYPE => 'P12',   // Set certificate type to P12 (PFX)
            CURLOPT_SSLCERT => $this->_sslCertPath, // Path to your PFX file
            CURLOPT_SSLCERTPASSWD => $this->_sslCertPW, // Password for the PFX file
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
    private function extractParticipantFromResponse($response): bool
    {
        unset($this->_participant);

        $dom = new DOMDocument();
        if (!$dom->loadXML($response))
        {
            $this->_log->log("No xml response", Logger::LV_1, Logger::LOG_ERR);
            return false;
        }

        $xpath = new DOMXPath($dom);
        $xpath->registerNamespace('ns', $dom->documentElement->namespaceURI);

        $participantBindingNodes = $xpath->query('//ns:ParticipantBindings/ns:ParticipantBinding');
        foreach ($participantBindingNodes as $participantBindingNode)
        {
            $NetworkTypeId      = $xpath->query('./ns:NetworkTypeId', $participantBindingNode)->item(0);
            $DisplayName        = $xpath->query('./ns:OwnerService/ns:DisplayName', $participantBindingNode)->item(0);
            $EndpointReference  = $xpath->query('./ns:OwnerService/ns:EndpointReference', $participantBindingNode)->item(0);
            $this->_participant["ParticipantBindings"][] = [
                "NetworkTypeId"     => $NetworkTypeId?$NetworkTypeId->textContent:0,
                "DisplayName"       => $DisplayName?$DisplayName->textContent:"",
                "EndpointReference" => $EndpointReference?$EndpointReference->textContent:"",
            ];
        }

        $success = true & $this->extractNodeVal($xpath, '//ns:Participant/ns:Key', "Key");
        $success = $success & $this->extractNodeVal($xpath, '//ns:Participant/ns:KeyType', "KeyType");
        $success = $success & $this->extractNodeVal($xpath, '//ns:Participant/ns:UnitCVR', "UnitCVR");
        $success = $success & $this->extractNodeVal($xpath, '//ns:Participant/ns:Id', "ParticipantId");
        $this->extractNodeVal($xpath, '//ns:Participant/ns:OwnerBusinessId', "OwnerBusinessId");
        $this->extractNodeVal($xpath, '//ns:Participant/ns:UnitName', "UnitName");
        $this->extractNodeVal($xpath, '//ns:Participant/ns:Group/ns:Id', "GroupId");
        $this->extractNodeVal($xpath, '//ns:ContactName', "ContactName");
        $this->extractNodeVal($xpath, '//ns:ContactEmail', "ContactEmail");

        return $success;
    }

    /**
     * @throws Exception
     */
    private function extractNodeVal(&$xpath, $expression, $index): bool
    {
        $node       = $xpath->query($expression)->item(0);
        if ($node)
        {
            $this->_participant[$index] = $node->nodeValue;
            $this->_log->log("$index found: '{$this->_participant[$index]}'");
            return true;
        }
        $this->_log->log("$index was NOT found", Logger::LV_1, Logger::LOG_WARN);
        return false;
    }

}