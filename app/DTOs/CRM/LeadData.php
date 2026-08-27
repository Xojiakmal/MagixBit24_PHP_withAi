<?php

namespace App\DTOs\CRM;

class LeadData
{
    public function __construct(
        public string $title,
        public ?int $contact_id = null,
        public string $status = 'yangi',
        public ?int $assigned_to = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            title: $data['title'],
            contact_id: $data['contact_id'] ?? null,
            status: $data['status'] ?? 'yangi',
            assigned_to: $data['assigned_to'] ?? null,
        );
    }
}
