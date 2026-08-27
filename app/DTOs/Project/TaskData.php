<?php

namespace App\DTOs\Project;

class TaskData
{
    public function __construct(
        public string $title,
        public ?int $deal_id = null,
        public ?string $description = null,
        public string $status = 'to_do',
        public string $priority = 'medium',
        public ?string $start_date = null,
        public ?string $due_date = null,
        public int|array|null $assigned_to = null,
        public int|array|null $assigned_to_department_id = null,
        public bool $assigned_to_everyone = false,
        public ?int $created_by = null,
        public ?array $observers = null,
        public bool $available_to_everyone = false,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            title: $data['title'],
            deal_id: $data['deal_id'] ?? null,
            description: $data['description'] ?? null,
            status: $data['status'] ?? 'to_do',
            priority: $data['priority'] ?? 'medium',
            start_date: $data['start_date'] ?? null,
            due_date: $data['due_date'] ?? null,
            assigned_to: $data['assigned_to'] ?? null,
            assigned_to_department_id: $data['assigned_to_department_id'] ?? null,
            assigned_to_everyone: $data['assigned_to_everyone'] ?? false,
            created_by: $data['created_by'] ?? null,
            observers: $data['observers'] ?? null,
            available_to_everyone: $data['available_to_everyone'] ?? false,
        );
    }
}
