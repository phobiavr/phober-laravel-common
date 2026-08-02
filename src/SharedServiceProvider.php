<?php

namespace Phobiavr\PhoberLaravelCommon;

use Illuminate\Foundation\Support\Providers\EventServiceProvider;
use Phobiavr\PhoberLaravelCommon\Clients\ConfigClient;
use Phobiavr\PhoberLaravelCommon\Commands\UpdateConfigsCommand;
use Phobiavr\PhoberLaravelCommon\Commands\UpdateHostnameCommand;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
//use Laravel\Telescope\Telescope;
use Phobiavr\PhoberLaravelCommon\Logging\TraceIdProcessor;
use Phobiavr\PhoberLaravelCommon\Middleware\AuthServerMiddleware;
use Phobiavr\PhoberLaravelCommon\Middleware\ForceJsonMiddleware;
use Phobiavr\PhoberLaravelCommon\Middleware\IdempotencyMiddleware;
use Phobiavr\PhoberLaravelCommon\Middleware\OTPGenerateMiddleware;
use Phobiavr\PhoberLaravelCommon\Middleware\OTPMiddleware;
use Phobiavr\PhoberLaravelCommon\Middleware\PrivateMiddleware;
use Phobiavr\PhoberLaravelCommon\Middleware\TraceRequestMiddleware;
use Phobiavr\PhoberLaravelCommon\Middleware\TranslationMiddleware;

class SharedServiceProvider extends ServiceProvider {
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void {
        $this->mergeConfigFrom(__DIR__ . '/../config/features.php', 'features');
        $this->mergeConfigFrom(__DIR__ . '/../config/service.php', 'service');
    }

    /**
     * Bootstrap any application services.
     *
     * @param Router $router
     * @param Kernel $kernel
     * @return void
     */
    public function boot(Router $router, Kernel $kernel): void {
        $this->registerCommands();

        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');

        $kernel->pushMiddleware(TraceRequestMiddleware::class);
        $kernel->pushMiddleware(ForceJsonMiddleware::class);
        $kernel->pushMiddleware(TranslationMiddleware::class);

        Log::pushProcessor(new TraceIdProcessor());
        $router->aliasMiddleware('auth.server', AuthServerMiddleware::class);
        $router->aliasMiddleware('otp', OTPMiddleware::class);
        $router->aliasMiddleware('otp.generate', OTPGenerateMiddleware::class);
        $router->aliasMiddleware('private', PrivateMiddleware::class);
        $router->aliasMiddleware('idempotent', IdempotencyMiddleware::class);

        if (ConfigClient::$runEveryTime) {
            ConfigClient::update(false);
        }

        Auth::extend('json', function ($app, $name, array $config) {
            $provider = Auth::createUserProvider($config['provider'] ?? null);

            if (!$provider) {
                throw new \InvalidArgumentException("Auth guard [{$name}] has no configured user provider.");
            }

            return new JsonGuard($provider, $app->make('request'));
        });

        Config::set('database.connections.db_shared', [
            'driver'   => env('DB_SHARED_CONNECTION', 'mysql'), // @phpstan-ignore larastan.noEnvCallsOutsideOfConfig
            'host'     => env('DB_SHARED_HOST', '127.0.0.1'), // @phpstan-ignore larastan.noEnvCallsOutsideOfConfig
            'port'     => env('DB_SHARED_PORT', '3306'), // @phpstan-ignore larastan.noEnvCallsOutsideOfConfig
            'database' => env('DB_SHARED_DATABASE', 'phober_shared'), // @phpstan-ignore larastan.noEnvCallsOutsideOfConfig
            'username' => env('DB_SHARED_USERNAME', 'forge'), // @phpstan-ignore larastan.noEnvCallsOutsideOfConfig
            'password' => env('DB_SHARED_PASSWORD', ''), // @phpstan-ignore larastan.noEnvCallsOutsideOfConfig
        ]);

        $this->app->useLangPath(__DIR__ . '/../resources/lang');

        EventServiceProvider::disableEventDiscovery();
    }

    /**
     * Register the package's commands.
     *
     * @return void
     */
    protected function registerCommands(): void {
        $this->commands([
            UpdateHostnameCommand::class,
            UpdateConfigsCommand::class,
        ]);
    }
}
