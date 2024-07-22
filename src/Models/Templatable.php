<?php

namespace EscolaLms\Templates\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property int $id
 * @property int $templatable_id
 * @property string $templatable_type
 */
class Templatable extends Model
{
    use HasFactory;

    protected $table = 'templatables';

    protected $casts = [
        'id' => 'integer',
    ];

    protected $guarded = [
        'id'
    ];

    public function template(): BelongsTo
    {
        return $this->belongsTo(Template::class);
    }

    public function templatable(): MorphTo
    {
        return $this->morphTo();
    }
}
