<?php

namespace EscolaLms\Templates\Services\Contracts;

use EscolaLms\Templates\Models\Template;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Interface TemplatesServiceContract
 * @package EscolaLms\Templates\Http\Services\Contracts
 */
interface TemplateServiceContract
{
    public function search(array $search = []): LengthAwarePaginator;

    public function getById(int $id): Template;

    public function insert(array $data): Template;

    public function deleteById(int $id): bool;

    public function update(int $id, array $data): Template;

    public function createPreview(Template $template) : string;

}
