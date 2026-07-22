<?php

namespace App\Support;

final readonly class ModuleCapability
{
    /**
     * @param  array<int, OrganizationLevel|string>  $availableIn
     */
    public function __construct(
        public string $key,
        public string $label,
        public ModuleLayer $layer = ModuleLayer::BusinessCore,
        public array $availableIn = [],
        public array $scopeFields = [],
        public PermissionMode $permissionsMode = PermissionMode::ScopedPermissions,
    ) {
    }

    // Tạo capability từ config array để app consumer có thể khai báo trong config/modules.
    public static function fromArray(string $key, array $config): self
    {
        return new self(
            key: $key,
            label: (string) ($config['label'] ?? $key),
            layer: ModuleLayer::tryFrom((string) ($config['layer'] ?? '')) ?? ModuleLayer::BusinessCore,
            availableIn: $config['available_in'] ?? $config['availableIn'] ?? OrganizationLevel::values(),
            scopeFields: array_values($config['scope_fields'] ?? $config['scopeFields'] ?? []),
            permissionsMode: PermissionMode::tryFrom((string) ($config['permissions_mode'] ?? $config['permissionsMode'] ?? '')) ?? PermissionMode::ScopedPermissions,
        );
    }

    // Capability chỉ xác định gói có bật module hay không, không thay thế permission/action policy.
    public function availableFor(OrganizationLevel|string $level): bool
    {
        $value = $level instanceof OrganizationLevel ? $level->value : $level;

        return in_array($value, array_map(
            static fn ($item) => $item instanceof OrganizationLevel ? $item->value : $item,
            $this->availableIn ?: OrganizationLevel::values(),
        ), true);
    }

    // UI phân quyền chỉ nên lộ với mode scoped; API vẫn kiểm permission ở mọi mode.
    public function shouldExposePermissionUi(OrganizationLevel|string $level): bool
    {
        return $this->availableFor($level)
            && $this->permissionsMode === PermissionMode::ScopedPermissions;
    }

    // Kiểm tra field scope được phép xuất hiện; core không hardcode company/department/team.
    public function supportsScopeField(string $field): bool
    {
        return in_array($field, $this->scopeFields, true);
    }

    /**
     * @return array<int, string>
     */
    public function validationErrors(): array
    {
        $errors = [];
        $levels = array_map(
            static fn ($item) => $item instanceof OrganizationLevel ? $item->value : $item,
            $this->availableIn ?: OrganizationLevel::values(),
        );

        if ($this->key === '') {
            $errors[] = 'Module capability requires a key.';
        }

        foreach ($this->scopeFields as $field) {
            if (!is_string($field) || $field === '') {
                $errors[] = "Module {$this->key} has invalid scope field.";
            }
        }

        foreach ($levels as $level) {
            if (!in_array($level, OrganizationLevel::values(), true)) {
                $errors[] = "Module {$this->key} has invalid organization level: {$level}.";
            }
        }

        return $errors;
    }
}
