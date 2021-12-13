<?php

namespace EscolaLms\Templates\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use EscolaLms\Templates\Database\Factories\TemplateFactory;
use EscolaLms\Templates\Services\Contracts\TemplateServiceContract;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

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
