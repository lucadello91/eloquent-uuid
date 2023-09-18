<?php

namespace Lucadello91\EloquentUuid\Tests;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Events\Dispatcher;
use Lucadello91\EloquentUuid\Tests\Models\EloquentPostBinaryModel;
use Lucadello91\EloquentUuid\Tests\Models\EloquentPostModel;
use Lucadello91\EloquentUuid\Tests\Models\EloquentUserBinaryModel;
use Lucadello91\EloquentUuid\Tests\Models\EloquentUserModel;
use Ramsey\Uuid\Uuid;

class EloquentUuidTest extends TestCase
{
    /**
     * Bootstrap Eloquent.
     *
     * @return void
     */
    public static function setUpBeforeClass(): void
    {
        Eloquent::setEventDispatcher(
            new Dispatcher()
        );
    }

    /**
     * Tear down Eloquent.
     */
    public static function tearDownAfterClass(): void
    {
        Eloquent::unsetEventDispatcher();
    }

    /**
     * Tests the creation of model with uuid as primary key.
     *
     * @return void
     */
    public function testCreationStringUuid()
    {
        $creation = EloquentUserModel::create([
            'username' => 'username',
            'password' => 'secret',
        ]);

        static::assertEquals(36, strlen($creation->id));

        $model = EloquentUserModel::first();

        static::assertEquals(36, strlen($model->id));

        static::assertEquals($creation->id, $model->id);
    }

    public function testRelationshipWithStringUuid()
    {
        $firstUser = EloquentUserModel::create([
            'username' => 'first-user',
            'password' => 'secret',
        ]);

        $secondUser = EloquentUserModel::create([
            'username' => 'second-user',
            'password' => 'secret',
        ]);

        $postsForFirstUser = [];
        $postsForSecondUser = [];

        for ($i = 0; $i < 10; $i++) {
            $postsForFirstUser[] = new EloquentPostModel([
                'name' => 'First user - post ' . $i,
            ]);

            $postsForSecondUser[] = EloquentPostModel::create([
                'name'    => 'Second user - post ' . $i,
                'user_id' => $secondUser->id,
            ]);
        }

        $firstUser->posts()->saveMany($postsForFirstUser);

        static::assertEquals(10, $firstUser->posts()->count());
        static::assertEquals(10, $secondUser->posts()->count());
    }

    public function testCreationStringBinaryUuidWithKeyType()
    {
        $creation = EloquentUserBinaryModel::create([
            'username' => 'username',
            'password' => 'secret',
        ]);

        static::assertEquals(36, strlen($creation->id));

        $model = EloquentUserBinaryModel::first();

        static::assertEquals(36, strlen($model->id));

        static::assertEquals($creation->id, $model->id);
    }

    public function testCreationStringBinaryUuidWithCast()
    {
        $creation = EloquentPostBinaryModel::create([
            'name'    => 'Post Example',
            'user_id' => Uuid::uuid4()->toString(),
        ]);

        static::assertEquals(36, strlen($creation->id));

        $model = EloquentPostBinaryModel::first();

        static::assertEquals(36, strlen($model->id));

        static::assertEquals($creation->id, $model->id);
    }

    public function testRelationshipWithBinaryUuid()
    {
        $firstUser = EloquentUserBinaryModel::create([
            'username' => 'first-user',
            'password' => 'secret',
        ]);

        $secondUser = EloquentUserBinaryModel::create([
            'username' => 'second-user',
            'password' => 'secret',
        ]);

        $postsForFirstUser = [];
        $postsForSecondUser = [];

        for ($i = 0; $i < 10; $i++) {
            $postsForFirstUser[] = new EloquentPostBinaryModel([
                'name' => 'First user - post ' . $i,
            ]);

            $postsForSecondUser[] = EloquentPostBinaryModel::create([
                'name'    => 'Second user - post ' . $i,
                'user_id' => $secondUser->id,
            ]);
        }

        $firstUser->posts()->saveMany($postsForFirstUser);

        static::assertEquals(10, $firstUser->posts()->count());
        static::assertEquals(10, $secondUser->posts()->count());
    }
}
