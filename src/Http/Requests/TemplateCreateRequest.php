<?php

namespace EscolaLms\Templates\Http\Requests;

use EscolaLms\Core\Models\User;
use EscolaLms\Core\Models\Template;

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
        return isset($user) ? $user->can('create', Template::class) : false;
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
        ];
    }
}
