<?php

namespace Phobiavr\PhoberLaravelCommon;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;

class JsonGuard implements Guard {
    protected Request $request;
    protected UserProvider $provider;
    protected ?Authenticatable $user;

    /**
     * Create a new authentication guard.
     */
    public function __construct(UserProvider $provider, Request $request) {
        $this->request = $request;
        $this->provider = $provider;
        $this->user = NULL;
    }

    /**
     * Determine if the current user is authenticated.
     */
    public function check(): bool {
        return !is_null($this->user());
    }

    /**
     * Determine if the current user is a guest.
     */
    public function guest(): bool {
        return !$this->check();
    }

    /**
     * Get the currently authenticated user.
     */
    public function user(): ?Authenticatable {
        if (!is_null($this->user)) {
            return $this->user;
        }

        return null;
    }

    /**
     * Get the ID for the currently authenticated user.
     */
    public function id() {
        return $this->user()?->id;
    }

    /**
     * This guard never authenticates by local credentials — the user is always
     * set externally via setUser() after AuthServerMiddleware validates the
     * bearer token against auth-server. $provider is unused for that reason.
     *
     * @param array<string, mixed> $credentials
     */
    public function validate(array $credentials = []): bool {
        return false;
    }

    /**
     * Set the current user.
     */
    public function setUser(Authenticatable $user): static {
        $this->user = $user;

        return $this;
    }

    /**
     * Determine if the guard has a user instance.
     */
    public function hasUser(): bool {
        return !is_null($this->user);
    }
}
