<?php

namespace App\Support;

final readonly class ModuleCapabilityRegistry
{
    /**
     * @param  array<string, ModuleCapability>  $modules
     */
    public function __construct(private array $modules)
    {
    }

    // Tạo registry từ config array để app consumer không phải tự map từng capability.
    public static function fromConfig(array $config): self
    {
        $modules = [];

        foreach ($config as $key => $module) {
            $modules[$key] = ModuleCapability::fromArray($key, $module);
        }

        return new self($modules);
    }

    public function get(string $key): ?ModuleCapability
    {
        return $this->modules[$key] ?? null;
    }

    // Kiểm tra module có được bật theo level tổ chức không; permission vẫn kiểm riêng.
    public function available(string $key, OrganizationLevel|string $level): bool
    {
        return $this->get($key)?->availableFor($level) ?? false;
    }

    // Trả về module nếu khả dụng, giúp app consumer tự quyết định throw exception hay fallback.
    public function availableCapability(string $key, OrganizationLevel|string $level): ?ModuleCapability
    {
        $module = $this->get($key);

        return $module?->availableFor($level) ? $module : null;
    }

    /**
     * @return array<string, ModuleCapability>
     */
    public function availableModules(OrganizationLevel|string $level): array
    {
        return array_filter(
            $this->modules,
            static fn (ModuleCapability $module) => $module->availableFor($level),
        );
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function validationErrors(): array
    {
        $errors = [];

        foreach ($this->modules as $key => $module) {
            $moduleErrors = $module->validationErrors();

            if ($moduleErrors !== []) {
                $errors[$key] = $moduleErrors;
            }
        }

        return $errors;
    }
}
