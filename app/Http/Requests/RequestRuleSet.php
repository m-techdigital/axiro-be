<?php

namespace App\Http\Requests;

final readonly class RequestRuleSet
{
    /**
     * @param  array<string, mixed>  $rules
     * @param  array<string, string>  $attributes
     * @param  array<string, string>  $messages
     */
    public function __construct(
        public array $rules = [],
        public array $attributes = [],
        public array $messages = [],
    ) {
    }

    /**
     * Tạo rule set dùng chung để FormRequest nghiệp vụ chỉ compose rule, không kế thừa rỗng.
     *
     * @param  array<string, mixed>  $rules
     * @param  array<string, string>  $attributes
     * @param  array<string, string>  $messages
     */
    public static function make(array $rules = [], array $attributes = [], array $messages = []): self
    {
        return new self($rules, $attributes, $messages);
    }

    /**
     * Gộp nhiều rule set theo thứ tự, rule sau được override rule trước khi cùng field.
     */
    public function merge(self ...$sets): self
    {
        $rules = $this->rules;
        $attributes = $this->attributes;
        $messages = $this->messages;

        foreach ($sets as $set) {
            $rules = array_replace($rules, $set->rules);
            $attributes = array_replace($attributes, $set->attributes);
            $messages = array_replace($messages, $set->messages);
        }

        return new self($rules, $attributes, $messages);
    }

    /**
     * Thêm prefix cho nhóm field dạng payload.* hoặc items.* để request con không tự nối chuỗi thủ công.
     */
    public function prefixed(string $prefix): self
    {
        $prefix = trim($prefix, '.');

        if ($prefix === '') {
            return $this;
        }

        return new self(
            rules: $this->prefixKeys($this->rules, $prefix),
            attributes: $this->prefixKeys($this->attributes, $prefix),
            messages: $this->prefixMessageKeys($this->messages, $prefix),
        );
    }

    /**
     * Xuất về array tương thích FormRequest để app consumer có thể dùng trực tiếp.
     *
     * @return array{rules: array<string, mixed>, attributes: array<string, string>, messages: array<string, string>}
     */
    public function toArray(): array
    {
        return [
            'rules' => $this->rules,
            'attributes' => $this->attributes,
            'messages' => $this->messages,
        ];
    }

    /**
     * @param  array<string, mixed>  $items
     * @return array<string, mixed>
     */
    private function prefixKeys(array $items, string $prefix): array
    {
        $result = [];

        foreach ($items as $key => $value) {
            $result["{$prefix}.{$key}"] = $value;
        }

        return $result;
    }

    /**
     * @param  array<string, string>  $items
     * @return array<string, string>
     */
    private function prefixMessageKeys(array $items, string $prefix): array
    {
        $result = [];

        foreach ($items as $key => $value) {
            $result["{$prefix}.{$key}"] = $value;
        }

        return $result;
    }
}
