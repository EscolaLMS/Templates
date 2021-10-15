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
            'type' => ['sometimes', 'string'],
            'vars_set' => ['sometimes', 'string'],
            'name' => ['sometimes', 'string', 'required'],
            'content' => ['sometimes', 'string', 'required', new TemplateValidContentRule($this->getTemplate())],
        ];
    }

    public function getTemplate(): ?Template
    {
        return Template::find($this->route('id'));
    }
}
