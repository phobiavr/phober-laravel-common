<?php

namespace Phobiavr\PhoberLaravelCommon\Pageable;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use JsonSerializable;

/**
 * @property \Illuminate\Support\Collection<array-key, mixed> $collection
 * @mixin LengthAwarePaginator<int, mixed>
 */
class PageableCollection extends ResourceCollection
{

    /**
     * PaginationCollection constructor.
     *
     * @param mixed $resource
     * @param string|null $collects JsonResource
     */
    public function __construct($resource, string $collects = null)
    {
        if ($collects) {
            $this->collects = $collects;
        }

        parent::__construct($resource);
    }

    /**
     * Transform the resource collection into an array.
     *
     * @param Request $request
     * @return array<string, mixed>|Arrayable<array-key, mixed>|JsonSerializable
     */
    public function toArray($request): array|JsonSerializable|Arrayable
    {
        return [
            'data' => $this->collection,
            'total' => $this->total(),
            'size' => $this->perPage(),
            'current_page' => $this->currentPage(),
            'total_pages' => $this->lastPage(),
        ];
    }
}
