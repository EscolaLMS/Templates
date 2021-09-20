<?php

namespace EscolaLms\Templates\Services;

use EscolaLms\Templates\Services\Contracts\TemplateServiceContract;
use EscolaLms\Templates\Models\Template;
use EscolaLms\Templates\Repository\Contracts\TemplateRepositoryContract;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TemplateService implements TemplateServiceContract
{
    private TemplateRepositoryContract $repository;

    public function __construct(TemplateRepositoryContract $repository)
    {
        $this->repository = $repository;
    }

    public function search(array $search = []): LengthAwarePaginator
    {
        return $this->repository->searchAndPaginate($search);
    }

    public function getById(int $id): Template
    {
        return $this->repository->find($id);
    }

    public function insert(array $data): Template
    {
        /** @var Template $template */
        $template = new Template();
        $template->fill($data);
        $this->repository->insert($template);
        if (!$template->exists()) {
            throw new Exception("error creating template");
        }
        return $template;
    }

    public function deleteById(int $id): bool
    {
        return $this->repository->deleteTemplate($id);
    }

    public function update(int $id, array $data): Template
    {
        return $this->repository->update($data, $id);
    }
}
