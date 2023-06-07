<?php

namespace App\Providers;

use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Define a macro called 'search' on the query builder instance
        Builder::macro('search', function (string|array $attributes, string|array|int $searchTerm) {
            // Add a 'where' clause to the query builder
            $this->where(function (Builder $query) use ($attributes, $searchTerm) {
                // Loop through each attribute to search for
                foreach ((!is_array($attributes) ? Arr::wrap($attributes) : $attributes) as $attribute) {
                    // Check if the attribute is a relation
                    $query->when(
                        str_contains($attribute, '.'),
                        function (Builder $query) use ($attribute, $searchTerm) {
                            // Split the attribute into relation and column parts
                            $array = explode('.', $attribute);
                            $length = count($array);
                            $column = $array[$length - 1];
                            (array)array_pop($array);
                            $relation = implode('.', $array);
                            // Handle different types of search terms
                            if (is_int($searchTerm)) {
                                $query->orWhereRelation($relation, $column, "=", "{$searchTerm}");
                            }
                            if (is_array($searchTerm)) {
                                $query->where(function (Builder $query) use ($relation, $column, $searchTerm) {
                                    foreach ($searchTerm as $item) {
                                        $query->orWhereRelation($relation, $column, "=", "{$item}");
                                    }
                                });
                            }
                            if (is_string($searchTerm)) {
                                $query->orWhereRelation($relation, $column, 'LIKE', "%{$searchTerm}%");
                            }
                        },
                        // If the attribute is not a relation, add a simple 'LIKE' clause
                        function (Builder $query) use ($attribute, $searchTerm) {
                            $query->orWhere($attribute, 'LIKE', "%{$searchTerm}%");
                        }
                    );
                }
            });
            // Return the query builder instance
            return $this;
        });
    }
}
