<?php

namespace MobilityWork\Service\Zendesk;

use MobilityWork\Entity\Language;
use MobilityWork\Mock\Zendesk\API\HttpClient as ZendeskAPI;
//use Zendesk\API\HttpClient as ZendeskAPI;

abstract class Ticket
{
    const LIST_FLUSH = true;
    const LIST_NO_FLUSH = false;

    const LANGUAGE = '80918708';
    const TYPE = '80924888';


    protected User|null $user = null;
    protected string $subject = '';
    protected string $message = '';

    protected string $priority = 'normal';
    protected string $type = 'question';
    protected string $status = 'new';
    protected array $customFields = [];

    public function __construct(
        User|null $user = null,
        Language $language = null,
        string|null $message = null,
        string $subject = ''
    )
    {
        $this->setUser($user);
        $this->setMessage($message);
        $this->setSubject($subject);
        $this->addCustomFields(self::LANGUAGE, $language->getName());
    }

    // Getter
    public function getUser(): ?User
    {
        return $this->user;
    }
    public function getSubject(): ?string
    {
        return $this->subject;
    }
    public function getMessage(): ?string
    {
        return $this->message;
    }
    public function getPriority(): ?string
    {
        return $this->priority;
    }
    public function getType(): ?string
    {
        return $this->type;
    }
    public function getStatus(): ?string
    {
        return $this->status;
    }
    public function getCustomFields(): ?array
    {
        return $this->customFields;
    }
    // Setter
    public function setUser(User|null $data = null): self
    {
        $this->user = $data;

        return $this;
    }
    public function setSubject(string|null $data = null): self
    {
        $this->subject = $data;
        $this->setSubjectDefault();
        return $this;
    }
    protected function setSubjectDefault() {
        if (empty($this->getSubject()) && !empty($this->getMessage())) {
            $this->setSubject(
                strlen($this->getMessage()) > 50
                    ? substr($this->getMessage(), 0, 50) . '...'
                    : $this->getMessage()
            );
        }
    }
    public function setMessage(string|null $data = null): self
    {
        $this->message = $data;
        $this->setSubjectDefault();

        return $this;
    }
    public function setPriority(string$data): self
    {
        $this->priority = $data;

        return $this;
    }
    public function setType(string $data): self
    {
        $this->type = $data;

        return $this;
    }
    public function setStatus(string $data): self
    {
        $this->status = $data;

        return $this;
    }
    public function setCustomFields(array $data, bool $flush = self::LIST_NO_FLUSH): self
    {
        if ($flush) {
            $this->flushCustomFields();
        }
        foreach($data as $key => $value) {
            $this->addCustomFields($key, $value);
        }

        return $this;
    }
    public function flushCustomFields() {
        $this->customFields = [];
    }
    public function addCustomFields(string|int $key, $value): self
    {
        $this->customFields[$key] = $value;

        return $this;
    }
    public function removeCustomFields(string|int $key): self
    {
        unset($this->customFields[$key]);

        return $this;
    }

    protected function format()
    {
        return [
            'requester_id' => $this->getUser()->getId(),
            'subject' => $this->getSubject(),
            'comment' =>
                [
                    'body' => $this->getMessage()
                ],
            'priority' => $this->getPriority(),
            'type' => $this->getType(),
            'status' => $this->getStatus(),
            'custom_fields' => $this->getCustomFields()
        ];
    }
    public function save(ZendeskAPI $client) : self
    {
        try {
            $client->tickets()->create($this->format());
        } catch (\Exception $e) {
            $this->getLogger()->addError(var_export($this->getUser()->getId(), true));
            throw new \Exception('Failed to add Zendesk Ticket from ' . static::class, 500, $e);
        }

        return $this;
    }
}