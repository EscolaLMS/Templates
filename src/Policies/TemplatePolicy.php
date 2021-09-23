<?php

namespace EscolaLms\Templates\Policies;

use EscolaLms\Core\Models\User;
use EscolaLms\Templates\Models\Template;
use Illuminate\Auth\Access\HandlesAuthorization;

class TemplatePolicy
{
    use HandlesAuthorization;

    // TODO this should also include "list" and "updateOwn" 

    /**
     * @param User $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->can('create templates');
    }

    /**
     * @param User $user
     * @param Template $template
     * @return bool
     */
    public function delete(User $user, Template $template)
    {
        return $user->can('delete templates');
    }

    /**
     * @param User $user
     * @param Template $template
     * @return bool
     */
    public function update(User $user,  Template $template)
    {
        return $user->can('update templates');
    }
}
