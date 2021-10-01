<?php

namespace MobilityWork\Entity;

class Reservation
{
    public function getHotel(): ?Hotel
    {
        return new Hotel();
    }
}