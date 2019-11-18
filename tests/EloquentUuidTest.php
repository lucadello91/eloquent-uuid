<?php

use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Lucadello91\EloquentUuid\UuidModelTrait;
use PHPUnit\Framework\TestCase;

class EloquentUuidTest extends TestCase
{
    /**
     * Tests the creation of model with uuid as primary key.
     *
     * @return void
     */
    public function testCreation()
    {
        $creation = EloquentUserModel::create([
            'username'=> 'username',
            'password'=> 'secret'
        ]);

        static::assertEquals(36, strlen($creation->id));

        $model = EloquentUserModel::first();

        static::assertEquals(36, strlen($model->id));
        static::assertRegExp('/^[0-9a-f-]{36}$/', $model->id);
        static::assertRegExp('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/', $model->id);

        static::assertEquals($creation->id, $model->id);
    }


    public function testRelationshipWithStringUuid()
    {
        $firstUser = EloquentUserModel::create([
            'username'=> 'first-user',
            'password'=> 'secret'
        ]);

        $secondUser = EloquentUserModel::create([
            'username'=> 'second-user',
            'password'=> 'secret'
        ]);

        $postsForFirstUser = [];
        $postsForSecondUser = [];

        for ($i = 0; $i < 10; $i++) {
            $postsForFirstUser[] = new EloquentPostModel([
                'name'=> 'First user - post '.$i,
            ]);

            $postsForSecondUser[] = EloquentPostModel::create([
                'name'   => 'Second user - post '.$i,
                'user_id'=> $secondUser->id,
            ]);
        }

        $firstUser->posts()->saveMany($postsForFirstUser);

        static::assertEquals(10, $firstUser->posts()->count());
        static::assertEquals(10, $secondUser->posts()->count());
    }

    /**
     * Bootstrap Eloquent.
     *
     * @return void
     */
    public static function setUpBeforeClass()
    {

        Eloquent::setConnectionResolver(
            new DatabaseIntegrationTestConnectionResolver()
        );

        Eloquent::setEventDispatcher(
            new Illuminate\Events\Dispatcher()
        );
    }

    /**
     * Tear down Eloquent.
     */
    public static function tearDownAfterClass()
    {
        Eloquent::unsetEventDispatcher();
        Eloquent::unsetConnectionResolver();
    }

    /**
     * Setup the database schema.
     *
     * @return void
     */
    public function setUp()
    {
        $this->schema()->create('users', function ($table) {
            $table->uuid('id');
            $table->string('username');
            $table->string('password');
            $table->timestamps();
            $table->primary('id');
        });

        $this->schema()->create('posts', function ($table) {
            $table->uuid('id');
            $table->string('name');
            $table->uuid('user_id');
            $table->timestamps();
            $table->primary('id');
        });
    }

    /**
     * Tear down the database schema.
     *
     * @return void
     */
    public function tearDown()
    {
        $this->schema()->drop('users');
        $this->schema()->drop('posts');
    }

    /**
     * Get a database connection instance.
     *
     * @return Connection
     */
    protected function connection()
    {
        return Eloquent::getConnectionResolver()->connection();
    }

    /**
     * Get a schema builder instance.
     *
     * @return \Illuminate\Database\Schema\Builder
     */
    protected function schema()
    {
        return $this->connection()->getSchemaBuilder();
    }
}

class EloquentUserModel extends Eloquent
{
    use UuidModelTrait;
    protected $table = 'users';

    protected $guarded = [];

    public function posts()
    {
        return $this->hasMany('EloquentPostModel', 'user_id');
    }
}

class EloquentPostModel extends Eloquent
{
    use UuidModelTrait;
    protected $table = 'posts';

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo('EloquentUserModel', 'user_id');
    }
}

class DatabaseIntegrationTestConnectionResolver implements Illuminate\Database\ConnectionResolverInterface
{
    protected $connection;

    public function connection($name = null)
    {
        if (isset($this->connection)) {
            return $this->connection;
        }

        return $this->connection = new Illuminate\Database\SQLiteConnection(new PDO('sqlite::memory:'));
    }

    public function getDefaultConnection()
    {
        return 'default';
    }

    public function setDefaultConnection($name)
    {
        //
    }
}
