<?php

namespace Phobiavr\PhoberLaravelCommon\Pageable;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @template TModel of Model
 * @extends Builder<TModel>
 */
class PageableBuilder extends Builder {
  /** @return LengthAwarePaginator<int, TModel> */
  public function paginateFromRequest(PageableRequest $request): LengthAwarePaginator {
    $pagination = $request->pagination();

    return $this->paginate(
      $pagination['perPage'],
      $pagination['columns'],
      $pagination['pageName'],
      $pagination['page'],
    );
  }
}
