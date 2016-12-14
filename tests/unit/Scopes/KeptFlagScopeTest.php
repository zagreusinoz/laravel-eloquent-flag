<?php

/*
 * This file is part of Laravel Eloquent Flag.
 *
 * (c) CyberCog <support@cybercog.su>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cog\Flag\Tests\Unit\Scopes;

use Cog\Flag\Tests\TestCase;
use Cog\Flag\Tests\Stubs\Models\EntityWithKeptFlag;

/**
 * Class KeptFlagScopeTest.
 *
 * @package Cog\Merchant\Tests\Unit\Scopes
 */
class KeptFlagScopeTest extends TestCase
{
    /** @test */
    public function it_can_get_only_kept_models()
    {
        factory(EntityWithKeptFlag::class, 3)->create([
            'is_kept' => true,
        ]);
        factory(EntityWithKeptFlag::class, 2)->create([
            'is_kept' => false,
        ]);

        $entities = EntityWithKeptFlag::all();

        $this->assertCount(3, $entities);
    }

    /** @test */
    public function it_can_get_without_unkept()
    {
        factory(EntityWithKeptFlag::class, 3)->create([
            'is_kept' => true,
        ]);
        factory(EntityWithKeptFlag::class, 2)->create([
            'is_kept' => false,
        ]);

        $entities = EntityWithKeptFlag::withoutUnkept()->get();

        $this->assertCount(3, $entities);
    }

    /** @test */
    public function it_can_get_only_unkept()
    {
        factory(EntityWithKeptFlag::class, 3)->create([
            'is_kept' => true,
        ]);
        factory(EntityWithKeptFlag::class, 2)->create([
            'is_kept' => false,
        ]);

        $entities = EntityWithKeptFlag::onlyUnkept()->get();

        $this->assertCount(2, $entities);
    }

    /** @test */
    public function it_can_keep_model()
    {
        $method = factory(EntityWithKeptFlag::class)->create([
            'is_kept' => false,
        ]);

        EntityWithKeptFlag::where('id', $method->id)->keep();

        $method = EntityWithKeptFlag::where('id', $method->id)->first();

        $this->assertTrue($method->is_kept);
    }

    /** @test */
    public function it_can_deactivate_model()
    {
        $method = factory(EntityWithKeptFlag::class)->create([
            'is_kept' => true,
        ]);

        EntityWithKeptFlag::where('id', $method->id)->unkeep();

        $method = EntityWithKeptFlag::withUnkept()->where('id', $method->id)->first();

        $this->assertFalse($method->is_kept);
    }
}