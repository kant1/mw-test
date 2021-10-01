<?php

namespace MobilityWork\Service\Zendesk;

use MobilityWork\Entity\Language;

class TicketPartner extends Ticket
{
    public function __construct(
        User|null $user = null,
        Language $language = null,
        string|null $message = null,
        string $subject = ''
    )
    {
        parent::__construct($user, $language, $message, $subject);
        $this->addCustomFields(self::TYPE, 'partner');
    }
}