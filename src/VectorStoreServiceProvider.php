<?php

namespace AdrianTanase\VectorStore;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class VectorStoreServiceProvider extends PackageServiceProvider
{
	public function configurePackage(Package $package): void
	{
		$package
			->name('vector-store')
			->hasConfigFile('vector-store');
	}

	public function packageRegistered()
	{
		parent::packageRegistered();
	}

	public function provides(): array
	{
		return [];
	}
}
