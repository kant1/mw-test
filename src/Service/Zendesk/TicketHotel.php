<?php

namespace MobilityWork\Service\Zendesk;

use MobilityWork\Entity\Language;

class TicketHotel extends Ticket
{
    const  HOTEL_NAME = '80918668';
    const  CITY = '80918648';

    public function __construct(
        User|null $user = null,
        Language $language = null,
        string|null $message = null,
        string|null $subject = ''
    )
    {
        parent::__construct($user, $language, $message, $subject);
        $this->addCustomFields(self::TYPE, 'hotel');
    }
}