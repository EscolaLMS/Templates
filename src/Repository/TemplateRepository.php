<?php

namespace EscolaLms\Templates\Repository;

use EscolaLms\Core\Repositories\BaseRepository;
use EscolaLms\Templates\Models\Template;
use EscolaLms\Templates\Repository\Contracts\TemplateRepositoryContract;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TemplateRepository extends BaseRepository implements TemplateRepositoryContract
{
    public function model()
    {
        return Template::class;
    }

    public function getFieldsSearchable()
    {
        return [];
    }

    public function searchAndPaginate(array $search = [], ?int $perPage = null, string $orderDirection = 'asc', string $orderColumn = 'id'): LengthAwarePaginator
    {
        return $this->allQuery($search)->orderBy($orderColumn, $orderDirection)->paginate($perPage);
    }


    /**
     * @param Template $template
     * @return Template
     */
    public function insert(Template $template): Template
    {
        return $this->createUsingModel($template);
    }

    /**
     * @param int $id
     * @return bool
     */
    public function deleteTemplate(int $id): bool
    {
        $template = $this->find($id);
        if (!$template) {
            return false;
        }
        try {
            return $template->delete();
        } catch (\Exception $err) {
            return false;
        }
    }

    public function save(Template $template): bool
    {
        return $template->save();
    }
}
