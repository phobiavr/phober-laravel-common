<?php

namespace Phobiavr\PhoberLaravelCommon\Pageable;

/**
 * @method static PageableBuilder<static> paginateFromRequest(PageableRequest $request)
 */
trait Pageable { // @phpstan-ignore trait.unused
  /** @return PageableBuilder<static> */
  public static function query() {
    /** @var PageableBuilder<static> $builder */
    $builder = parent::query();

    return $builder;
  }

  /** @return PageableBuilder<static> */
  public function newEloquentBuilder($query): PageableBuilder {
    /** @var PageableBuilder<static> */
    return new PageableBuilder($query);
  }
}
