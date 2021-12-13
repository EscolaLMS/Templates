<?php

namespace EscolaLms\Templates\Services\Contracts;

use EscolaLms\Templates\Models\Template;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface TemplateServiceContract
{
    public function search(array $search = []): LengthAwarePaginator;

    public function getById(int $id): Template;

    public function deleteById(int $id): bool;

    public function update(int $id, array $data): Template;

    public function insert(array $data): Template;

    public function isValid(Template $template): bool;

    public function generateContentUsingVariables(Template $template, array $variables): array;

    public function createPreview(Template $template): array;

    public function assignTemplateToModel(Template $template, ?int $assignable_id = null): Template;
}
