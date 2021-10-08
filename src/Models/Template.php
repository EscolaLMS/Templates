<?php

namespace EscolaLms\Templates\Models;

use EscolaLms\Courses\Models\Course;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use EscolaLms\Templates\Database\Factories\TemplateFactory;

/**
 * @OA\Schema(
 *     schema="Template",
 *     required={"name","type","vars_set","content"},
 *     @OA\Property(
 *          property="name",
 *          type="string",
 *          description="name "
 *     ),
 *     @OA\Property(
 *          property="type",
 *          type="string",
 *          description="type (Certificate, email, etc)"
 *     ),
 *     @OA\Property(
 *          property="vars_set",
 *          type="string",
 *          description="avaliable vars (Certificate, email account confirm, etc)"
 *     ),
 *     @OA\Property(
 *          property="content",
 *          type="string",
 *          description="template content"
 *     )
 * )
 *
 * @property integer $id
 * @property string $name
 * @property string $type
 * @property string $content
 */
class Template extends Model
{
    use HasFactory;

    public $table = 'templates';

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */


    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'type' => 'string',
        'vars_set' => 'string',
        'content' => 'string',
    ];

    public $fillable = [
        'name',
        'type',
        'vars_set',
        'content'
    ];


    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return TemplateFactory::new();
    }
}
