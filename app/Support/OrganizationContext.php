<?php

namespace App\Support;

final readonly class OrganizationContext
{
    public function __construct(
        public OrganizationLevel $level = OrganizationLevel::Personal,
        public int|string|null $workspaceId = null,
        public int|string|null $userId = null,
        public array $scopes = [],
    ) {
    }

    // Chuẩn hóa context từ request/session để service không tự đoán key scope mỗi nơi một kiểu.
    public static function fromArray(array $payload): self
    {
        $level = OrganizationLevel::tryFrom((string) ($payload['level'] ?? '')) ?? OrganizationLevel::Personal;

        return new self(
            level: $level,
            workspaceId: $payload['workspace_id'] ?? $payload['workspaceId'] ?? null,
            userId: $payload['user_id'] ?? $payload['userId'] ?? null,
            scopes: is_array($payload['scopes'] ?? null) ? $payload['scopes'] : [],
        );
    }

    // Payload scope gửi xuống repository/API, bỏ giá trị rỗng để tránh áp scope sai.
    public function scopePayload(): array
    {
        return array_filter([
            'workspace_id' => $this->workspaceId,
            'user_id' => $this->userId,
            ...$this->scopes,
        ], static fn ($value) => $value !== null && $value !== '');
    }
}
