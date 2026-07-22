# AXIRO BE Core

Repo này là bản backend Platform Core dạng Laravel mini app chạy được, được copy/tách từ `mylands-api` rồi lọc về mô hình một người dùng. Đây không phải library composer rỗng và không được thiết kế lệch cấu trúc dự án chính.

## Vai trò

- Copy từ code thật đang vận hành trong `mylands-api`, giữ cấu trúc Laravel thật, sau đó lọc bỏ nghiệp vụ multi-user/team/department.
- Core phải chạy được như backend nền: auth, user/profile, settings, notification, audit, files, comments, import/export/report base và CRUD mẫu.
- Permission engine có thể tồn tại nhưng UI/luồng phân quyền bị ẩn ở FE; user ADMIN mặc định full quyền.
- Không tự tạo namespace/folder/base mới nếu dự án chính đã có cấu trúc tương ứng.

## Phạm vi single-user core

- Một user chính vận hành hệ thống; không có giao việc giữa người này người kia, người xử lý, người duyệt, team hay phòng ban.
- Company/workspace nếu có chỉ là ngữ cảnh/đối tác dữ liệu, không ép doanh nghiệp phải có cơ cấu tổ chức.
- Những nghiệp vụ đa người như HR vận hành, employee đa nhân sự, approval đa cấp, department/team hub sẽ nằm ở edition/branch cao hơn.

## Nguyên tắc port-back

1. `mylands-api` là nguồn chuẩn ban đầu. Copy-lọc nhanh hơn build lại từng module nếu module đã ổn.
2. Core giữ namespace/cấu trúc Laravel giống app chính để dễ port ngược.
3. Không tạo request/controller kế thừa rỗng chỉ để “dùng core”.
4. Validation tiếng Việt, permission, audit/log phải theo chuẩn dự án chính.
5. Nếu core tối ưu base tốt hơn, áp dụng ngược lại `mylands-api`.

## Trạng thái hiện tại

- Branch `core-single-user-foundation` sẽ thay skeleton library hiện tại bằng Laravel mini app copy-lọc từ `mylands-api`.
- Các base như `BaseRepository`, `BaseRequest`, `ModuleRouteBuilder`, `BaseModelController`, activity helpers sẽ lấy từ app chính trước rồi tối ưu dần.
- Chạy check nhẹ theo từng lát cắt; không test/build lặp lại khi thay đổi hiển nhiên.

## Contract bắt buộc

API core phải hiểu cùng edition/cấp tổ chức với FE. Capability chỉ xác định module có được bật theo gói không; permission, validation và activity/audit log vẫn phải kiểm ở action nghiệp vụ thật.
