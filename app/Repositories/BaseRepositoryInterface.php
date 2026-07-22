<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Builder;

interface BaseRepositoryInterface
{
    public function scopeNullableAllowedRelation(Builder $query, string $nullableColumn, string $relation): Builder;

    public function scopeAllowedRelationQuery(Builder $query, string $relation): Builder;

    public function paginateQuery(Builder $query, array $filters = [], array $defaultSorts = []): mixed;

    public function sortPaginateQuery(Builder $query, array $filters = [], array $defaultSorts = []): mixed;

    public function findQueryOrFail(Builder $query, int|string $id, string $column = 'id'): mixed;

    public function findAllowedQueryOrFail(Builder $query, int|string $id, string $relation, string $column = 'id'): mixed;
}
