<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
  /**
   * Register services.
   *
   * @return void
   */
  public function register()
  {
    //
  }

  /**
   * Bootstrap services.
   *
   * @return void
   */
  public function boot()
  {
    $verticalMenuJson = file_get_contents(base_path('resources/menu/verticalMenu.json'));
    $adminVerticalMenu = file_get_contents(base_path('resources/menu/adminVerticalMenu.json'));
    $responsableVerticalMenu = file_get_contents(base_path('resources/menu/responsableVerticalMenu.json'));
    $chefServiceVerticalMenu = file_get_contents(base_path('resources/menu/chefServiceVerticalMenu.json'));
    $simpleUserVerticalMenu = file_get_contents(base_path('resources/menu/simpleUserVerticalMenu.json'));

    $verticalMenuData = json_decode($verticalMenuJson);
    $adminVerticalMenuData = json_decode($adminVerticalMenu);
    $responsableVerticalMenuData = json_decode($responsableVerticalMenu);
    $chefServiceVerticalMenuData = json_decode($chefServiceVerticalMenu);
    $simpleUserVerticalMenuData = json_decode($simpleUserVerticalMenu);

    // Share all menuData to all the views
    // \View::share('menuData', [$verticalMenuData]);
    \View::share('verticalMenuData', [$verticalMenuData]);
    \View::share('adminVerticalMenuData', [$adminVerticalMenuData]);
    \View::share('responsableVerticalMenuData', [$responsableVerticalMenuData]);
    \View::share('chefServiceVerticalMenuData', [$chefServiceVerticalMenuData]);
    \View::share('simpleUserVerticalMenuData', [$simpleUserVerticalMenuData]);
  }
}
