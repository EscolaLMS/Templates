<?php

namespace EscolaLms\Templates\Policies;

use EscolaLms\Core\Models\User;
use EscolaLms\Templates\Models\Template;
use Illuminate\Auth\Access\HandlesAuthorization;

class TemplatePolicy
{
    use HandlesAuthorization;

    public function list(?User $user): bool
    {
        return !is_null($user) && $user->can('list templates');
    }

    public function create(?User $user): bool
    {
        return !is_null($user) && $user->can('create templates');
    }

    public function delete(?User $user, Template $template = null): bool
    {
        return !is_null($user) && $user->can('delete templates');
    }

    public function update(?User $user, Template $template = null): bool
    {
        return !is_null($user) && $user->can('update templates');
    }
}
