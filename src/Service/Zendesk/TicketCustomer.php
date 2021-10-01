<?php

namespace MobilityWork\Service\Zendesk;

use MobilityWork\Entity\Language;
use MobilityWork\Entity\Reservation;
use MobilityWork\Entity\Hotel;

class TicketCustomer extends Ticket
{
    const TYPE = '80924888';
    // RESERVATION
    const RESERVATION_ROOM_NAME = '80531287';
    const RESERVATION_BOOKED_DATE = '80531307';
    const RESERVATION_ROOM_PRICE = '80924568';
    const RESERVATION_BOOKED_PERIOD = '80918728';
    const RESERVATION_NUMBER = '80531327';
    // HOTEL
    const  HOTEL_CONTACT_EMAIL = '80531267';
    const  HOTEL_NAME = '80918668';
    const  HOTEL_ADDRESS= '80918648';


    protected Reservation|null $reservation = null;
    protected Hotel|null $hotel = null;

    public function __construct(
        User|null $user = null,
        Language $language = null,
        string|null $message = null,
        string $subject = '',
        Reservation|null $reservation,
        Hotel|null $hotel
    )
    {
        parent::__construct($user, $language, $message, $subject);
        $this->addCustomFields(self::TYPE, 'customer');
        $this->setReservation($reservation);
        $this->setHotel($hotel);
    }
    // Getter
    public function getReservation(): ?Reservation
    {
        return $this->reservation;
    }
    public function getHotel(): ?Hotel
    {
        return $this->hotel;
    }
    // Setter
    public function setReservation(Reservation|null $data = null): self
    {
        $this->reservation = $data;
        if (!is_a($data, Reservation::class)) {
            $this->addCustomFields(self::RESERVATION_NUMBER, $data->number)
                ->addCustomFields(self::RESERVATION_ROOM_NAME, $data->getRoom()->getName() . ' ('.$data->getRoom()->getType().')')
                ->addCustomFields(self::RESERVATION_BOOKED_DATE, $data->getBookedDate()->format('Y-m-d'))
                ->addCustomFields(self::RESERVATION_ROOM_PRICE, $data->getRoomPrice().' '.$data->getHotel()->getCurrency()->getCode())
                ->addCustomFields(self::RESERVATION_BOOKED_PERIOD, $data->getBookedStartTime()->format('H:i').' - '.$data->getBookedEndTime()->format('H:i'));
        } else {
            $this->removeCustomFields(self::RESERVATION_NUMBER)
                ->removeCustomFields(self::RESERVATION_ROOM_NAME)
                ->removeCustomFields(self::RESERVATION_BOOKED_DATE)
                ->removeCustomFields(self::RESERVATION_ROOM_PRICE)
                ->removeCustomFields(self::RESERVATION_BOOKED_PERIOD);
        }

        return $this;
    }
    public function setHotel(Hotel|null $data = null): self
    {
        $this->hotel = $data;
        if (!is_a($data, Hotel::class)) {
            $hotelContact = $this->getServiceManager()->get('service.hotel_contacts')->getMainHotelContact($data);
            $this->addCustomFields(self::HOTEL_CONTACT_EMAIL, $hotelContact != null ? $hotelContact->getEmail() : null)
                ->addCustomFields(self::HOTEL_NAME, $data->getName())
                ->addCustomFields(self::HOTEL_ADDRESS, $data->getAddress());
        } else {
            $this->removeCustomFields(self::HOTEL_CONTACT_EMAIL)
                ->removeCustomFields(self::HOTEL_NAME)
                ->removeCustomFields(self::HOTEL_ADDRESS);
        }

        return $this;
    }
}