<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use App\Models\ArchiveComment;
use MoonShine\Laravel\Enums\Ability;
use MoonShine\Laravel\Resources\ModelResource;
use Illuminate\Contracts\Database\Eloquent\Builder;
use App\MoonShine\Pages\ArchiveComment\ArchiveCommentFormPage;
use App\MoonShine\Pages\ArchiveComment\ArchiveCommentIndexPage;
use App\MoonShine\Pages\ArchiveComment\ArchiveCommentDetailPage;

/**
 * @extends ModelResource<ArchiveComment, ArchiveCommentIndexPage, ArchiveCommentFormPage, ArchiveCommentDetailPage>
 */
final class ArchiveCommentResource extends ModelResource
{
    protected string $model = ArchiveComment::class;

    protected string $title = 'ArchiveComments';

    //    protected function modifyQueryBuilder(Builder $builder): Builder
    //    {
    //        if (!request()->has('aggregated')) {
    //            return $builder->whereRaw('1 = 0');
    //        }
    //
    //        return $builder;
    //    }

    /**
     * @return list<class-string<\MoonShine\Contracts\Core\PageContract>>
     */
    protected function pages(): array
    {
        return [
            ArchiveCommentIndexPage::class,
            ArchiveCommentFormPage::class,
            ArchiveCommentDetailPage::class,
        ];
    }

    /**
     * @param  ArchiveComment  $item
     * @return array<string, string[]|string>
     *
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    protected function rules(mixed $item): array
    {
        return [];
    }

    protected function modifyQueryBuilder(Builder $builder): Builder
    {
        $year = request('year') ?? session('archive_year');

        if ($year) {
            session(['archive_year' => $year]);
        }

        if (!$year || !request()->has('aggregated')) {
            return $builder->whereRaw('1 = 0');
        }

        return ArchiveComment::fromYear($year)->newQuery()->with('user');
    }

    public function findItem(bool $orFail = false): mixed
    {
        $id = request()->route('resourceItem');
        $year = request('year') ?? session('archive_year');

        $query = ArchiveComment::fromYear($year)->newQuery();

        return $orFail
            ? $query->findOrFail($id)
            : $query->find($id);
    }

    public function can(Ability|string $ability): bool
    {
        return auth()->user()->isAdmin()
            || auth()->user()->isHavePermission(self::class, $ability);
    }
}
