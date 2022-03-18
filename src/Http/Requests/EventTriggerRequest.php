<?php

namespace EscolaLms\Templates\Http\Requests;

use EscolaLms\Templates\Enums\TemplatesPermissionsEnum;
use Illuminate\Foundation\Http\FormRequest;

class EventTriggerRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = auth()->user();

        return !is_null($user) && $user->can(TemplatesPermissionsEnum::EVENTS_TRIGGER);
    }

    public function rules(): array
    {
        return [
            'users' => ['required', 'array'],
            'users.*' => ['integer', 'exists:users,id'],
        ];
    }
}
