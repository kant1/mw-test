<?php

namespace MobilityWork\Service\Zendesk;

use MobilityWork\Entity\Language;

class TicketPress extends Ticket
{
    const  CITY = '80918648';

    public function __construct(
        User|null $user = null,
        Language $language = null,
        string|null $message = null,
        string $subject = ''
    )
    {
        parent::__construct($user, $language, $message, $subject);
        $this->addCustomFields(self::TYPE, 'press');
    }
}