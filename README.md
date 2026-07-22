# AXIRO BE Core

Repo này chứa phần nền backend Laravel dùng chung cho các sản phẩm AXIRO: repository, controller/request/resource primitives, permission helpers, activity log helpers, notification metadata và các contract cross-module.

## Vai trò

- Tách API foundation từ code thật đang vận hành trong `mylands-api`, không build lại kiến trúc mới nếu dự án chính đã có base/helper tương ứng.
- Khi một primitive đủ ổn định, port ngược vào `mylands-api` hoặc publish Composer package theo version rõ ràng.
- Không đưa service nghiệp vụ cụ thể của khách hàng/app hiện tại vào repo này nếu chưa tách được contract dùng chung.

## Nguyên tắc port-back

1. API core mini dùng cấu trúc và namespace `App\\...` giống Laravel app chính để dễ song hành với `mylands-api`.
2. App hiện tại chỉ adopt phần đã đủ rõ contract.
3. Không tạo request/controller kế thừa rỗng chỉ để “dùng core”.
4. Validation tiếng Việt, permission, audit/log vẫn nằm ở app consumer nếu phụ thuộc nghiệp vụ cụ thể.

## Trạng thái hiện tại

- Đã có `BaseRepositoryInterface`.
- Đã có `BaseRepository` nền cho scope allowed, filter/sort/paginate và find theo query đã scope.
- Đã có `BaseCrudService` nền cho list/detail từ query đã scope.
- Đã có `RequestRuleSet` để compose validation rules/messages/attributes dùng chung, tránh tạo FormRequest kế thừa rỗng.
- Đã có `ModuleRouteDefinition` và `ModuleRouteRegistry` để chuẩn hóa route metadata trước khi bridge sang ModuleRouteBuilder của app.
- Đã có contract `OrganizationLevel`, `OrganizationContext`, `ModuleCapability`, `ModuleCapabilityRegistry`.
- Đã có `DefaultModuleCapabilities` làm catalog seed cho các module nền platform, không nhét sẵn business/HR/kế toán.
- Chạy `composer check` để lint cú pháp core và validate registry capability.
- Chưa publish Composer package.

## Contract bắt buộc

API core phải hiểu cùng edition/cấp tổ chức với FE. Capability chỉ xác định module có được bật theo gói không; permission, validation và activity/audit log vẫn phải kiểm ở action nghiệp vụ thật.
