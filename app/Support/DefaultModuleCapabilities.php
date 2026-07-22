<?php

namespace App\Support;

final class DefaultModuleCapabilities
{
    /**
     * Catalog nền của Platform Core. Các module business/HR/kế toán sẽ khai báo ở branch hoặc app mở rộng,
     * không hardcode sẵn scope tổ chức khi database core chưa có các bảng tương ứng.
     *
     * @return array<string, array<string, mixed>>
     */
    public static function config(): array
    {
        return [
            'auth' => self::platformCore('Xác thực', permissionsMode: PermissionMode::HiddenFull),
            'dashboard' => self::platformCore('Tổng quan'),
            'users' => self::platformCore('Tài khoản'),
            'profile' => self::platformCore('Hồ sơ cá nhân', permissionsMode: PermissionMode::HiddenFull),
            'permissions' => self::platformCore('Phân quyền'),
            'settings' => self::platformCore('Cài đặt'),
            'notifications' => self::platformCore('Thông báo'),
            'audit_logs' => self::platformCore('Nhật ký hệ thống'),
            'files' => self::platformCore('Tệp tin'),
            'comments' => self::platformCore('Trao đổi'),
            'import_export' => self::platformCore('Nhập xuất dữ liệu'),
            'reports' => self::platformCore('Báo cáo'),
        ];
    }

    // Tạo registry mặc định để API consumer có thể bootstrap nhanh trong service provider.
    public static function registry(array $overrides = []): ModuleCapabilityRegistry
    {
        return ModuleCapabilityRegistry::fromConfig([
            ...self::config(),
            ...$overrides,
        ]);
    }

    /**
     * Khai báo module nền platform. Scope cụ thể như company_id/team_id chỉ thêm ở module extension thật.
     *
     * @param  array<int, string>|null  $availableIn
     * @param  array<int, string>  $scopeFields
     * @return array<string, mixed>
     */
    private static function platformCore(
        string $label,
        ?array $availableIn = null,
        array $scopeFields = [],
        PermissionMode $permissionsMode = PermissionMode::ScopedPermissions,
    ): array {
        return [
            'label' => $label,
            'layer' => ModuleLayer::PlatformCore->value,
            'available_in' => $availableIn ?? OrganizationLevel::values(),
            'scope_fields' => $scopeFields,
            'permissions_mode' => $permissionsMode->value,
        ];
    }
}
