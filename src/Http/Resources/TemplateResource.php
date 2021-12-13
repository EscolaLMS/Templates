<?php

namespace EscolaLms\Templates\Http\Resources;

use EscolaLms\Templates\Models\Template;
use Illuminate\Http\Resources\Json\JsonResource;

class TemplateResource extends JsonResource
{
    public function __construct(Template $template)
    {
        $this->resource = $template;
    }

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'event' => $this->event,
            'channel' => $this->channel,
            'default' => $this->default,
            'assignable' => $this->assignable,
            'sections' => $this->sections,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
