<?php

namespace EscolaLms\Templates\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TemplateReadRequest extends FormRequest
{


    /**
     * @return bool
     */
    public function authorize()
    {
        $user = $this->user();
        return isset($user) ? $user->can('list', Template::class) : false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
        ];
    }
}
