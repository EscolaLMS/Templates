<?php

namespace EscolaLms\Templates\Services;

use EscolaLms\Templates\Services\Contracts\TemplateServiceContract;
use EscolaLms\Templates\Models\Template;
use EscolaLms\Templates\Repository\Contracts\TemplateRepositoryContract;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Spatie\Browsershot\Browsershot;
use EscolaLms\Templates\Models\Certificate;
use EscolaLms\Templates\Services\Contracts\VariablesServiceContract;

class TemplateService implements TemplateServiceContract
{
    private TemplateRepositoryContract $repository;
    private VariablesServiceContract $variableService;

    public function __construct(TemplateRepositoryContract $repository, VariablesServiceContract $variableService)
    {
        $this->repository = $repository;
        $this->variableService = $variableService;
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

    public function createPreview(Template $template): string
    {
        switch ($template->vars_set) {
            case "certificates":
                $vars = $this->variableService->getMockVariables(Certificate::class);
                break;
            default:
                $vars = [];
        }

        $content = strtr($template->content, $vars);
        $content = view('templates::ckeditor', ['body' => $content])->render();

        $filename = 'preview-' . uniqid() . '.pdf';

        Browsershot::html($content)
            ->addChromiumArguments([
                'no-sandbox',
                'disable-setuid-sandbox',
                'disable-dev-shm-usage',
                'single-process'
            ])
            ->timeout(120)
            ->save($filename);

        // Add this file to delete queue 

        return $filename;
    }
}
