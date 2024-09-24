<?php

namespace Abdukhaligov\PhoberLaravelCommon;

use Abdukhaligov\PhoberLaravelCommon\Clients\ConfigClient;
use Abdukhaligov\PhoberLaravelCommon\Commands\UpdateConfigsCommand;
use Abdukhaligov\PhoberLaravelCommon\Commands\UpdateHostnameCommand;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Laravel\Telescope\Telescope;
use Abdukhaligov\PhoberLaravelCommon\Middleware\AuthServerMiddleware;
use Abdukhaligov\PhoberLaravelCommon\Middleware\ForceJsonMiddleware;
use Abdukhaligov\PhoberLaravelCommon\Middleware\OTPGenerateMiddleware;
use Abdukhaligov\PhoberLaravelCommon\Middleware\OTPMiddleware;
use Abdukhaligov\PhoberLaravelCommon\Middleware\PrivateMiddleware;
use Abdukhaligov\PhoberLaravelCommon\Middleware\TranslationMiddleware;

class SharedServiceProvider extends ServiceProvider {
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void {
        try {
            Telescope::ignoreMigrations();
        } catch (\Throwable $exception) {
            Log::error('Error in Telescope migration:', ['message' => $exception->getMessage()]);
        }
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
        $this->loadRoutesFrom((base_path('routes/api.php')));

        $kernel->pushMiddleware(ForceJsonMiddleware::class);
        $kernel->pushMiddleware(TranslationMiddleware::class);
        $router->aliasMiddleware('auth.server', AuthServerMiddleware::class);
        $router->aliasMiddleware('otp', OTPMiddleware::class);
        $router->aliasMiddleware('otp.generate', OTPGenerateMiddleware::class);
        $router->aliasMiddleware('private', PrivateMiddleware::class);

        if (ConfigClient::$runEveryTime) {
            ConfigClient::update(false);
        }

        Auth::extend('json', function ($app, $name, array $config) {
            return new JsonGuard(Auth::createUserProvider($config['provider']), $app->make('request'));
        });

        Config::set('database.connections.db_shared', [
            'driver'   => env('DB_SHARED_CONNECTION', 'mysql'),
            'host'     => env('DB_SHARED_HOST', '127.0.0.1'),
            'port'     => env('DB_SHARED_PORT', '3306'),
            'database' => env('DB_SHARED_DATABASE', 'phober_shared'),
            'username' => env('DB_SHARED_USERNAME', 'forge'),
            'password' => env('DB_SHARED_PASSWORD', ''),
        ]);

        $this->app->useLangPath(__DIR__ . '/../resources/lang');
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
