<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Snappy PDF / Image Configuration
    |--------------------------------------------------------------------------
    |
    | This option contains settings for PDF generation.
    |
    | Enabled:
    |
    |    Whether to load PDF / Image generation.
    |
    | Binary:
    |
    |    The file path of the wkhtmltopdf / wkhtmltoimage executable.
    |
    | Timout:
    |
    |    The amount of time to wait (in seconds) before PDF / Image generation is stopped.
    |    Setting this to false disables the timeout (unlimited processing time).
    |
    | Options:
    |
    |    The wkhtmltopdf command options. These are passed directly to wkhtmltopdf.
    |    See https://wkhtmltopdf.org/usage/wkhtmltopdf.txt for all options.
    |
    | Env:
    |
    |    The environment variables to set while running the wkhtmltopdf process.
    |
    */

    'pdf' => [
        'enabled' => true,
        // 'binary'  => env('WKHTML_PDF_BINARY', base_path('vendor/h4cc/wkhtmltopdf-amd64/bin/wkhtmltopdf-amd64')),
        'binary'  => env('WKHTML_PDF_BINARY', '"D:\soft\wkhtmltopdf\bin\wkhtmltopdf.exe"'),
        'timeout' => false,
        'options' => [],
        'env'     => [],
    ],

    'image' => [
        'enabled' => false,
        // 'binary'  => env('WKHTML_IMG_BINARY', 'vendor/h4cc/wkhtmltoimage-amd64/bin/wkhtmltopdf-amd64'),
        'binary'  => env('WKHTML_IMG_BINARY', '"D:\soft\wkhtmltopdf\bin\wkhtmltoimage.exe"'),
        'timeout' => false,
        'options' => [],
        'env'     => [],
    ],

];
