<?php

namespace EscolaLms\Templates\Models;

use EscolaLms\Courses\Models\Course;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Template",
 *     required={"title","author_id","content"},
 *     @OA\Property(
 *          property="name",
 *          type="string",
 *          description="name "
 *     ),
 *     @OA\Property(
 *         property="course_id",
 *         type="integer",
 *         description="identifier oftcourse"
 *     ), 
 *     @OA\Property(
 *          property="type",
 *          type="string",
 *          description="type (Certificate, email, etc)"
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
 * @property integer $course_id
 * @property string $content
 */
class Template extends Model
{
    use HasFactory;

    public $table = 'templates';
    public $timestamps = false;

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */


    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'type' => 'string',
        'course_id' => 'integer',
        'content' => 'string',
    ];

    public $fillable = [
        'name',
        'type',
        'course_id',
        'content'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}
