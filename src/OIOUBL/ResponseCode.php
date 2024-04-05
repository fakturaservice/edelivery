<?php

namespace Fakturaservice\Edelivery\OIOUBL;

abstract class ResponseCode
{

    //OIOUBL
    const TechnicalReject   = "TechnicalReject";
    const ProfileReject     = "ProfileReject";
    const BusinessAccept    = "BusinessAccept";
    const BusinessReject    = "BusinessReject";

    //PEPPOL
    const MessageAcknowledgement = "AB";
    const Accepted = "AP";
    const Rejected = "RE";
}