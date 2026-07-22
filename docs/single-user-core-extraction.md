# Single-user Platform Core Extraction

Tài liệu này là checklist bắt buộc khi tách API core từ `mylands-api`. Core phải là Laravel mini app chạy thật, giữ cấu trúc dự án chính, rồi lọc nghiệp vụ nhiều người dùng. Không tạo namespace/base/request/controller mới nếu dự án chính đã có.

## Định hướng sản phẩm

- Core phục vụ một người dùng chính, phù hợp làm nền cho nhiều dự án nhỏ hoặc gói cá nhân.
- ADMIN mặc định full quyền; permission engine có thể tồn tại ở nền nhưng không bắt buộc người dùng cấu hình quyền.
- Không có phòng ban, đội nhóm, giao việc giữa nhiều người, người xử lý, người duyệt theo cấp tổ chức.
- Company/workspace nếu còn dùng chỉ là thông tin đối tác hoặc ngữ cảnh dữ liệu, không phải cây tổ chức.
- Core phải có API kiểm chứng được: auth, user/profile, settings, notification, audit, files, comments, import/export, report builder base và một CRUD mẫu.

## Chiến lược copy-filter

Ưu tiên copy từ dự án chính trước, sau đó lọc theo whitelist/blacklist. Không viết lại service/controller khi dự án chính đã có base hoặc helper tương ứng.

### Giữ nguyên cấu trúc

- `app/Http/Controllers`
- `app/Http/Requests`
- `app/Http/Resources`
- `app/Models`
- `app/Repositories`
- `app/Routes`
- `app/Services`
- `app/Support`
- `app/Helpers`
- `database/migrations`
- `database/seeders`
- `routes`

Nếu cần tối ưu base, phải giữ API tương thích với dự án chính hoặc cập nhật đồng thời ở dự án chính.

### Base bắt buộc giữ

- `BaseRequest`
- `BaseRepository`
- `BaseRepositoryInterface`
- `BaseModelController`
- `ModuleRouteBuilder`
- Model traits/log helpers
- Validation tiếng Việt trong `validation.php`
- Permission engine ở mức nền
- Activity/audit log helpers
- Notification metadata/helpers

### Module core giai đoạn đầu

- Auth/user/profile cơ bản
- Settings/options/enums
- Notifications
- Audit/activity log
- Files/documents base
- Comments/mentions
- Import/export base
- Report builder base
- Calendar/task cá nhân nếu không kéo theo assignment nhiều người
- Một CRUD mẫu dùng `BaseRepository`, `BaseRequest`, resource và route builder

### Loại khỏi Platform Core

- Department/team/company-member organization tree
- HR/employee đa nhân sự
- Accounting/CRM/business domain sâu
- Approval đa cấp
- Scoped permission UI/phân quyền theo member/team/department
- Các field mặc định: `department_id`, `team_id`, `assigned_to`, `approver_id`, `company_member_id`
- Các action giao việc, duyệt hộ, chuyển tiếp, escalate theo cấp tổ chức

Những phần này sẽ đi vào edition/branch cao hơn, không nằm ở core single-user ban đầu.

## Quy tắc BE

- Validate phải nằm trong `FormRequest` hoặc base request; không viết rule rải rác trong controller.
- Attribute validate phải có tiếng Việt trong `validation.php`, chữ đầu viết hoa khi hiển thị.
- Action thay đổi dữ liệu phải log đủ: ai làm, làm gì, trước/sau, relation label thay vì chỉ ID.
- Permission phải kiểm ở BE ngay cả khi UI ẩn.
- Không tạo request kế thừa rỗng nếu không bổ sung rule/message/authorize riêng.
- Service/controller quá lớn phải tách theo resource hoặc action group khi đi qua.

## Quy tắc đồng bộ ngược

Mọi tối ưu phát sinh trong core phải có khả năng áp dụng ngược lại `mylands-api`. Nếu core sửa base/repository/request/helper, cần ghi rõ:

- Vì sao sửa.
- API có đổi không.
- Module nào ở dự án chính cần port lại.
- Rủi ro khi port ngược.

## Kiểm tra tối thiểu

- Laravel boot được.
- Route core không gọi controller/model đã loại.
- Auth/user/profile hoạt động.
- CRUD mẫu tạo/sửa/xóa/xem được.
- FormRequest trả 422 với key và label tiếng Việt đúng.
- Activity/audit log ghi được action thay đổi dữ liệu.
- Notification/file/comment/report/import-export có API nền dùng được.
