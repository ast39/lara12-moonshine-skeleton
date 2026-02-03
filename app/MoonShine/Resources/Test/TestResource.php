<?php

declare(strict_types=1);

namespace App\MoonShine\Resources\Test;

use App\Models\Api\TestModel;
use App\MoonShine\Resources\Test\Pages\TestFormPage;
use App\MoonShine\Resources\Test\Pages\TestIndexPage;
use MoonShine\Contracts\Core\PageContract;
use MoonShine\Laravel\Resources\ModelResource;
use MoonShine\MenuManager\Attributes\Group;
use MoonShine\MenuManager\Attributes\Order;
use MoonShine\Support\Attributes\Icon;

/**
 * @extends ModelResource<TestModel, TestIndexPage, TestFormPage, null>
 */
#[Icon('document-text')]
#[Group('Контент')]
#[Order(10)]
class TestResource extends ModelResource
{
    protected string $model = TestModel::class;

    protected string $column = 'title';

    public function getTitle(): string
    {
        return 'Тесты';
    }

    /**
     * @return list<class-string<PageContract>>
     */
    protected function pages(): array
    {
        return [
            TestIndexPage::class,
            TestFormPage::class,
        ];
    }

    protected function search(): array
    {
        return ['id', 'title', 'description'];
    }
}
