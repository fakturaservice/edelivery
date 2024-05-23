<?php

use Fakturaservice\Edelivery\NemLookUpCli;
use Fakturaservice\Edelivery\OIOUBL\EndpointID;
use Fakturaservice\Edelivery\OIOUBL\NetworkType;
use Fakturaservice\Edelivery\util\Logger;

require_once __DIR__ . '/../vendor/autoload.php';

//putenv('APP_ENV=prod');
putenv('APP_ENV=dev');

try {
    $printSeparator = "*****************************************************************************\n";

    $environment    = getenv('APP_ENV');
    $configuration  = parse_ini_file(__DIR__ . "/config.ini", true);
    $debugLevel     = $configuration[$environment]["debugLevel"];
    $log            = new Logger($debugLevel);
    $log->setChannel(basename(__FILE__));

    $lookupCli   = new NemLookUpCli(new Logger($debugLevel));

    echo "\n$printSeparator";
    $endpointAddr = readline("* Enter the endpoint address: ");
    $endpointAddrArr    = explode(":", $endpointAddr);
    if(EndpointID::getId($endpointAddrArr[0]) == $endpointAddrArr[0])
        die("* ERROR: '$endpointAddrArr[0]' is not a legal prefix\n$printSeparator\n\n");

    echo "* schemeID: " . EndpointID::getId($endpointAddrArr[0]) . "\n";

    echo $printSeparator . "\n";
    $documentTypeInv    = NemLookUpCli::BUSINESS_SCOPE_DOC_ID_IDENTIFIER_BUSDOX . "::" . NemLookUpCli::BUSINESS_SCOPE_INSTANCE_IDENTIFIER_INV;
    $documentTypeCre    = NemLookUpCli::BUSINESS_SCOPE_DOC_ID_IDENTIFIER_BUSDOX . "::" . NemLookUpCli::BUSINESS_SCOPE_INSTANCE_IDENTIFIER_CRE;
    $documentTypeMlr    = NemLookUpCli::BUSINESS_SCOPE_DOC_ID_IDENTIFIER_BUSDOX . "::" . NemLookUpCli::BUSINESS_SCOPE_INSTANCE_IDENTIFIER_MLR;
    $documentTypeOrdRes = NemLookUpCli::BUSINESS_SCOPE_DOC_ID_IDENTIFIER_BUSDOX . "::" . NemLookUpCli::BUSINESS_SCOPE_INSTANCE_IDENTIFIER_ORD_RES;

    $isPeppol   =   $invPeppol = $lookupCli->lookupEndpointPeppol($endpointAddr, $httpCode, $documentTypeInv);
    $isPeppol   |=  $crePeppol = $lookupCli->lookupEndpointPeppol($endpointAddr, $httpCode, $documentTypeCre);
    $isPeppol   |=  $OrdResPeppol = $lookupCli->lookupEndpointPeppol($endpointAddr, $httpCode, $documentTypeOrdRes);

    $isNHR      = $lookupCli->lookupEndpoint($endpointAddr, $httpCode);
    echo "* Endpoint {$endpointAddr} is " . ($isPeppol?"":"*NOT* ") . "registered on PEPPOL SML(" . ($invPeppol?"Inv ":"") . ($crePeppol?"Cre ":"") . ($OrdResPeppol?"ResOrd ":"") . ")\n";
    echo "* Endpoint {$endpointAddr} is " . ($isNHR?"":"*NOT* ") . "registered on NHR SML\n";

    echo $printSeparator;
    if($isNHR) {
        $networkTypeIDs = array_unique($lookupCli->getNetworkTypeIds($endpointAddr, null));
        $participant = $lookupCli->getParticipant();
        echo "* Participant:\n";

        $Key = $participant["Key"] ?? "";
        echo "* \tKey:      $Key\n";

        $KeyType = $participant["KeyType"] ?? "";
        echo "* \tKeyType:  $KeyType\n";

        $UnitCVR = $participant["UnitCVR"] ?? "";
        echo "* \tUnitCVR:  $UnitCVR\n";

        $UnitName = $participant["UnitName"] ?? "";
        echo "* \tUnitName: $UnitName\n";
        echo "* \n";
        echo "* Compatible networkTypeIDs:\n";
        foreach ($networkTypeIDs as $networkTypeID) {
            $ownerService = $lookupCli->getOwnerServices($endpointAddr, $networkTypeID);
            echo "* \t" . NetworkType::getName($networkTypeID) . "\n";
            echo "* \t\tOwnerService:\n";
            $EndpointReference = $ownerService["EndpointReference"] ?? "";
            echo "* \t\t\tEndpointReference:    $EndpointReference\n";

            $ContactName = $ownerService["ContactName"] ?? "";
            echo "* \t\t\tContactName:          $ContactName\n";

            $ContactEmail = $ownerService["ContactEmail"] ?? "";
            echo "* \t\t\tContactEmail:         $ContactEmail\n";

            $DisplayName = $ownerService["DisplayName"] ?? "";
            echo "* \t\t\tDisplayName:          $DisplayName\n";
            echo "* \n";
            $profileIDs = $lookupCli->getProfileNames($endpointAddr, $networkTypeID, "Customer");
            echo "* \t\tCompatible 'Customer' profiles:\n";
            foreach ($profileIDs as $profileID) {
                echo "* \t\t\t{$profileID}\n";
            }
            echo "*\n";
            $profileIDs = $lookupCli->getProfileNames($endpointAddr, $networkTypeID, "Supplier");
            echo "* \t\tCompatible 'Supplier' profiles:\n";
            foreach ($profileIDs as $profileID) {
                echo "* \t\t\t{$profileID}\n";
            }
            echo "*\n";
        }
        echo $printSeparator;
    }

}
catch (\Exception $e) {}