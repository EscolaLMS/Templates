<?php

namespace EscolaLms\Templates\Tests;

use EscolaLms\Templates\Models\Template;
use EscolaLms\Templates\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use EscolaLms\Templates\Services\Contracts\TemplateServiceContract;
use EscolaLms\Templates\Services\Contracts\VariablesServiceContract;

use EscolaLms\Templates\Tests\Enum\Email\CertificateVar as EmailCertificateVar;
use EscolaLms\Templates\Tests\Enum\Pdf\CertificateVar as PdfCertificateVar;

class ServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->variablesService = resolve(VariablesServiceContract::class);
        $this->templateService = resolve(TemplateServiceContract::class);

        $this->variablesService::addToken(EmailCertificateVar::class, 'email', 'certificates');
        $this->variablesService::addToken(PdfCertificateVar::class, 'pdf', 'certificates');
    }

    public function testServiceAvailableTokens()
    {
        $tokens = $this->variablesService->getAvailableTokens();

        $this->assertTrue(isset($tokens['email']['certificates']));
        $this->assertTrue(isset($tokens['pdf']['certificates']));

        $this->assertIsArray($tokens['email']['certificates']);
        $this->assertIsArray($tokens['pdf']['certificates']);
    }

    public function testMockVariables()
    {
        $tokens = $this->variablesService->getMockVariables('certificates', 'pdf');

        $this->assertIsArray($tokens);

        $this->assertTrue(strtotime($tokens["@VarDateFinished"]) !== false);
    }

    public function testVariableEnumClassName()
    {
        $className = $this->variablesService->getVariableEnumClassName('pdf', 'certificates');
        $this->assertEquals($className, PdfCertificateVar::class);

        $className = $this->variablesService->getVariableEnumClassName('email', 'certificates');
        $this->assertEquals($className, EmailCertificateVar::class);
    }

    public function testGeneratePDF()
    {
        $template = Template::factory()->createOne([
            'type' => 'pdf',
            'vars_set' => 'certificates',
            'content' => 'Date course was finished: @VarDateFinished'
        ]);

        $pdf_filename = $this->templateService->generatePDF($template, ['@VarDateFinished'=>'2022']);

        $this->assertTrue(is_string($pdf_filename));
    }
}
