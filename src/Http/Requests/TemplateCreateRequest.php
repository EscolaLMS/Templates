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
            'name' => ['required', 'string'],
            'channel' => ['required', 'string'],
            'event' => ['required', 'string'],
            'default' => ['sometimes', 'bool'],
            'sections' => ['required', 'array', new TemplateValidContentRule()],
            'sections.*.key' => ['string'],
            'sections.*.content' => ['string'],
        ];
    }
}
