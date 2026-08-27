<?php

namespace App\DTOs\CRM;

class DealData
{
    public function __construct(
        public string $title,
        public ?int $contact_id = null,
        public ?int $pipeline_stage_id = null,
        public float $amount = 0.0,
        public ?string $close_date = null,
        public ?int $assigned_to = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            title: $data['title'],
            contact_id: $data['contact_id'] ?? null,
            pipeline_stage_id: $data['pipeline_stage_id'] ?? null,
            amount: (float)($data['amount'] ?? 0.0),
            close_date: $data['close_date'] ?? null,
            assigned_to: $data['assigned_to'] ?? null,
        );
    }
}
