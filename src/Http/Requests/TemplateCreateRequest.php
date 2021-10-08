<?php

namespace EscolaLms\Templates\Http\Requests;

use EscolaLms\Core\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class TemplateCreateRequest extends FormRequest
{


    /**
     * @return bool
     */
    public function authorize()
    {
        /** @var User $user */
        $user = $this->user();
        return $user->can('create templates', 'api');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'type' => 'string',
            'vars_set' => 'string',
            'name' => 'string|required',
            'content' => 'string|required',
            'course_id' => 'integer|exists:courses'
        ];
    }

    
}
