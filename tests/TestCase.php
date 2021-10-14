<?php

namespace EscolaLms\Templates\Tests;

use EscolaLms\Core\Models\User;
use EscolaLms\Templates\AuthServiceProvider;
use EscolaLms\Templates\Database\Seeders\PermissionTableSeeder;
use EscolaLms\Templates\EscolaLmsTemplatesServiceProvider;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Passport\PassportServiceProvider;
use Spatie\Permission\PermissionServiceProvider;
use EscolaLms\Templates\Services\Contracts\VariablesServiceContract;
use EscolaLms\Templates\Services\VariablesService;
use EscolaLms\Templates\Tests\Enum\Email\CertificateVar as EmailCertificateVar;
use EscolaLms\Templates\Tests\Enum\Pdf\CertificateVar as PdfCertificateVar;


class TestCase extends \EscolaLms\Core\Tests\TestCase
{
    use DatabaseTransactions;

    public $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(PermissionTableSeeder::class);
        $variablesService = resolve(VariablesServiceContract::class);
        $variablesService::addToken(EmailCertificateVar::class, 'email', 'certificates');
        $variablesService::addToken(PdfCertificateVar::class, 'pdf', 'certificates');

    }

    protected function getPackageProviders($app): array
    {
        return [
            ...parent::getPackageProviders($app),
            EscolaLmsTemplatesServiceProvider::class,
            PassportServiceProvider::class,
            PermissionServiceProvider::class,
            AuthServiceProvider::class
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);
        $app['config']->set('mail.driver', 'log');
    }

    protected function authenticateAsAdmin()
    {
        $this->user = config('auth.providers.users.model')::factory()->create();        
        $this->user->guard_name = 'api';
        $this->user->givePermissionTo('create templates');
        $this->user->givePermissionTo('update templates');
        $this->user->givePermissionTo('delete templates');
    }
}
