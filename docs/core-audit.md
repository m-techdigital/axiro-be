# Core Direction Audit

Ngày rà soát: 2026-07-22

## Kết luận

API core hiện mới là lớp primitive ban đầu, chưa đủ là một platform core hoàn chỉnh có thể chạy thật. Hướng đúng không phải viết backend mới, mà là tách dần các pattern đã chứng minh trong `mylands-api`.

## Điều chỉnh đã chốt

- `mylands-api` là nguồn chuẩn cho backend base code.
- Không đưa business service/controller lớn như HR, Accounting, CRM vào platform core.
- Chỉ tách `BaseRepository`, `BaseService`, `BaseController`, `BaseRequest`, route/capability/helper khi đã có pattern lặp trong module thật.
- Request kế thừa rỗng là không cần thiết; dùng helper/base trực tiếp nếu không thêm rule/logic.
- Permission, validation, audit/log phải tiếp tục được kiểm tại action nghiệp vụ thật.

## Việc cần làm tiếp

1. Lập inventory controller/service/request/repository đang lặp trong `mylands-api`.
2. Nâng `BaseRepositoryInterface` và `BaseRepository` theo nhu cầu CRUD thật.
3. Tách request rule composition và validation message/attribute chuẩn.
4. Tách audit relation snapshot helper để timeline/log không hiện ID thô.
5. Áp dụng thử vào một module thật trước khi mở rộng.
