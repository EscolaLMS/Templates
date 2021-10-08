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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use EscolaLms\Templates\Mail\TemplatePreview;

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

    public function generatePDF(Template $template, array $vars): string
    {
        $content = strtr($template->content, $vars);
        $content = view('templates::ckeditor', ['body' => $content])->render();
        $filename = 'generated-' . uniqid() . '.pdf';
        Browsershot::html($content)
            ->addChromiumArguments([
                'no-sandbox',
                'disable-setuid-sandbox',
                'disable-dev-shm-usage',
                'single-process'
            ])
            ->timeout(120)
            ->save($filename);
        return $filename;
    }

    public function createPreview(Template $template): string | array
    {

        $enum = $this->variableService->getVariableEnumClassName($template->type, $template->vars_set);
        $vars = $enum::getMockVariables();

        $content = strtr($template->content, $vars);
        $result = $content;
        switch ($template->type) {
            case "pdf":
                $content = view('templates::ckeditor', ['body' => $content])->render();
                $result = $this->previewPDF($content);
                break;
            case "email":
                $content = view('templates::email', ['body' => $content])->render();
                $result = $this->previewEmail($content);
                break;
        }


        return $result;
    }

    private function previewEmail($markup, string $email = null): array
    {
        if (empty($email)) {
            $user = Auth::user();
            $email = $user->email;
        }

        Mail::to($email)->send(new TemplatePreview($markup));
        return [
            'sent' => true,
            'to' => $email
        ];
    }

    private function previewPDF($markup): array
    {
        $filename = 'preview-' . uniqid() . '.pdf';

        $dir = Storage::disk('local')->makeDirectory('tmp_pdfs/');
        $path = Storage::disk('local')->path('tmp_pdfs/' . $filename);
        $url = Storage::disk('local')->url('tmp_pdfs/' . $filename);

        Browsershot::html($markup)
            ->addChromiumArguments([
                'no-sandbox',
                'disable-setuid-sandbox',
                'disable-dev-shm-usage',
                'single-process'
            ])
            ->timeout(120)
            ->save($path);

        return [
            'filename' => $filename,
            'url' => $url
        ];
    }
}
