<?php

namespace EscolaLms\Templates\Http\Resources;

use EscolaLms\Templates\Models\Templatable;
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
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'event' => $this->resource->event,
            'channel' => $this->resource->channel,
            'default' => $this->resource->default,
            'sections' => $this->resource->sections,
            'variable_class' => $this->resource->variable_class,
            'assignable_class' => $this->resource->assignable_class,
            'is_assignable' => $this->resource->is_assignable,
            'assignables' => ($this->resource->is_assignable) ? $this->resource->templatables->map(fn (Templatable $templatable) => ['class' => $templatable->templatable_type, 'id' => $templatable->templatable_id]) : [],
            'created_at' => $this->resource->created_at,
            'updated_at' => $this->resource->updated_at,
        ];
    }
}
