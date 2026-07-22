<?php

namespace App\Routing;

final class ModuleRouteRegistry
{
    /** @var array<string, ModuleRouteDefinition> */
    private array $routes;

    /**
     * @param  array<string, ModuleRouteDefinition>  $routes
     */
    public function __construct(array $routes = [])
    {
        $this->routes = $routes;
    }

    /**
     * Tạo route registry từ config để route files không phải tự normalize thủ công.
     *
     * @param  array<int|string, array<string, mixed>|ModuleRouteDefinition>  $config
     */
    public static function fromConfig(array $config): self
    {
        $routes = [];

        foreach ($config as $key => $definition) {
            $route = $definition instanceof ModuleRouteDefinition
                ? $definition
                : ModuleRouteDefinition::fromArray($definition);

            $routes[is_string($key) ? $key : self::routeKey($route->module, $route->resource)] = $route;
        }

        return new self($routes);
    }

    public static function routeKey(string $module, ?string $resource = null): string
    {
        return $resource ? "{$module}.{$resource}" : $module;
    }

    public function get(string $module, ?string $resource = null): ?ModuleRouteDefinition
    {
        return $this->routes[self::routeKey($module, $resource)] ?? null;
    }

    /**
     * @return array<string, ModuleRouteDefinition>
     */
    public function all(): array
    {
        return $this->routes;
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function toArray(): array
    {
        return array_map(
            fn (ModuleRouteDefinition $definition) => $definition->toArray(),
            $this->routes,
        );
    }
}
