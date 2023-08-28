# Bootcamp Technical Task – Back-End PHP

## Features

- Using Docker
- Using Sail
- Using background jobs
- Using saved procedure
- Own implementation of command pattern (commands for controllers)
- Own implemetation of Chain of Responsobility (for filters)
- Multiple filters in filtering
- Authentication with JWT tokens


## Requirements

- Doker
- Composer

## Getting Started

To get strated you need two terminals
In first run

``` bash
git clone https://github.com/mymyka/macpaw
cd macpaw
cp .env.example .env
composer install
./vendor/bin/sail up
```

In second terminal run

``` bash
cd macpaw
./vendor/bin/sail artisan migrate:fresh --seed
./vendor/bin/sail artisan schedule:work
```
After minute you should see

``` bash
 INFO  Running scheduled tasks every minute.

2023-08-28 01:24:00 Running [App\Jobs\MakeCollectionsSummary] ... 257ms DONE
```

## Notes

### Comands

In app/Commands/Command.php I made absatract class to implement command pattern.


``` PHP
// app/Commands/Command.php

<?php

namespace App\Commands;

/*
* Base class for all commands
* Implements the Command Pattern
*/
abstract class Command {
    /**
     * Define in subclass
     * and put the logic of a command
     */
    public abstract function execute(): Result;

    /**
     * Called to create instance 
     * of subclass with parameterns 
     * in constructor and execute it
     */
    public static function call(...$args): Result
    {
        $instance = self::create(...$args);
        return $instance->execute();
    }

    /**
     * Creates instance of subclass
     * with arguments in constructor
     */
    private static function create(...$args): self
    {
        return new static(...$args);
    }
}
```
And use it like so
``` PHP
// app/Http/Controllers/Api/V1/UserController.php

public function login(LogInUserRequest $request)
    {
        $result = LogInUserCommand::call($request);
        return response()->json([
                'status' => 'success',
                'user' => $result->user,
                'authorisation' => [
                    'token' => $result->token,
                    'type' => 'bearer',
                ]
            ]);

    }
```

Also I made data class to store result of commands

``` PHP
// app/Commands/Result.php

<?php

namespace App\Commands;

/**
 * Dataclass
 * Contains the result of a command
 * Have dynamic properties
 */
class Result
{
    private $data = [];

    public function __construct()
    {
        $this->data['errors'] = [];
        $this->data['infos'] = [];
        $this->data['warnings'] = [];
    }

    public function __get($name) {
        return $this->data[$name];
    }

    public function __set($name, $value) {
        $this->data[$name] = $value;
        return $this;
    }
}
```

### Filters

Filters implement Chain of Responsibility Pattern

``` PHP
// app/Filters/Filter.php

<?php

namespace App\Filters;

use Illuminate\Database\Query\Builder;

/**
 * Base class for all filters
 * Implements the Chain Of Responsobility Pattern
 */
abstract class Filter
{
    protected ?Filter $nextFilter = null;

    /**
     * Called to select nex filter in the chain
     */
    public function then(Filter $filter): Filter
    {
        $this->nextFilter = $filter;
        return $filter;
    }

    /**
     * Called to call next filter in the chain
     */
    public function nextStep(Builder $data): ?Builder
    {
        if ($this->nextFilter) {
            return $this->nextFilter->filter($data);
        }
        return $data;
    }

    /**
     * Define in subclasses
     * and put the logic of a filter
     */
    public abstract function filter(Builder $data): Builder;
}

```
Also I made Filter Chain Builder, so it is easier to make filter chain

``` PHP
<?php

namespace App\Filters;

/**
 * Builds a chain of filters
 * Implements the Builder Pattern
 */
class FilterChainBuilder
{
    private array $filters;

    public function __construct()
    {
        $this->filters = [];
    }

    public function addFilter(Filter $filter): FilterChainBuilder
    {
        $target_filter = end($this->filters);
        if ($target_filter) {
            $target_filter->then($filter);
        }
        array_push($this->filters, $filter);
        return $this;
    }

    public function build(): array
    {
        return $this->filters;
    }
}

```
In my filter reuqest you can specify multiple filters, and all of them will be ran in order.

``` JSON 
{
    "filters": [
        {
            "name": "activeCollection"
        },
        {
            "name": "sumLeft",
            "sortField": "sum_left",
            "sortOrder": "asc"
        }
    ]
}
```

Also in some filters you can specify sortField and SortOrder.

- sumLeft - "Реалізувати можливість фільтрування зборів за залишеною сумою
до досягнення кінцевої суми." (тут за допомогою параметрів можна вказати поле (по завданню воно "sum_left", але можно будь яке) і з параметром sortOrder можна вказати порядок сортування)

- activeCollection - "Отримати список зборів, які мають суму внесків менше за цільову суму."

### Saved Procedure

When I were making filtering, I considered that we have big database and a lot of users, so I decided to make saved procedure and call it every X seconds (in the code I call it every 5 seconds for testing, but in real situation it would be longer), instaed of making big select staiment with subqueries, in my point it have next advantages:

1. Performance - if we have many records in our database and users make reuqests more then once for X seconds, so it is mach easier for a databse to update table every X seconds, and then take data from it.

2. Security

I have made saved procedure in migration file
``` PHP
// database/migrations/2023_08_27_094142_create_stored_procedures_for_filters.php

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('DROP PROCEDURE IF EXISTS collections_summary_procedure;');
        DB::statement("
            CREATE PROCEDURE collections_summary_procedure()
            BEGIN
                DROP TABLE IF EXISTS contribution_sum;
                
                CREATE TABLE contribution_sum
                SELECT collection_id, SUM(amount) AS total 
                FROM contributors 
                GROUP BY collection_id;

                DROP TABLE IF EXISTS collections_summary;
                CREATE TABLE collections_summary
                SELECT id, 
                    title, 
                    description, 
                    target_amount, 
                    link, 
                    total, 
                    (target_amount - total) AS sum_left 
                FROM contribution_sum INNER JOIN collections 
                    ON contribution_sum.collection_id = collections.id;
            END;");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP PROCEDURE IF EXISTS collections_summary_procedure;');
    }
};
```
### Background Jobs

In order to keep collectins summary up to date, I made background job, it runs every 5 seconds for testing (but in real situation it can be longer) and call saved procedure for making collection summary.

``` PHP
// app/Jobs/MakeCollectionsSummary.php

<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class MakeCollectionsSummary implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        /**
         * Call the stored procedure to make and update the collection summary
         */
        DB::statement('CALL collections_summary_procedure()');
    }
}

```

### Authentication with JWT tokens

In my pet-project I used JWT tokens, and I liked it. So I decided to make authentication with WT tokens here as well.


### SQL

Tasks requires all make with SQL, so commented ORM code in all project and write SQL queries, the both work great.
For example 
``` PHP
// app/Http/Controllers/Api/V1/CollectionController.php

public function store(StoreCollectionRequest $request)
{
    // ORM
    // return new CollectionResource(Collection::create($request->all()));
    
    // SQL
    $result = DB::insert('INSERT INTO collections (title, description, target_amount, link) VALUES (?, ?, ?, ?)', [
        $request->title,
        $request->description,
        $request->targetAmount,
        $request->link
    ]);
    return ($result) ? response()->json(['message' => 'Success'], 200) : response()->json(['error' => 'Something went wrong'], 500);
}
```

## Tasks