<?php

namespace App\Services;

use App\Repositories\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;

abstract class BaseCrudService
{
    public function __construct(protected BaseRepositoryInterface $repository)
    {
    }

    /**
     * Tạo danh sách đã filter/sort/paginate từ query đã được service con scope quyền/ngữ cảnh.
     *
     * @param  array<string, mixed>  $filters
     * @param  array<string, string>  $defaultSorts
     */
    protected function listFromQuery(Builder $query, array $filters = [], array $defaultSorts = []): mixed
    {
        return $this->repository->paginateQuery($query, $filters, $defaultSorts);
    }

    /**
     * Tạo danh sách từ query đã filter nghiệp vụ riêng trước, chỉ còn sort/paginate ở base.
     *
     * @param  array<string, mixed>  $filters
     * @param  array<string, string>  $defaultSorts
     */
    protected function sortListFromQuery(Builder $query, array $filters = [], array $defaultSorts = []): mixed
    {
        return $this->repository->sortPaginateQuery($query, $filters, $defaultSorts);
    }

    /**
     * Lấy detail từ query đã được scope sẵn để service con không bỏ qua permission/context.
     */
    protected function findFromQuery(Builder $query, int|string $id, string $column = 'id'): mixed
    {
        return $this->repository->findQueryOrFail($query, $id, $column);
    }
}
