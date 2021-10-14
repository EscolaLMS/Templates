<?php

namespace EscolaLms\Templates\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TemplateDeleteRequest extends FormRequest
{


    /**
     * @return bool
     */
    public function authorize()
    {
        /** @var User $user */
        $user = $this->user();
        return isset($user) ? $user->can('delete', Template::class) : false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }
}
