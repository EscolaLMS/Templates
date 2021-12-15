<?php

namespace EscolaLms\Templates\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use EscolaLms\Templates\Database\Factories\TemplateFactory;
use EscolaLms\Templates\Services\Contracts\TemplateServiceContract;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @OA\Schema(
 *      schema="Template",
 *      @OA\Property(
 *          property="id",
 *          description="template id",
 *          type="integer",
 *      ),
 *      @OA\Property(
 *          property="name",
 *          description="template name",
 *          type="string"
 *      ),
 *      @OA\Property(
 *          property="event",
 *          description="event full classname",
 *          type="string"
 *      ),
 *      @OA\Property(
 *          property="channel",
 *          description="channel full classname",
 *          type="string"
 *      ),
 *      @OA\Property(
 *          property="assignable_type",
 *          description="classname of Model to which this template is assigned (for example for creating custom template for each Course)",
 *          type="string"
 *      ),
 *      @OA\Property(
 *          property="assignable_id",
 *          description="id of Model to which this template is assigned (for example for creating custom template for each Course)",
 *          type="string"
 *      ),
 *      @OA\Property(
 *          property="default",
 *          description="this template is default template for given channel and event pair",
 *          type="bool"
 *      ),
 * )
 */
class Template extends Model
{
    use HasFactory;

    protected $table = 'templates';

    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'channel' => 'string',
        'event' => 'string',
        'default' => 'bool',
    ];

    protected $guarded = [
        'id'
    ];

    protected static function newFactory()
    {
        return TemplateFactory::new();
    }

    public function sections(): HasMany
    {
        return $this->hasMany(TemplateSection::class);
    }

    public function assignable(): MorphTo
    {
        return $this->morphTo();
    }

    public function generateContent(array $variables): array
    {
        /** @var TemplateServiceContract $service */
        $service = app(TemplateServiceContract::class);
        return $service->generateContentUsingVariables($this, $variables);
    }

    public function getIsValidAttribute(): bool
    {
        /** @var TemplateServiceContract $service */
        $service = app(TemplateServiceContract::class);
        return $service->isValid($this);
    }
}
