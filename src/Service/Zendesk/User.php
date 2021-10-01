<?php

namespace MobilityWork\Service\Zendesk;

use MobilityWork\Mock\Zendesk\API\HttpClient as ZendeskAPI;

class User
{
    const LIST_FLUSH = true;
    const LIST_NO_FLUSH = false;

    protected string|null $id = null;
    protected string|null $gender = '';
    protected string|null $email = '';
    protected string|null $firstName = '';
    protected string|null $lastName = '';
    protected string|null $phone = '';
    protected string|null $country = '';
    protected string|null $role = 'end-user';
    protected array $userFields = [];

    // Getter
    public function getId(): string
    {
        return $this->id;
    }

    public function getGender(): string
    {
        return $this->gender;
    }
    public function getEmail(): string
    {
        return $this->email;
    }
    public function getFirstName(): string
    {
        return $this->firstName;
    }
    public function getLastName(): string
    {
        return $this->lastName;
    }
    public function getPhone(): string
    {
        return $this->phone;
    }
    public function getCountry(): string
    {
        return $this->country;
    }
    public function getRole(): string
    {
        return $this->role;
    }
    public function getUserFields(): ?array
    {
        return $this->userFields;
    }
    // Setter
    public function setGender(string $data): self
    {
        $this->gender = $data;

        return $this;
    }
    public function setEmai(string|null $data): self
    {
        $this->email = $data;

        return $this;
    }
    public function setFirstName(string|null $data): self
    {
        $this->firstName = $data;

        return $this;
    }
    public function setLastName(string|null $data): self
    {
        $this->lastName = strtoupper($data);

        return $this;
    }
    public function setPhone(string|null $data): self
    {
        $this->phone = $data;

        return $this;
    }
    public function setCountry(string|null $data): self
    {
        $this->country = $data;

        return $this;
    }
    public function setRole(string|null $data): self
    {
        $this->role = $data;

        return $this;
    }
    public function setUserFields(array $data, bool $flush = self::LIST_NO_FLUSH): self
    {
        if ($flush) {
            $this->flushUserFields();
        }
        foreach($data as $key => $value) {
            $this->addUserFields($key, $value);
        }

        return $this;
    }
    public function flushUserFields() {
        $this->userFields = [];
    }
    public function addUserFields(string|int $key, $value): self
    {
        $this->userFields[$key] = $value;

        return $this;
    }
    public function removeUserFields(string|int $key): self
    {
        unset($this->userFields[$key]);

        return $this;
    }

    protected function format(): array
    {
        return [
            'email' => $this->getEmail(),
            'name' => $this->getFirstName().' '.$this->getLastName(),
            'phone' => $this->getPhone(),
            'role' => $this->getRole(),
            'user_fields' => $this->getUserFields()
        ];
    }
    public function save(ZendeskAPI $client) : self
    {
        try {
            $response = $client->users()->createOrUpdate($this->format());
            $this->id = $response->user->id;
        } catch (\Exception $e) {
            $this->getLogger()->addError(var_export($this->getMail(), true));
            throw new \Exception('Failed to add Zendesk User', 500, $e);
        }
        return $this;
    }
}