<?php

namespace EscolaLms\Templates\Http\Requests;

use EscolaLms\Templates\Models\Template;
use EscolaLms\Templates\Rules\TemplateValidContentRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class TemplateUpdateRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('update', Template::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['sometimes', 'string'],
            'channel' => ['sometimes', 'string'],
            'event' => ['sometimes', 'string'],
            'default' => ['sometimes', 'bool'],
            'sections' => ['sometimes', 'array', new TemplateValidContentRule($this->getTemplate())],
            'sections.*.key' => ['string'],
            'sections.*.content' => ['string'],
        ];
    }

    public function getTemplate(): ?Template
    {
        return Template::find($this->route('id'));
    }
}
