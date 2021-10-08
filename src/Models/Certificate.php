<?php

namespace EscolaLms\Templates\Models;

use EscolaLms\Courses\Models\Course;
use EscolaLms\Auth\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Certificate",
 *     @OA\Property(
 *          property="id",
 *          type="integer",
 *          description="id "
 *     ),
 *     @OA\Property(
 *         property="course_id",
 *         type="integer",
 *         description="identifier of the course"
 *     ),
 *     @OA\Property(
 *         property="user_id",
 *         type="integer",
 *         description="identifier of the user"
 *     ),
 *     @OA\Property(
 *          property="path",
 *          type="string",
 *          description="path"
 *     ),
 *     @OA\Property(
 *          property="status",
 *          type="string",
 *          description="queue status"
 *     )
 * )
 *
 * @property integer $id
 * @property integer $course_id
 * @property integer $user_id
 * @property string $status
 * @property string $path
 */
class Certificate extends Model
{
    use HasFactory;

    public $table = 'certificates';

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */


    protected $casts = [
        'id' => 'integer',
        'status' => 'string',
        'path' => 'string',
        'course_id' => 'integer',
        'user_id' => 'integer',
        'template_id' => 'integer'
    ];

    public $fillable = [
        'name',
        'status',
        'path',
        'course_id',
        'user_id',
        'template_id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
