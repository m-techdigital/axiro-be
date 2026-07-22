# API Core Roadmap

Mục tiêu của API core là tách lớp nền từ `mylands-api` đang vận hành, không thiết kế một backend mới lệch khỏi dự án chính. Core phải giúp giảm lặp controller/service/request/repository trong dự án hiện tại, rồi mới trở thành nền cho repo mới.

## Ưu tiên gần

1. Rà `mylands-api/app/Repositories`, `app/Services`, `app/Http/Controllers`, `app/Http/Requests` để xác định pattern đang lặp.
2. Mở rộng `BaseRepositoryInterface` và `BaseRepository` theo CRUD/list/filter/sort/detail primitives dùng thật.
3. Tạo `BaseCrudService`, `BaseController`/action helper khi có ít nhất hai module thật dùng cùng pattern.
4. Tạo request helper cho validate chung, tránh request kế thừa rỗng.
5. Chuẩn hóa activity relation snapshot helper để timeline/log không hiển thị ID thô.
6. Chuẩn hóa module capability metadata phía BE.
7. Chuẩn hóa organization context để service/repository không tự đoán company/department/team scope.

## Mốc core có thể chạy thật

Core hoàn chỉnh giai đoạn đầu phải có một Laravel reference app hoặc skeleton package có thể kiểm thử:

- Auth/user/profile cơ bản.
- Permission engine ở mức nền, có thể ẩn UI ở gói đơn giản nhưng BE vẫn kiểm quyền.
- Settings/enums/options.
- Audit log, notification, files, comments/mentions base.
- Một CRUD mẫu dùng BaseRepository/BaseService/BaseRequest/route registry.

## Không làm vội

- Không đưa `AccountingService`, `HrOperationsService` hoặc service business cụ thể vào core.
- Không tách permission engine nếu app hiện tại còn thay đổi mạnh.
- Không publish package khi chưa có ít nhất một module trong app hiện tại adopt ổn định.
- Không tạo controller/service/request base mới nếu chưa áp dụng được vào module thật trong `mylands-api`.
