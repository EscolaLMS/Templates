<?php

namespace EscolaLms\Templates\Services;

use EscolaLms\Core\Models\User;
use EscolaLms\Templates\Facades\Template as FacadesTemplate;
use EscolaLms\Templates\Models\Template;
use EscolaLms\Templates\Models\TemplateSection;
use EscolaLms\Templates\Repository\Contracts\TemplateRepositoryContract;
use EscolaLms\Templates\Services\Contracts\TemplateServiceContract;
use EscolaLms\Templates\Services\Contracts\TemplateVariablesServiceContract;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TemplateService implements TemplateServiceContract
{
    protected TemplateRepositoryContract $repository;
    protected TemplateVariablesServiceContract $variableService;

    public function __construct(
        TemplateRepositoryContract $repository,
        TemplateVariablesServiceContract $variableService
    ) {
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
        if (array_key_exists('sections', $data) && !empty($data['sections'])) {
            foreach ($data['sections'] as $section) {
                $sections[$section['key']] = $section['content'];
            }
            unset($data['sections']);
            $template = $this->repository->createWithSections($data, $sections);
        } else {
            $template = $this->repository->create($data);
        }
        return FacadesTemplate::processTemplateAfterSaving($template);
    }

    public function deleteById(int $id): bool
    {
        return $this->repository->deleteTemplate($id);
    }

    public function update(int $id, array $data): Template
    {
        if (array_key_exists('sections', $data) && !empty($data['sections'])) {
            foreach ($data['sections'] as $section) {
                $sections[$section['key']] = $section['content'];
            }
            unset($data['sections']);
            $template = $this->repository->updateWithSections($data, $sections, $id);
        } else {
            $template =  $this->repository->update($data, $id);
        }
        return FacadesTemplate::processTemplateAfterSaving($template);
    }

    public function isValid(Template $template): bool
    {
        $channelClass = $template->channel;
        $variableClass = FacadesTemplate::getVariableClassName($template->event, $channelClass);

        if (!$variableClass) {
            return false;
        }

        $existingSections = [];
        foreach ($template->sections->pluck('content', 'key') as $key => $content) {
            if (!empty($content)) {
                $existingSections[$key] = $content;
            }
        }

        $requiredSections = array_merge($variableClass::requiredSections(), $channelClass::sectionsRequired());
        foreach ($requiredSections as $section) {
            if (!in_array($section, array_keys($existingSections))) {
                return false;
            }
        }

        $allContent = implode($existingSections);
        if (!$this->variableService->contentIsValidForChannel($variableClass, $channelClass, $allContent)) {
            return false;
        }

        foreach ($existingSections as $key => $content) {
            if (!$this->variableService->sectionIsValid($variableClass, $key, $content)) {
                return false;
            }
        }

        return true;
    }

    public function generateContentUsingVariables(Template $template, array $variables): array
    {
        $results = [
            'template_id' => $template->id
        ];
        
        foreach ($template->sections as $section) {
            /** @var TemplateSection $section */
            $results[$section->key] = strtr($section->content, $variables);
        }
        return $results;
    }

    public function previewContentUsingMockedVariables(Template $template, ?User $user = null): array
    {
        $channelClass = $template->channel;
        $variableClass = FacadesTemplate::getVariableClassName($template->event, $channelClass);
        return $this->generateContentUsingVariables($template, $variableClass::mockedVariables($user));
    }

    public function assignTemplateToModel(Template $template, int $assignable_id): Template
    {
        $variableClass = FacadesTemplate::getVariableClassName($template->event, $template->channel);
        $assignableClass = $variableClass::assignableClass();
        if (class_exists($assignableClass)) {
            $assignable = $assignableClass::findOrFail($assignable_id);
            $template->assignable()->associate($assignable);
            $template->save();
        }

        return $template;
    }

    public function unassignTemplateFromModel(Template $template, int $assignable_id): Template
    {
        $variableClass = FacadesTemplate::getVariableClassName($template->event, $template->channel);
        $assignableClass = $variableClass::assignableClass();
        if (class_exists($assignableClass)) {
            $assignable = $assignableClass::findOrFail($assignable_id);
            $template->assignable()->dissociate($assignable);
            $template->save();
        }

        return $template;
    }
}
