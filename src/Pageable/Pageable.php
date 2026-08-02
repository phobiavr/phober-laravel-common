<?php

namespace Phobiavr\PhoberLaravelCommon\Pageable;

/**
 * @method static paginateFromRequest(PageableRequest $request)
 */
trait Pageable { // @phpstan-ignore trait.unused
  /**
   * @return PageableBuilder
   */
  public static function query() {
    return parent::query();
  }

  public function newEloquentBuilder($query): PageableBuilder {
    return new PageableBuilder($query);
  }
}
