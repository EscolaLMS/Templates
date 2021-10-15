<?php

namespace EscolaLms\Templates\Repository\Contracts;

use EscolaLms\Core\Repositories\Contracts\BaseRepositoryContract;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface TemplateRepositoryContract extends BaseRepositoryContract
{
    public function searchAndPaginate(array $search = [], ?int $perPage = null, string $orderDirection = 'asc', string $orderColumn = 'id'): LengthAwarePaginator;
    public function deleteTemplate(int $id): bool;
}
