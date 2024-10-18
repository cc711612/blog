<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Laravel\Telescope\IncomingEntry;
use Laravel\Telescope\Telescope;
use Laravel\Telescope\TelescopeApplicationServiceProvider;

class TelescopeServiceProvider extends TelescopeApplicationServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Telescope::night();

        $this->hideSensitiveRequestDetails();


        Telescope::filter(function (IncomingEntry $entry) {
            $blockUri = [];
            $blockHttpStatus = [404, 405, 301, 302];
            $host = request()->getHost();
            
            // 檢查主機是否為 IP 地址
            if (filter_var($host, FILTER_VALIDATE_IP)) {
                return false;
            }

            if ($entry->type === 'request' && in_array($entry->content['response_status'], $blockHttpStatus)) {
                return false;
            }
            
            return config('telescope.record.enabled')
                && !in_array(request()->getRequestUri(), $blockUri)
                && request()->method() != 'OPTIONS';
        });
    }

    /**
     * Prevent sensitive request details from being logged by Telescope.
     */
    protected function hideSensitiveRequestDetails(): void
    {
        Telescope::hideRequestParameters(['_token']);

        Telescope::hideRequestHeaders([
            'cookie',
            'x-csrf-token',
            'x-xsrf-token',
        ]);
    }

    /**
     * Register the Telescope gate.
     *
     * This gate determines who can access Telescope in non-local environments.
     */
    protected function gate(): void
    {
        Gate::define('viewTelescope', function ($user) {
            return in_array($user->email, [
                'cc711612@gmail.com'
            ]);
        });
    }
}
