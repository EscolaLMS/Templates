<?php

namespace EscolaLms\Templates\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use EscolaLms\Templates\Models\Template;

class TemplateUpdateRequest extends FormRequest
{


    /**
     * @return bool
     */
    public function authorize()
    {
        /** @var User $user */
        $user = $this->user();
        return isset($user) ? $user->can('update', Template::class) : false;
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
            'name' => 'string',
            'content' => 'string',
        ];
    }
}
