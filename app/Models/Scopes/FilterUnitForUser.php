<?php

declare(strict_types=1);

namespace App\Models\Scopes;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Builder;

final readonly class FilterUnitForUser implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        $builder->when(
            auth()->user() instanceof User && auth()->user()->moonshine_user_role_id !== 1,
            fn (Builder $builder) => $builder->whereRelation('users', 'id', '=', auth()->id())
        );
    }
}
