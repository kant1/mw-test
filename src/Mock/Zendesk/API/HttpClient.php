<?php

namespace MobilityWork\Mock\Zendesk\API;

use MobilityWork\Mock\Basic;

class HttpClient
{
    public function setAuth($authType, $attr)
    {
        return true;
    }

    public function tickets(): Basic
    {
        return new Basic();
    }

    public function users(): Basic
    {
        return new Basic();
    }

}