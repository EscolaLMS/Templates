<?php

namespace EscolaLms\Templates\Http\Controllers\Contracts;

use EscolaLms\Templates\Http\Requests\TemplateDeleteRequest;
use EscolaLms\Templates\Http\Requests\TemplateCreateRequest;
use EscolaLms\Templates\Http\Requests\TemplateListingRequest;
use EscolaLms\Templates\Http\Requests\TemplateUpdateRequest;
use EscolaLms\Templates\Http\Requests\TemplateReadRequest;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

interface TemplatesAdminApiContract
{
    /**
     * @OA\Get(
     *     path="/api/admin/templates",
     *     summary="Lists available templates",
     *     tags={"Templates"},
     *     security={
     *         {"passport": {}},
     *     },
     *     @OA\Response(
     *         response=200,
     *         description="list of available templates",
     *         @OA\MediaType(
     *            mediaType="application/json",
     *            @OA\Schema(
     *                type="object",
     *                description="map of templates identified by a slug value",
     *                @OA\AdditionalProperties(
     *                    ref="#/components/schemas/Template"
     *                )
     *            )
     *         )
     *      ),
     *     @OA\Response(
     *          response=401,
     *          description="endpoint requires authentication",
     *     ),
     *     @OA\Response(
     *          response=403,
     *          description="user doesn't have required access rights",
     *      ),
     *     @OA\Response(
     *          response=500,
     *          description="server-side error",
     *      ),
     * )
     *
     * @param TemplateListingRequest $request
     * @return JsonResponse
     */
    public function list(TemplateListingRequest $request): JsonResponse;

    /**
     * @OA\Post(
     *     path="/api/admin/templates",
     *     summary="Create a new template identified by id",
     *     tags={"Templates"},
     *     security={
     *         {"passport": {}},
     *     },
     *     @OA\RequestBody(
     *         description="Template attributes",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Template")
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="template created successfully",
     *      ),
     *     @OA\Response(
     *          response=401,
     *          description="endpoint requires authentication",
     *      ),
     *     @OA\Response(
     *          response=403,
     *          description="user doesn't have required access rights",
     *      ),
     *     @OA\Response(
     *          response=409,
     *          description="there already is a template identified by chosen slug identifier",
     *      ),
     *     @OA\Response(
     *          response=422,
     *          description="one of the parameters has invalid format",
     *      ),
     *     @OA\Response(
     *          response=500,
     *          description="server-side error",
     *      ),
     * )
     *
     * @param TemplateCreateRequest $request
     * @return JsonResponse
     */
    public function create(TemplateCreateRequest $request): JsonResponse;

    /**
     * @OA\Patch(
     *     path="/api/admin/templates/{id}",
     *     summary="Update an existing template identified by id",
     *     tags={"Templates"},
     *     security={
     *         {"passport": {}},
     *     },
     *     @OA\Parameter(
     *         description="Unique human-readable template identifier",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         description="Template attributes",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Template")
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="template updated successfully",
     *      ),
     *     @OA\Response(
     *          response=401,
     *          description="endpoint requires authentication",
     *      ),
     *     @OA\Response(
     *          response=403,
     *          description="user doesn't have required access rights",
     *      ),
     *     @OA\Response(
     *          response=400,
     *          description="cannot find a template with provided slug identifier",
     *      ),
     *     @OA\Response(
     *          response=422,
     *          description="one of the parameters has invalid format",
     *      ),
     *     @OA\Response(
     *          response=500,
     *          description="server-side error",
     *      ),
     * )
     *
     * @param TemplateUpdateRequest $request
     * @return JsonResponse
     */
    public function update(TemplateUpdateRequest $request, int $id): JsonResponse;

    /**
     * @OA\Delete(
     *     path="/api/admin/templates/{id}",
     *     summary="Delete a template identified by a id",
     *     tags={"Templates"},
     *     security={
     *         {"passport": {}},
     *     },
     *     @OA\Parameter(
     *         description="Unique human-readable template identifier",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="template deleted successfully",
     *      ),
     *     @OA\Response(
     *          response=401,
     *          description="endpoint requires authentication",
     *      ),
     *     @OA\Response(
     *          response=403,
     *          description="user doesn't have required access rights",
     *      ),
     *     @OA\Response(
     *          response=400,
     *          description="cannot find a template with provided slug identifier",
     *      ),
     *     @OA\Response(
     *          response=500,
     *          description="server-side error",
     *      ),
     * )
     *
     * @param TemplateDeleteRequest $request
     * @return JsonResponse
     */
    public function delete(TemplateDeleteRequest $request, int $id): JsonResponse;

    /**
     * @OA\Get(
     *     path="/api/admin/templates/{id}",
     *     summary="Read a template identified by a given id identifier",
     *     tags={"Templates"},
     *     security={
     *         {"passport": {}},
     *     },
     *     @OA\Parameter(
     *         description="Unique human-readable template identifier",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="",
     *         @OA\JsonContent(ref="#/components/schemas/Template")
     *      ),
     *     @OA\Response(
     *          response=401,
     *          description="endpoint requires authentication",
     *     ),
     *     @OA\Response(
     *          response=403,
     *          description="user doesn't have required access rights",
     *      ),
     *     @OA\Response(
     *          response=500,
     *          description="server-side error",
     *      ),
     * )
     *
     * @param TemplateListingRequest $request
     * @return JsonResponse
     */
    public function read(TemplateReadRequest $request, int $id): JsonResponse;


      /**
     * @OA\Get(
     *     path="/api/admin/templates/variables",
     *     summary="Dictionary list of available template variables",
     *     tags={"Templates"},
     *     security={
     *         {"passport": {}},
     *     },
     *     @OA\Parameter(
     *         description="Unique human-readable template identifier",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="",
     *      ),
     *     @OA\Response(
     *          response=401,
     *          description="endpoint requires authentication",
     *     ),
     *     @OA\Response(
     *          response=403,
     *          description="user doesn't have required access rights",
     *      ),
     *     @OA\Response(
     *          response=500,
     *          description="server-side error",
     *      ),
     * )
     *
     * @param TemplateListingRequest $request
     * @return JsonResponse
     */
    public function variables(TemplateReadRequest $request): JsonResponse;


        /**
     * @OA\Get(
     *     path="/api/admin/templates/{id}/preview_email",
     *     summary="Read a template identified by a given id identifier",
     *     tags={"Templates"},
     *     security={
     *         {"passport": {}},
     *     },
     *     @OA\Parameter(
     *         description="Unique human-readable template identifier",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="PDF with faker",
     *      ),
     *     @OA\Response(
     *          response=401,
     *          description="endpoint requires authentication",
     *     ),
     *     @OA\Response(
     *          response=403,
     *          description="user doesn't have required access rights",
     *      ),
     *     @OA\Response(
     *          response=500,
     *          description="server-side error",
     *      ),
     * )
     *
     * @param TemplateListingRequest $request
     * @return Response
     */
    public function previewEmail(TemplateReadRequest $request, int $id): Response;

    /**
     * @OA\Get(
     *     path="/api/admin/templates/{id}/preview_pdf",
     *     summary="Read a template identified by a given id identifier",
     *     tags={"Templates"},
     *     security={
     *         {"passport": {}},
     *     },
     *     @OA\Parameter(
     *         description="Unique human-readable template identifier",
     *         in="path",
     *         name="id",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="PDF with faker",
     *      ),
     *     @OA\Response(
     *          response=401,
     *          description="endpoint requires authentication",
     *     ),
     *     @OA\Response(
     *          response=403,
     *          description="user doesn't have required access rights",
     *      ),
     *     @OA\Response(
     *          response=500,
     *          description="server-side error",
     *      ),
     * )
     *
     * @param TemplateListingRequest $request
     * @return Response
     */
    public function previewPdf(TemplateReadRequest $request, int $id): Response;
}
