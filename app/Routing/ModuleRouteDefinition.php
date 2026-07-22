<?php

namespace App\Routing;

final readonly class ModuleRouteDefinition
{
    /**
     * @param  array<int, string>  $middleware
     * @param  array<string, mixed>  $options
     */
    public function __construct(
        public string $module,
        public string $prefix,
        public string $controller,
        public ?string $resource = null,
        public array $middleware = [],
        public array $options = [],
    ) {
    }

    /**
     * Chuẩn hóa khai báo route module từ config để app có thể build route nhất quán.
     *
     * @param  array<string, mixed>  $config
     */
    public static function fromArray(array $config): self
    {
        if (empty($config['module'])) {
            throw new \InvalidArgumentException('Module route definition requires module.');
        }

        if (empty($config['prefix'])) {
            throw new \InvalidArgumentException('Module route definition requires prefix.');
        }

        if (empty($config['controller'])) {
            throw new \InvalidArgumentException('Module route definition requires controller.');
        }

        return new self(
            module: (string) $config['module'],
            prefix: (string) $config['prefix'],
            controller: (string) $config['controller'],
            resource: $config['resource'] ?? null,
            middleware: array_values($config['middleware'] ?? []),
            options: $config['options'] ?? [],
        );
    }

    /**
     * Xuất route definition về array để bridge sang ModuleRouteBuilder của app Laravel hiện tại.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'module' => $this->module,
            'resource' => $this->resource,
            'prefix' => $this->prefix,
            'controller' => $this->controller,
            'middleware' => $this->middleware,
            'options' => $this->options,
        ];
    }
}
