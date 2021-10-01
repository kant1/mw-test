<?php

namespace MobilityWork\Mock;

use MobilityWork\Entity\Reservation;

class Basic
{
    public $user = null;
    public $id = 'USER_ID';

    public function get($type) {
        return [
            'zendesk' => [
                'username' => 'ZENDESK_USER',
                'token' => 'ZENDESK_TOKEN',
                'subdomain' => 'ZENDESK_SUBDOMAIN'
            ]
        ];
    }

    public function getByRef($ref)
    {
        return new Reservation();
    }

    public function create($data)
    {
        $this->dump($data);
    }

    public function createOrUpdate($data)
    {
        $this->dump($data);
        $reponse = new Basic();
        $reponse->user = new Basic();
        return $reponse;
    }

    private function dump($data)
    {
        var_dump($data);
    }
}