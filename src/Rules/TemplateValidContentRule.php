<?php

namespace EscolaLms\Templates\Rules;

use EscolaLms\Templates\Models\Template;
use EscolaLms\Templates\Services\Contracts\VariablesServiceContract;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;

class TemplateValidContentRule implements Rule
{
    private ?Template $template;
    private Request $request;
    private VariablesServiceContract $variableService;

    public function __construct(?Template $template = null)
    {
        $this->template = $template;
        $this->request = request();
        $this->variableService = app(VariablesServiceContract::class);
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (!$this->template || ($this->request->has('type') && $this->request->has('vars_set'))) {
            $templateVariableClass = $this->variableService->getVariableEnumClassName($this->request->input('type'), $this->request->input('vars_set'));
        } else {
            $templateVariableClass = $this->variableService->getVariableEnumClassName($this->template->type, $this->template->vars_set);
        }
        return $templateVariableClass::isValid($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('The :attribute must contain all required variables.');
    }
}
