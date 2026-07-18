<?php

namespace Phobiavr\PhoberLaravelCommon\Traits;

trait AuthUserArrayable {
    public function toAuthUserArray(): array {
        return [
            self::FIELD_ID         => $this->getId(),
            self::FIELD_USERNAME   => $this->getUsername(),
            self::FIELD_FIRST_NAME => $this->getFirstName(),
            self::FIELD_LAST_NAME  => $this->getLastName(),
            self::FIELD_EMAIL      => $this->getEmail(),
        ];
    }
}
