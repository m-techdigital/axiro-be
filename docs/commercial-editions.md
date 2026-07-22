# Commercial Edition Contract

Tài liệu này là contract bắt buộc cho AXIRO API Core khi xây module có thể bật/tắt theo gói sản phẩm.

## Nguyên Tắc

- Capability theo edition chỉ xác định module/gói có được dùng hay không.
- Permission/action policy vẫn là lớp kiểm quyền thật, không được bỏ qua.
- API phải hiểu cùng các cấp `personal`, `team`, `company`, `enterprise` như FE.
- Module chỉ được nhận các field scope đã khai báo trong `scope_fields`; Platform Core không hardcode sẵn `company_id`, `department_id`, `team_id`.
- Core package chỉ cung cấp registry/capability; app consumer quyết định throw `AuthorizationException`, fallback hay ẩn route.

## Module Capability

```php
ModuleCapability::fromArray('accounting', [
    'label' => 'Kế toán',
    'layer' => 'operations_addon',
    'available_in' => ['company', 'enterprise'],
    'scope_fields' => ['company_id'],
    'permissions_mode' => 'scoped_permissions',
]);
```

## Quy Tắc BE

- Không dùng capability thay permission.
- Không tạo request/controller kế thừa rỗng chỉ để “dùng core”.
- Các method quan trọng cần comment ngắn bằng tiếng Việt để người sau hiểu intent.
- Log/audit phải lưu relation label/snapshot thay vì chỉ ghi ID kỹ thuật như `user_id`.
- App consumer nên bắt đầu từ `DefaultModuleCapabilities::config()` hoặc `::registry()`, sau đó override theo sản phẩm thật thay vì tự viết lại metadata từ đầu.
