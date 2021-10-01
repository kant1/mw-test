<?php

namespace MobilityWork\Service\Zendesk;

use MobilityWork\Mock\Zendesk\API\HttpClient as ZendeskAPI;
//use Zendesk\API\HttpClient as ZendeskAPI;
use MobilityWork\Service\AbstractService;
use MobilityWork\Entity\Language;
use MobilityWork\Entity\Reservation;
use MobilityWork\Entity\Hotel;
use MobilityWork\Entity\DomainConfig;

class ZendeskService extends AbstractService
{

    /**
     * This constant need to be in the config management service and pass as an envar during the run phase
     * Idea : store it in $this->getServiceManager()->get('Config')['zendesk']['secret']
     *
     * Reason:
     * - Maintenability : We can change this token without code change (test, build, deploy...)
     * - Responsability : This constant isn't used at this level (may be on the AbstractService class)
     * - Security : Everybody having access to the code can use this production token
     */
    const PRODUCTION_SECRET_TOKEN = '7a960781b588403ca32116048238d01c';

    /** CHANGES **/
    /**
     * - Add comment before methods signatures
     *  - Documentation auto-generation (phpdoc format)
     *  - Comprehension
     *
     * - Add arguments type on every signatures
     *  - Compatibility with next PHP versions (>8.0)
     *  - Clarity
     *  - Less check to do on the method core
     *
     * - Create "mock" to be able to test the class
     *
     * - Use some model for User and ticket action
     * - Dependencies injection to save model
     *  - More testable
     */

    /** Optimization **/
    /**
     * - Add default value on method signatures attributs (more easy to use)
     *
     * - Make the $client property real singleton (static) that mean Config must accessible in static
     * - Move $client property definition on AbstractService
     * - Factorize more the behavior (need more standardize argument)
     */

    protected ZendeskAPI|null $client = null;

    protected function getClient() : ZendeskAPI
    {
        if (!is_a($this->client, ZendeskAPI::class)) {
            $this->client = new ZendeskAPI($this->getServiceManager()->get('Config')['zendesk']['subdomain']);
            $this->client->setAuth(
                'basic',
                [
                    'username' => $this->getServiceManager()->get('Config')['zendesk']['username'],
                    'token' => $this->getServiceManager()->get('Config')['zendesk']['token']
                ]
            );
        }
        return $this->client;
    }

    /**
     * @param string $gender
     * @param string $firstName
     * @param string $lastName
     * @param string $phoneNumber
     * @param string $email
     * @param string $message
     * @param string $reservationNumber
     * @param Hotel $hotel
     * @param Language $language
     * @param DomainConfig $domainConfig
     *
     * @return boolean
     */
    final public function createCustomerTicket(
        string $gender,
        string $firstName,
        string $lastName,
        string $phoneNumber,
        string $email,
        string $message,
        string $reservationNumber,
        Hotel|null $hotel = null,
        Language $language,
        DomainConfig $domainConfig): bool
    {
        $user = $this->populateUser(
                    $gender,
                    $firstName,
                    $lastName,
                    $phoneNumber,
                    $email
                )->save($this->getClient());



        $reservation = $this->populateReservation($reservationNumber);
        $hotel = (!is_a($hotel, Hotel::class) &&
                    is_a($reservation, Reservation::class))
                ? $reservation->getHotel()
                : $hotel;

        $ticket = new TicketCustomer(
            $user,
            $language,
            $message,
            '',
            $reservation,
            $hotel
        );

        $ticket->save($this->getClient());

        return true;
    }


    /**
     * @param string $gender
     * @param string $firstName
     * @param string $lastName
     * @param string $country
     * @param string $phoneNumber
     * @param string $email
     * @param string $city
     * @param string $website
     * @param string $hotelName
     * @param string $subject
     * @param string $message
     * @param Language $language
     * @param DomainConfig $domainConfig
     *
     * @return boolean
     */
    final public function createHotelTicket(
        string $gender,
        string $firstName,
        string $lastName,
        string $country,
        string $phoneNumber,
        string $email,
        string $city,
        string $website,
        string $hotelName,
        string $subject,
        string $message,
        Language $language,
        DomainConfig $domainConfig): bool
    {
        $user = $this->populateUser(
            $gender,
            $firstName,
            $lastName,
            $phoneNumber,
            $email,
            $country,
            'end-user',
            [
                'website' => $website
            ]
        )->save($this->getClient());

        $ticket = (new TicketHotel($user, $language, $message, $subject))
            ->setCustomFields([
                TicketHotel::HOTEL_NAME => $hotelName,
                TicketHotel::CITY => $city
            ]
        );

        $ticket->save($this->getClient());

        return true;
    }

    /**
     * @param string $gender
     * @param string $firstName
     * @param string $lastName
     * @param string $country
     * @param string $phoneNumber
     * @param string $email
     * @param string $city
     * @param string $media
     * @param string $subject
     * @param string $message
     * @param Language $language
     * @param DomainConfig $domainConfig
     *
     * @return boolean
     */
    final public function createPressTicket(
        string $gender,
        string $firstName,
        string $lastName,
        string $country,
        string $phoneNumber,
        string $email,
        string $city,
        string $media,
        string $subject,
        string $message,
        Language $language,
        DomainConfig $domainConfig): bool
    {
        $user = $this->populateUser(
            $gender,
            $firstName,
            $lastName,
            $phoneNumber,
            $email,
            $country,
            'end-user',
            [
                'press_media' => $media
            ]
        )->save($this->getClient());

        $ticket = (new TicketPress($user, $language, $message, $subject))
            ->setCustomFields([
                    TicketPress::CITY => $city
                ]
            );

        $ticket->save($this->getClient());

        return true;
    }

    /**
     * @param string $gender
     * @param string $firstName
     * @param string $lastName
     * @param string $phoneNumber
     * @param string $email
     * @param string $message
     * @param Language $language
     * @param DomainConfig $domainConfig
     *
     * @return boolean
     */
    final public function createPartnersTicket(
        string $gender,
        string $firstName,
        string $lastName,
        string $phoneNumber,
        string $email,
        string $message,
        Language $language,
        DomainConfig $domainConfig): bool
    {

        $user = $this->populateUser(
            $gender,
            $firstName,
            $lastName,
            $phoneNumber,
            $email
        )->save($this->getClient());

        $ticket = (new TicketPartner($user, $language, $message));

        $ticket->save($this->getClient());

        return true;
    }

    private function populateUser(
        string $gender,
        string $firstName,
        string $lastName,
        string $phoneNumber,
        string $email,
        string $country = null,
        string $role = 'end-user',
        array $userFields = []
    ) : User
    {
        return (new User())
            ->setGender($gender)
            ->setFirstName($firstName)
            ->setLastName($lastName)
            ->setPhone($phoneNumber)
            ->setEmai($email)
            ->setCountry($country)
            ->setRole($role)
            ->setUserFields($userFields);
    }

    private function populateReservation(string $reservationNumber): ?Reservation
    {
        $reservation = null;
        if (!empty($reservationNumber)) {
            $reservation = $this->getEntityRepository('Reservation')->getByRef($reservationNumber);
        }
        return $reservation;
    }
}