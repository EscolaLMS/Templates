<?php

namespace EscolaLms\Templates\Http\Controllers;

use EscolaLms\Core\Http\Controllers\EscolaLmsBaseController;
use EscolaLms\Templates\Http\Controllers\Contracts\TemplatesAdminApiContract;
use EscolaLms\Templates\Http\Requests\TemplateCreateRequest;
use EscolaLms\Templates\Http\Requests\TemplateDeleteRequest;
use EscolaLms\Templates\Http\Requests\TemplateListingRequest;
use EscolaLms\Templates\Http\Requests\TemplateReadRequest;
use EscolaLms\Templates\Http\Requests\TemplateUpdateRequest;
use EscolaLms\Templates\Http\Resources\TemplateResource;
use EscolaLms\Templates\Services\Contracts\TemplateServiceContract;
use EscolaLms\Templates\Services\Contracts\VariablesServiceContract;
use EscolaLms\Templates\Models\Template;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Exception;

class TemplatesAdminApiController extends EscolaLmsBaseController implements TemplatesAdminApiContract
{
    private TemplateServiceContract $templateService;
    private VariablesServiceContract $variablesService;

    public function __construct(TemplateServiceContract $templateService, VariablesServiceContract $variablesService)
    {
        $this->templateService = $templateService;
        $this->variablesService = $variablesService;
    }

    public function list(TemplateListingRequest $request): JsonResponse
    {
        $templates = $this->templateService->search();
        return $this->sendResponseForResource(TemplateResource::collection($templates), "templates list retrieved successfully");
    }

    public function create(TemplateCreateRequest $request): JsonResponse
    {
        $template = $this->templateService->insert($request->all());
        return $this->sendResponseForResource(TemplateResource::make($template), "template created successfully");
    }

    public function update(TemplateUpdateRequest $request, int $id): JsonResponse
    {
        $input = $request->all();

        $updated = $this->templateService->update($id, $input);
        if (!$updated) {
            return $this->sendError(sprintf("template id '%s' doesn't exists", $id), 404);
        }
        return $this->sendResponse($updated, "template updated successfully");
    }

    public function delete(TemplateDeleteRequest $request, int $id): JsonResponse
    {
        $deleted = $this->templateService->deleteById($id);
        if (!$deleted) {
            return $this->sendError(sprintf("template with id '%s' doesn't exists", $id), 404);
        }
        return $this->sendResponse($deleted, "template deleted successfully");
    }

    public function read(TemplateReadRequest $request, int $id): JsonResponse
    {
        $template = $this->templateService->getById($id);
        if ($template->exists) {
            return $this->sendResponseForResource(TemplateResource::make($template), "template fetched successfully");
        }
        return $this->sendError(sprintf("template with id '%s' doesn't exists", $id), 404);
    }

    public function variables(TemplateReadRequest $request): JsonResponse
    {
        $vars = $this->variablesService->getAvailableTokens();

        return $this->sendResponse($vars, "template vars fetched successfully");
    }

    public function preview(TemplateReadRequest $request, $id): Response
    {
        $template = Template::findOrFail($id);

        $preview = $this->templateService->createPreview($template);

        return $this->sendResponse($preview, "template preview fetched successfully");
    }
}
