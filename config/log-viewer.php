<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Log Viewer
    |--------------------------------------------------------------------------
    | Log Viewer can be disabled, so it's no longer accessible via browser.
    |
    */

    'enabled' => env('LOG_VIEWER_ENABLED', true),
    'show_support_link' => false,

    /*
    |--------------------------------------------------------------------------
    | Log Viewer Domain
    |--------------------------------------------------------------------------
    | You may change the domain where Log Viewer should be active.
    | If the domain is empty, all domains will be valid.
    |
    */

    'route_domain' => null,

    /*
    |--------------------------------------------------------------------------
    | Log Viewer Route
    |--------------------------------------------------------------------------
    | Log Viewer will be available under this URL.
    |
    */

    'route_path' => 'log-viewer',

    /*
    |--------------------------------------------------------------------------
    | Back to system URL
    |--------------------------------------------------------------------------
    | When set, displays a link to easily get back to this URL.
    | Set to `null` to hide this link.
    |
    | Optional label to display for the above URL.
    |
    */

    'back_to_system_url' => config('app.url', null),

    'back_to_system_label' => null, // Displayed by default: "Back to {{ app.name }}"

    /*
    |--------------------------------------------------------------------------
    | Log Viewer route middleware.
    |--------------------------------------------------------------------------
    | Optional middleware to use when loading the initial Log Viewer page.
    |
    */

    'middleware' => ['web', 'auth:web'],

    /*
    |--------------------------------------------------------------------------
    | Log Viewer API middleware.
    |--------------------------------------------------------------------------
    | Optional middleware to use on every API request. The same API is also
    | used from within the Log Viewer user interface.
    |
    */

    'api_middleware' => [],

    /*
    |--------------------------------------------------------------------------
    | Include file patterns
    |--------------------------------------------------------------------------
    |
    */

    'include_files' => [
        '*.log',
        '**/*.log',
        // '/absolute/paths/supported',
    ],

    /*
    |--------------------------------------------------------------------------
    | Exclude file patterns.
    |--------------------------------------------------------------------------
    | This will take precedence over included files.
    |
    */

    'exclude_files' => [
        // 'my_secret.log'
    ],

    /*
    |--------------------------------------------------------------------------
    |  Shorter stack trace filters.
    |--------------------------------------------------------------------------
    | Lines containing any of these strings will be excluded from the full log.
    | This setting is only active when the function is enabled via the user interface.
    |
    */

    'shorter_stack_trace_excludes' => [
        '/vendor/symfony/',
        '/vendor/laravel/framework/',
        '/vendor/barryvdh/laravel-debugbar/',
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache driver
    |--------------------------------------------------------------------------
    | Cache driver to use for storing the log indices. Indices are used to speed up
    | log navigation. Defaults to your application's default cache driver.
    |
    */

    'cache_driver' => env('LOG_VIEWER_CACHE_DRIVER', null),

    /*
    |--------------------------------------------------------------------------
    | Chunk size when scanning log files lazily
    |--------------------------------------------------------------------------
    | The size in MB of files to scan before updating the progress bar when searching across all files.
    |
    */

    'lazy_scan_chunk_size_in_mb' => 200,
];
