<?php

namespace EscolaLms\Templates\Tests\Feature;

use EscolaLms\Templates\Enum\EmptyVariableSet;
use EscolaLms\Templates\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EmptyVariableSetTest extends TestCase
{
    use DatabaseTransactions;

    public function testEmptyVariableSet()
    {
        $this->assertEquals([], EmptyVariableSet::getRequiredVariables());
        $this->assertEquals([], EmptyVariableSet::getMockVariables());
        $this->assertEquals([], EmptyVariableSet::getVariablesFromContent());
        $this->assertTrue(EmptyVariableSet::isValid(''));
    }
}
