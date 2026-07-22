<?php

namespace Phobiavr\PhoberLaravelCommon\Pageable;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class PageableBuilder extends Builder {
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
