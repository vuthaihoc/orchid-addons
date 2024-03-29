<?php

declare(strict_types=1);

namespace OrchidAddon\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection as BaseCollection;
use Illuminate\Support\Facades\Crypt;
use Orchid\Platform\Http\Controllers\Controller;
use Orchid\Platform\Http\Requests\RelationRequest;

class AdvancedRelationController extends Controller
{
    /**
     * @param RelationRequest $request
     *
     * @return JsonResponse
     */
    public function view(RelationRequest $request)
    {
        [
            'model'         => $model,
            'name'          => $name,
            'key'           => $key,
            'scope'         => $scope,
            'append'        => $append,
            'searchColumns' => $searchColumns,
        ] = collect($request->all())
            ->except(['search', 'chunk'])
            ->map(static function ($item, $key) {
                if ($item === null) {
                    return null;
                }

                if ($key === 'scope' || $key === 'searchColumns') {
                    return Crypt::decrypt($item);
                }

                return Crypt::decryptString($item);
            });

        /** @var Model $model */
        /** @psalm-suppress UndefinedClass */
        $model = new $model;
        $search = $request->get('search', '');

        $items = $this->buildersItems($model, $name, $key, $search, $scope, $append, $searchColumns, (int)$request->get('chunk', 10));

        return response()->json($items);
    }

    /**
     * @param Model $model
     * @param string $name
     * @param string $key
     * @param string|null $search
     * @param array|null $scope
     * @param string|null $append
     * @param array|null $searchColumns
     * @param int|null $chunk
     *
     * @return mixed
     */
    private function buildersItems(
        Model   $model,
        string  $name,
        string  $key,
        string  $search = null,
        ?array  $scope = [],
        ?string $append = null,
        ?array  $searchColumns = null,
        ?int    $chunk = 10
    )
    {
        if ($scope !== null) {
            $scope['parameters'][] = $search;
            /** @var Collection|array $model */
            $model = $model->{$scope['name']}(...$scope['parameters']);
        }

        if (is_array($model)) {
            $model = collect($model);
        }

        if (is_a($model, BaseCollection::class)) {
            return $model->take($chunk)->pluck($append ?? $name, $key);
        }

        if($scope == null){
            $model = $model->where(function ($query) use ($name, $search, $searchColumns) {
                $query->where($name, 'like', '%' . $search . '%');
                if ($searchColumns !== null) {
                    foreach ($searchColumns as $column) {
                        $query->orWhere($column, 'like', '%' . $search . '%');
                    }
                }
            });
        }

        return $model
            ->limit($chunk)
            ->get()
            ->pluck($append ?? $name, $key);
    }
}
