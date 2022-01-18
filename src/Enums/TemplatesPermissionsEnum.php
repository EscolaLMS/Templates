<?php

namespace EscolaLms\Templates\Enums;

use EscolaLms\Core\Enums\BasicEnum;

class TemplatesPermissionsEnum extends BasicEnum
{
    const TEMPLATES_CREATE = 'templates_create';
    const TEMPLATES_DELETE = 'templates_delete';
    const TEMPLATES_UPDATE = 'templates_update';
    const TEMPLATES_LIST = 'templates_list';
    const TEMPLATES_READ = 'templates_read';
}
