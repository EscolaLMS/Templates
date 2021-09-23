<?php

namespace EscolaLms\Templates\Tests;

use EscolaLms\Templates\Models\Template;
use EscolaLms\Templates\Repository\TemplateRepository;
use EscolaLms\Templates\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TemplatesVariablesTest extends TestCase
{
    use DatabaseTransactions;

    public function testCourseFinishedCreatesCertificate()
    {
        // TODO write this test

        $this->assetTrue(true);
    }
}
