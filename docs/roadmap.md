# API Core Roadmap

Mục tiêu của API core là tạo một Laravel mini app nền chạy được, copy từ `mylands-api` rồi lọc xuống mô hình một người dùng. Core không phải library trừu tượng và không được lệch namespace/folder so với dự án chính.

## Ưu tiên gần

1. Copy cấu trúc Laravel thật từ `mylands-api` sang branch core, loại trừ `vendor`, `storage`, `.env`, cache.
2. Giữ nguyên base/helper đang có: `BaseRequest`, `BaseRepository`, `BaseModelController`, `ModuleRouteBuilder`, traits, helpers, validation tiếng Việt.
3. Lọc route/controller/service/model/migration theo Platform Core single-user.
4. Loại bỏ schema/validate/action liên quan team, department, assigned user, approver, member role, scoped permission UI nếu không cần cho core.
5. Giữ permission engine ở nền, nhưng seed ADMIN full quyền và không yêu cầu cấu hình quyền phức tạp.
6. Giữ audit/activity/notification/file/comment/import-export/report builder base ở mức chạy thật.
7. Sau mỗi nhóm lọc, rà route/list request/import và chạy check nhẹ.

## Mốc core có thể chạy thật

Core hoàn chỉnh giai đoạn đầu phải có một Laravel mini app có thể kiểm thử:

- Auth/user/profile cơ bản.
- Permission engine ở mức nền, ADMIN full quyền, UI quyền có thể ẩn ở FE.
- Settings/enums/options.
- Audit log, notification, files, comments/mentions base.
- Một CRUD mẫu dùng BaseRepository/BaseService/BaseRequest/route registry.

## Không làm vội

- Không đưa `AccountingService`, `HrOperationsService` hoặc service business đa người vào Platform Core.
- Không định nghĩa `department_id`, `team_id`, `assigned_to`, `approved_by` nếu core không có nghiệp vụ đó.
- Không tạo controller/service/request base mới nếu dự án chính đã có base tương ứng.
- Không biến core thành package không có route/API chạy thật.
