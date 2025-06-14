<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //Model::automaticallyEagerLoadRelationships();

        if (request()->has('lang')) {
            App::setLocale(request()->get('lang'));
        }

        $asset_v = config('constants.asset_version', 1);
        View::share('asset_v', $asset_v);

        // Share the list of modules enabled in sidebar
        View::composer(
            ['*'],
            function ($view) {
                $enabled_modules = !empty(session('business.enabled_modules')) ? session('business.enabled_modules') : [];

                $view->with('enabled_modules', $enabled_modules);
            }
        );

        //This will fix "Specified key was too long; max key length is 767 bytes issue during migration"
        Schema::defaultStringLength(191);

        //Blade directive to format number into required format.
        Blade::directive('num_format', function ($expression) {
            return "number_format($expression, config('constants.currency_precision', 2), session('currency')['decimal_separator'], session('currency')['thousand_separator'])";
        });

        //Blade directive to format quantity values into required format.
        Blade::directive('format_quantity', function ($expression) {
            return "number_format($expression, config('constants.quantity_precision', 2), session('currency')['decimal_separator'], session('currency')['thousand_separator'])";
        });

        //Blade directive to return appropiate class according to transaction status
        Blade::directive('transaction_status', function ($status) {
            return "<?php if($status == 'ordered'){
                echo 'bg-aqua';
            }elseif($status == 'pending'){
                echo 'bg-red';
            }elseif ($status == 'received') {
                echo 'bg-light-green';
            }?>";
        });

        //Blade directive to return appropiate class according to transaction status
        Blade::directive('payment_status', function ($status) {
            return "<?php if($status == 'partial'){
                echo 'bg-aqua';
            }elseif($status == 'due'){
                echo 'bg-red';
            }elseif ($status == 'paid') {
                echo 'bg-light-green';
            }?>";
        });

        //Blade directive to display help text.
        Blade::directive('show_tooltip', function ($message) {
            return "<?php
                if(session('business.enable_tooltip')){
                    echo '<i class=\"fa fa-info-circle text-info hover-q no-print \" aria-hidden=\"true\"
                    data-container=\"body\" data-toggle=\"popover\" data-placement=\"auto bottom\"
                    data-content=\"' . $message . '\" data-html=\"true\" data-trigger=\"hover\"></i>';
                }
                ?>";
        });

        //Blade directive to convert.
        Blade::directive('format_date', function ($date) {
            if (!empty($date)) {
                return "<?php echo Carbon\\Carbon::createFromTimestamp(strtotime($date))->format(session('business.date_format')) ?>";
            } else {
                return null;
            }
        });

        //Blade directive to convert.
        Blade::directive('format_time', function ($date) {
            if (!empty($date)) {
                $time_format = 'h:i A';
                if (session('business.time_format') == 24) {
                    $time_format = 'H:i';
                }
                return Carbon::createFromTimestamp(strtotime($date))->format('$time_format');
            } else {
                return null;
            }
        });

        if (config('app.debug')) {
            DB::listen(function ($query) {
                if ($query->time > 100) {
                    Log::channel('queries')->info(
                        'Slow query: ' . $query->sql,
                        [
                            'time' => $query->time,
                            'bindings' => $query->bindings
                        ]
                    );
                }
            });
        }

        $this->registerCommands();
    }

    /**
     * Register commands.
     *
     * @return void
     */
    protected function registerCommands()
    {
        //
    }
}
