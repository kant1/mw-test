<?php

use MobilityWork\Entity\DomainConfig;
use MobilityWork\Entity\Hotel;
use MobilityWork\Entity\Language;

require_once __DIR__.'/vendor/autoload.php';

$zendesk = (new MobilityWork\Service\Zendesk\ZendeskService())->createCustomerTicket(
        'GENDER',
        'FIRSTNAME',
        'LASTNAME',
        'PHONENUMBER',
        'EMAIL',
        'MESSAGE',
        'RESERVATIONNUMBER',
        null,
        new Language(),
        new DomainConfig()
    );
var_dump($zendesk);

$zendesk = (new MobilityWork\Service\Zendesk\ZendeskService())->createHotelTicket(
    'GENDER',
    'FIRSTNAME',
    'LASTNAME',
    'country',
    'PHONENUMBER',
    'EMAIL',
    'CITY',
    'WEBSITE',
    'HOTELNAME',
    'SUBJECT',
    'MESSAGE',
    new Language(),
    new DomainConfig()
);
var_dump($zendesk);

$zendesk = (new MobilityWork\Service\Zendesk\ZendeskService())->createPressTicket(
    'GENDER',
    'FIRSTNAME',
    'LASTNAME',
    'country',
    'PHONENUMBER',
    'EMAIL',
    'CITY',
    'MEDIA',
    'SUBJECT',
    'MESSAGE',
    new Language(),
    new DomainConfig()
);
var_dump($zendesk);

$zendesk = (new MobilityWork\Service\Zendesk\ZendeskService())->createPartnersTicket(
    'GENDER',
    'FIRSTNAME',
    'LASTNAME',
    'PHONENUMBER',
    'EMAIL',
    'MESSAGE',
    new Language(),
    new DomainConfig()
);
var_dump($zendesk);