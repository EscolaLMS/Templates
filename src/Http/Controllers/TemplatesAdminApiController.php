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
        try {
            $templates = $this->templateService->search();
            return $this->sendResponseForResource(TemplateResource::collection($templates), "templates list retrieved successfully");
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function create(TemplateCreateRequest $request): JsonResponse
    {
        try {
            $template = $this->templateService->insert($request->all());
            return $this->sendResponseForResource(TemplateResource::make($template), "template created successfully");
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function update(TemplateUpdateRequest $request, int $id): JsonResponse
    {
        try {
            $input = $request->all();

            $updated = $this->templateService->update($id, $input);
            if (!$updated) {
                return $this->sendError(sprintf("template id '%s' doesn't exists", $id), 404);
            }
            return $this->sendResponseForResource(TemplateResource::make($updated), "template updated successfully");
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function delete(TemplateDeleteRequest $request, int $id): JsonResponse
    {
        try {
            $deleted = $this->templateService->deleteById($id);
            if (!$deleted) {
                return $this->sendError(sprintf("template with id '%s' doesn't exists", $id), 404);
            }
            return $this->sendResponse($deleted, "template updated successfully");
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function read(TemplateReadRequest $request, int $id): JsonResponse
    {
        try {
            $template = $this->templateService->getById($id);
            if ($template->exists) {
                return $this->sendResponseForResource(TemplateResource::make($template), "template fetched successfully");
            }
            return $this->sendError(sprintf("template with id '%s' doesn't exists", $id), 404);
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function variables(TemplateReadRequest $request): JsonResponse
    {
        $vars = $this->variablesService->getAvailableTokens();

        try {
            return $this->sendResponse($vars, "template vars fetched successfully");
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function previewPdf(TemplateReadRequest $request, $id): Response
    {
        $template = Template::findOrFail($id);

        $filepath = $this->templateService->createPreview($template);

        try {
            return response()->file(public_path($filepath));
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function previewEmail(TemplateReadRequest $request, $id): Response
    {
        $template = Template::findOrFail($id);

        $filepath = $this->templateService->createPreview($template);

        try {
            return response()->file(public_path($filepath));
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
}
