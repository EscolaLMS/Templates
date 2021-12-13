<?php

use EscolaLms\Templates\Http\Controllers\TemplatesAdminApiController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'api/admin/templates', 'middleware' => ['auth:api']], function () {
    Route::get('/events', [TemplatesAdminApiController::class, 'events']);
    Route::get('/variables', [TemplatesAdminApiController::class, 'variables']);
    Route::get('/{id}/preview', [TemplatesAdminApiController::class, 'preview']);
    Route::get('/', [TemplatesAdminApiController::class, 'list']);
    Route::get('/{id}', [TemplatesAdminApiController::class, 'read']);
    Route::post('/', [TemplatesAdminApiController::class, 'create']);
    Route::delete('/{id}', [TemplatesAdminApiController::class, 'delete']);
    Route::patch('/{id}', [TemplatesAdminApiController::class, 'update']);
    Route::post('/{id}/assign', [TemplatesAdminApiController::class, 'assign']);
});
