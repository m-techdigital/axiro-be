<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Builder;

abstract class BaseRepository implements BaseRepositoryInterface
{
    // Scope dữ liệu có thể thuộc toàn hệ thống hoặc relation người dùng được phép thao tác.
    public function scopeNullableAllowedRelation(Builder $query, string $nullableColumn, string $relation): Builder
    {
        return $query->where(fn ($scope) => $scope
            ->whereNull($nullableColumn)
            ->orWhereHas($relation, fn ($related) => $related->allowed()));
    }

    // Scope relation có rule allowed để repository con không lặp query quyền.
    public function scopeAllowedRelationQuery(Builder $query, string $relation): Builder
    {
        return $query->whereHas($relation, fn ($related) => $related->allowed());
    }

    // Chuẩn hóa list query đã có filter/sort/pagy macro trong app consumer.
    public function paginateQuery(Builder $query, array $filters = [], array $defaultSorts = []): mixed
    {
        return $query
            ->filter($filters)
            ->sort($filters, $defaultSorts)
            ->pagy();
    }

    // Dùng cho query đã apply filter nghiệp vụ riêng trước khi sort/paginate.
    public function sortPaginateQuery(Builder $query, array $filters = [], array $defaultSorts = []): mixed
    {
        return $query
            ->sort($filters, $defaultSorts)
            ->pagy();
    }

    // Tìm bản ghi trong query đã được repository scope sẵn; không bỏ qua filter quyền/ngữ cảnh trước đó.
    public function findQueryOrFail(Builder $query, int|string $id, string $column = 'id'): mixed
    {
        return $query->where($column, $id)->firstOrFail();
    }

    // Tìm bản ghi và bắt buộc relation liên quan có allowed scope, dùng cho record thuộc công ty/thành viên/team.
    public function findAllowedQueryOrFail(Builder $query, int|string $id, string $relation, string $column = 'id'): mixed
    {
        return $this->findQueryOrFail($this->scopeAllowedRelationQuery($query, $relation), $id, $column);
    }

    // Alias protected cho các repository đang dùng naming cũ trong app hiện tại.
    protected function scopeAllowedRelation(Builder $query, string $relation): Builder
    {
        return $this->scopeAllowedRelationQuery($query, $relation);
    }

    // Alias paginate để giữ API nội bộ gọn trong repository con.
    protected function paginate(Builder $query, array $filters = [], array $defaultSorts = []): mixed
    {
        return $this->paginateQuery($query, $filters, $defaultSorts);
    }

    // Alias sort/paginate cho query đã có filter nghiệp vụ tùy biến.
    protected function sortPaginate(Builder $query, array $filters = [], array $defaultSorts = []): mixed
    {
        return $this->sortPaginateQuery($query, $filters, $defaultSorts);
    }

    // Alias protected cho repository con khi cần lấy detail theo query đã scope.
    protected function findFromQuery(Builder $query, int|string $id, string $column = 'id'): mixed
    {
        return $this->findQueryOrFail($query, $id, $column);
    }

    // Alias protected cho detail cần kiểm relation allowed.
    protected function findAllowedFromQuery(Builder $query, int|string $id, string $relation, string $column = 'id'): mixed
    {
        return $this->findAllowedQueryOrFail($query, $id, $relation, $column);
    }
}
