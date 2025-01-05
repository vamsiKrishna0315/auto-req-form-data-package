<?php

namespace Vamsi\AutoFormRequestData;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Vamsi\AutoFormRequestData\Commands\AutoFormRequestDataGenerateCommand;
use vamsi\AutoFormRequestData\AutoReqData;

class AutoReqDataServiceProvider extends PackageServiceProvider
{
    function register()
    {
        $this->commands([
            AutoFormRequestDataGenerateCommand::class,
        ]);

        $this->app->bind(AutoReqData::class, function () {
            return new AutoReqData();
        });
    }
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('auto-req-data')
            ->hasCommand(AutoFormRequestDataGenerateCommand::class);
    }
}
