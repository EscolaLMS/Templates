<?php

namespace EscolaLms\Templates\Tests;

use EscolaLms\Core\Enums\UserRole;
use EscolaLms\Templates\Database\Seeders\PermissionTableSeeder;
use EscolaLms\Templates\EscolaLmsTemplatesServiceProvider;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Passport\PassportServiceProvider;
use Spatie\Permission\PermissionServiceProvider;

class TestCase extends \EscolaLms\Core\Tests\TestCase
{
    use DatabaseTransactions;

    public $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(PermissionTableSeeder::class);
    }

    protected function getPackageProviders($app): array
    {
        return [
            ...parent::getPackageProviders($app),
            PassportServiceProvider::class,
            PermissionServiceProvider::class,
            EscolaLmsTemplatesServiceProvider::class,
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
        $this->user->assignRole(UserRole::ADMIN);
    }
}
