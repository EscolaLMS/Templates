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
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Exception;

class TemplatesAdminApiController extends EscolaLmsBaseController implements TemplatesAdminApiContract
{
    private TemplateServiceContract $templateService;

    public function __construct(TemplateServiceContract $templateService)
    {
        $this->templateService = $templateService;
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

        // TODO handle this somehow 

        //  move this with faker to enum class 
        $vars = [
            "@VarDateFinished",
            "@VarStudentFirstName",
            "@VarStudentLastName",
            "@VarStudentFullName",
            "@VarTutorFirstName",
            "@VarTutorLastName",
            "@VarTutorFullName",
            "@VarCourseName"
        ];

        try {

            return $this->sendResponse($vars, "template vars fetched successfully");
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }
}
