<?php

namespace EscolaLms\Templates\Http\Requests;

use EscolaLms\Templates\Models\Template;
use EscolaLms\Templates\Rules\TemplateValidContentRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class TemplateCreateRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('create', Template::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'type' => ['string'],
            'vars_set' => ['string'],
            'name' => ['string', 'required'],
            'content' => ['string', 'required', new TemplateValidContentRule()],
        ];
    }
}
