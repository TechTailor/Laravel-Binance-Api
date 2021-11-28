<?php

namespace TechTailor\BinanceApi;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class BinanceApiServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('binance-api')
            ->hasConfigFile();
    }

    public function packageRegistered()
    {
        //
    }
}
