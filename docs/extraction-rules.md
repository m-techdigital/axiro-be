# Core Extraction Rules

Tài liệu này là quy tắc bắt buộc khi tách backend core từ `mylands-api` sang `axiro-be`.

## Nguồn chuẩn

- `mylands-api` là nguồn chuẩn ban đầu cho controller/service/repository/request/route/helper đang chạy thật.
- `axiro-be` không được tự thiết kế lại flow CRUD, permission, validation, audit nếu dự án chính đã có pattern dùng được.
- Core chỉ chứa platform primitives. Nghiệp vụ như HR, Accounting, CRM ở app consumer hoặc package business riêng.

## Quy trình tách

1. Chọn một pattern đang lặp trong `mylands-api` như CRUD list/detail/action, relation snapshot log, FormRequest rule set.
2. Rà các module thật đã dùng pattern đó để xác định API ổn định.
3. Tách phần nền sang core bằng interface/base class hoặc helper composition.
4. Không tạo lớp kế thừa rỗng. Nếu lớp con không thêm rule/logic gì thì dùng trực tiếp base/helper.
5. Áp dụng lại vào một module thật của `mylands-api` để kiểm chứng trước khi mở rộng.

## Không được làm

- Không đưa business service lớn vào core.
- Không bypass permission bằng capability.
- Không hardcode label/color enum ở BE nếu FE có enum option/render tương ứng.
- Không trả lỗi validate bằng key tiếng Anh khi đã có thể đưa attribute/message vào `validation.php` của app.

## Checklist trước khi commit

- Đã đối chiếu source thật trong `mylands-api`.
- Permission, validation, audit/log vẫn được kiểm ở action nghiệp vụ.
- Method quan trọng có comment ngắn bằng tiếng Việt.
- `composer check` chạy được hoặc ghi rõ lý do chưa chạy.
