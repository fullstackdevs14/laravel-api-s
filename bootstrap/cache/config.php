<?php return array (
  'auth' => 
  array (
    'defaults' => 
    array (
      'guard' => 'web',
      'passwords' => 'users',
    ),
    'guards' => 
    array (
      'web' => 
      array (
        'driver' => 'session',
        'provider' => 'users',
      ),
      'api-applicationUser' => 
      array (
        'driver' => 'jwt.auth',
        'provider' => 'applicationUser',
      ),
      'api-partner' => 
      array (
        'driver' => 'jwt.auth',
        'provider' => 'partner',
      ),
    ),
    'providers' => 
    array (
      'users' => 
      array (
        'driver' => 'eloquent',
        'model' => 'App\\User',
      ),
      'applicationUser' => 
      array (
        'driver' => 'eloquent',
        'model' => 'App\\ApplicationUser',
      ),
      'partner' => 
      array (
        'driver' => 'eloquent',
        'model' => 'App\\ApplicationUser',
      ),
    ),
    'passwords' => 
    array (
      'users' => 
      array (
        'provider' => 'users',
        'table' => 'password_resets',
        'expire' => 60,
      ),
    ),
  ),
  'app' => 
  array (
    'name' => 'ApplicationAdmin',
    'env' => 'production',
    'debug' => false,
    'url' => 'http://localhost',
    'timezone' => 'Europe/Paris',
    'locale' => 'fr',
    'fallback_locale' => 'fr',
    'key' => 'base64:HhjlJ77RLFLqhzumV8QCxk9zbL3qE69m/hIxxWn7o64=',
    'cipher' => 'AES-256-CBC',
    'log' => 'single',
    'log_level' => 'debug',
    'providers' => 
    array (
      0 => 'Illuminate\\Auth\\AuthServiceProvider',
      1 => 'Illuminate\\Broadcasting\\BroadcastServiceProvider',
      2 => 'Illuminate\\Bus\\BusServiceProvider',
      3 => 'Illuminate\\Cache\\CacheServiceProvider',
      4 => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
      5 => 'Illuminate\\Cookie\\CookieServiceProvider',
      6 => 'Illuminate\\Database\\DatabaseServiceProvider',
      7 => 'Illuminate\\Encryption\\EncryptionServiceProvider',
      8 => 'Illuminate\\Filesystem\\FilesystemServiceProvider',
      9 => 'Illuminate\\Foundation\\Providers\\FoundationServiceProvider',
      10 => 'Illuminate\\Hashing\\HashServiceProvider',
      11 => 'Illuminate\\Mail\\MailServiceProvider',
      12 => 'Illuminate\\Notifications\\NotificationServiceProvider',
      13 => 'Illuminate\\Pagination\\PaginationServiceProvider',
      14 => 'Illuminate\\Pipeline\\PipelineServiceProvider',
      15 => 'Illuminate\\Queue\\QueueServiceProvider',
      16 => 'Illuminate\\Redis\\RedisServiceProvider',
      17 => 'Illuminate\\Auth\\Passwords\\PasswordResetServiceProvider',
      18 => 'Illuminate\\Session\\SessionServiceProvider',
      19 => 'Illuminate\\Translation\\TranslationServiceProvider',
      20 => 'Illuminate\\Validation\\ValidationServiceProvider',
      21 => 'Illuminate\\View\\ViewServiceProvider',
      22 => 'Laravel\\Tinker\\TinkerServiceProvider',
      23 => 'App\\Providers\\AppServiceProvider',
      24 => 'App\\Providers\\AuthServiceProvider',
      25 => 'App\\Providers\\BroadcastServiceProvider',
      26 => 'App\\Providers\\EventServiceProvider',
      27 => 'App\\Providers\\RouteServiceProvider',
      28 => 'App\\Providers\\HtmlMacroServiceProvider',
      29 => 'Collective\\Html\\HtmlServiceProvider',
      30 => 'Barryvdh\\Cors\\ServiceProvider',
      31 => 'Tymon\\JWTAuth\\Providers\\JWTAuthServiceProvider',
      32 => 'Intervention\\Image\\ImageServiceProvider',
      33 => 'Propaganistas\\LaravelPhone\\LaravelPhoneServiceProvider',
      34 => 'Maatwebsite\\Excel\\ExcelServiceProvider',
      35 => 'ConsoleTVs\\Charts\\ChartsServiceProvider',
      36 => 'LaravelFCM\\FCMServiceProvider',
      37 => 'Cviebrock\\LaravelMangopay\\ServiceProvider',
      38 => 'Intervention\\Validation\\ValidationServiceProvider',
      39 => 'niklasravnsborg\\LaravelPdf\\PdfServiceProvider',
      40 => 'Anhskohbo\\NoCaptcha\\NoCaptchaServiceProvider',
    ),
    'aliases' => 
    array (
      'App' => 'Illuminate\\Support\\Facades\\App',
      'Artisan' => 'Illuminate\\Support\\Facades\\Artisan',
      'Auth' => 'Illuminate\\Support\\Facades\\Auth',
      'Blade' => 'Illuminate\\Support\\Facades\\Blade',
      'Broadcast' => 'Illuminate\\Support\\Facades\\Broadcast',
      'Bus' => 'Illuminate\\Support\\Facades\\Bus',
      'Cache' => 'Illuminate\\Support\\Facades\\Cache',
      'Config' => 'Illuminate\\Support\\Facades\\Config',
      'Cookie' => 'Illuminate\\Support\\Facades\\Cookie',
      'Crypt' => 'Illuminate\\Support\\Facades\\Crypt',
      'DB' => 'Illuminate\\Support\\Facades\\DB',
      'Eloquent' => 'Illuminate\\Database\\Eloquent\\Model',
      'Event' => 'Illuminate\\Support\\Facades\\Event',
      'File' => 'Illuminate\\Support\\Facades\\File',
      'Gate' => 'Illuminate\\Support\\Facades\\Gate',
      'Hash' => 'Illuminate\\Support\\Facades\\Hash',
      'Lang' => 'Illuminate\\Support\\Facades\\Lang',
      'Log' => 'Illuminate\\Support\\Facades\\Log',
      'Mail' => 'Illuminate\\Support\\Facades\\Mail',
      'Notification' => 'Illuminate\\Support\\Facades\\Notification',
      'Password' => 'Illuminate\\Support\\Facades\\Password',
      'Queue' => 'Illuminate\\Support\\Facades\\Queue',
      'Redirect' => 'Illuminate\\Support\\Facades\\Redirect',
      'Redis' => 'Illuminate\\Support\\Facades\\Redis',
      'Request' => 'Illuminate\\Support\\Facades\\Request',
      'Response' => 'Illuminate\\Support\\Facades\\Response',
      'Route' => 'Illuminate\\Support\\Facades\\Route',
      'Schema' => 'Illuminate\\Support\\Facades\\Schema',
      'Session' => 'Illuminate\\Support\\Facades\\Session',
      'Storage' => 'Illuminate\\Support\\Facades\\Storage',
      'URL' => 'Illuminate\\Support\\Facades\\URL',
      'Validator' => 'Illuminate\\Support\\Facades\\Validator',
      'View' => 'Illuminate\\Support\\Facades\\View',
      'Form' => 'Collective\\Html\\FormFacade',
      'Html' => 'Collective\\Html\\HtmlFacade',
      'JWTAuth' => 'Tymon\\JWTAuth\\Facades\\JWTAuth',
      'Image' => 'Intervention\\Image\\Facades\\Image',
      'Excel' => 'Maatwebsite\\Excel\\Facades\\Excel',
      'Charts' => 'ConsoleTVs\\Charts\\Facades\\Charts',
      'FCM' => 'LaravelFCM\\Facades\\FCM',
      'FCMGroup' => 'LaravelFCM\\Facades\\FCMGroup',
      'PDF' => 'niklasravnsborg\\LaravelPdf\\Facades\\Pdf',
      'NoCaptcha' => 'Anhskohbo\\NoCaptcha\\Facades\\NoCaptcha',
    ),
  ),
  'mail' => 
  array (
    'driver' => 'mail',
    'host' => 'localhost',
    'port' => '25',
    'from' => 
    array (
      'address' => 'no-reply@sipper.pro',
      'name' => 'Sipper',
    ),
    'encryption' => NULL,
    'username' => NULL,
    'password' => NULL,
    'sendmail' => '/usr/sbin/sendmail -bs',
    'markdown' => 
    array (
      'theme' => 'default',
      'paths' => 
      array (
        0 => '/var/www/resources/views/vendor/mail',
      ),
    ),
  ),
  'services' => 
  array (
    'mailgun' => 
    array (
      'domain' => 'mail.sipper.pro',
      'secret' => 'key-61b2556772f548fc636185a361704c12',
    ),
    'ses' => 
    array (
      'key' => NULL,
      'secret' => NULL,
      'region' => 'us-east-1',
    ),
    'sparkpost' => 
    array (
      'secret' => NULL,
    ),
    'stripe' => 
    array (
      'model' => 'App\\User',
      'key' => NULL,
      'secret' => NULL,
    ),
    'mangopay' => 
    array (
      'env' => 'sandbox',
      'key' => 'sippermangopay',
      'secret' => 'gE2et0gHq4uraPtHjVL9hZGzgwtShrY87TuqKnUKZDjBRsoNTE',
    ),
  ),
  'database' => 
  array (
    'default' => 'mysql',
    'connections' => 
    array (
      'sqlite' => 
      array (
        'driver' => 'sqlite',
        'database' => 'sipperbdd',
        'prefix' => '',
      ),
      'mysql' => 
      array (
        'driver' => 'mysql',
        'host' => '35.195.93.139',
        'port' => '3306',
        'database' => 'sipperbdd',
        'username' => 'sipper_app',
        'password' => '|F383xye20_yyFkGmT8=t2b!(|[3nwM^H;`Cx15l',
        'unix_socket' => '',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '',
        'strict' => true,
        'engine' => NULL,
      ),
      'pgsql' => 
      array (
        'driver' => 'pgsql',
        'host' => '35.195.93.139',
        'port' => '3306',
        'database' => 'sipperbdd',
        'username' => 'sipper_app',
        'password' => '|F383xye20_yyFkGmT8=t2b!(|[3nwM^H;`Cx15l',
        'charset' => 'utf8',
        'prefix' => '',
        'schema' => 'public',
        'sslmode' => 'prefer',
      ),
    ),
    'migrations' => 'migrations',
    'redis' => 
    array (
      'client' => 'predis',
      'default' => 
      array (
        'host' => '127.0.0.1',
        'password' => NULL,
        'port' => '6379',
        'database' => 0,
      ),
    ),
  ),
  'cache' => 
  array (
    'default' => 'file',
    'stores' => 
    array (
      'apc' => 
      array (
        'driver' => 'apc',
      ),
      'array' => 
      array (
        'driver' => 'array',
      ),
      'database' => 
      array (
        'driver' => 'database',
        'table' => 'cache',
        'connection' => NULL,
      ),
      'file' => 
      array (
        'driver' => 'file',
        'path' => '/var/www/storage/framework/cache/data',
      ),
      'memcached' => 
      array (
        'driver' => 'memcached',
        'persistent_id' => NULL,
        'sasl' => 
        array (
          0 => NULL,
          1 => NULL,
        ),
        'options' => 
        array (
        ),
        'servers' => 
        array (
          0 => 
          array (
            'host' => '127.0.0.1',
            'port' => 11211,
            'weight' => 100,
          ),
        ),
      ),
      'redis' => 
      array (
        'driver' => 'redis',
        'connection' => 'default',
      ),
    ),
    'prefix' => 'laravel',
  ),
  'session' => 
  array (
    'driver' => 'file',
    'lifetime' => 120,
    'expire_on_close' => false,
    'encrypt' => false,
    'files' => '/var/www/storage/framework/sessions',
    'connection' => NULL,
    'table' => 'sessions',
    'store' => NULL,
    'lottery' => 
    array (
      0 => 2,
      1 => 100,
    ),
    'cookie' => 'laravel_session',
    'path' => '/',
    'domain' => NULL,
    'secure' => false,
    'http_only' => true,
  ),
  'charts' => 
  array (
    'default' => 
    array (
      'type' => 'line',
      'library' => 'material',
      'element_label' => 'Element',
      'title' => 'My Cool Chart',
      'height' => 400,
      'width' => 0,
      'responsive' => false,
      'background_color' => 'inherit',
      'colors' => 
      array (
      ),
      'one_color' => false,
      'template' => 'material',
      'legend' => true,
      'x_axis_title' => false,
      'y_axis_title' => NULL,
      'loader' => 
      array (
        'active' => true,
        'duration' => 500,
        'color' => '#000000',
      ),
    ),
    'templates' => 
    array (
      'material' => 
      array (
        0 => '#2196F3',
        1 => '#F44336',
        2 => '#FFC107',
      ),
      'red-material' => 
      array (
        0 => '#B71C1C',
        1 => '#F44336',
        2 => '#E57373',
      ),
      'indigo-material' => 
      array (
        0 => '#1A237E',
        1 => '#3F51B5',
        2 => '#7986CB',
      ),
      'blue-material' => 
      array (
        0 => '#0D47A1',
        1 => '#2196F3',
        2 => '#64B5F6',
      ),
      'teal-material' => 
      array (
        0 => '#004D40',
        1 => '#009688',
        2 => '#4DB6AC',
      ),
      'green-material' => 
      array (
        0 => '#1B5E20',
        1 => '#4CAF50',
        2 => '#81C784',
      ),
      'yellow-material' => 
      array (
        0 => '#F57F17',
        1 => '#FFEB3B',
        2 => '#FFF176',
      ),
      'orange-material' => 
      array (
        0 => '#E65100',
        1 => '#FF9800',
        2 => '#FFB74D',
      ),
    ),
    'assets' => 
    array (
      'global' => 
      array (
        'scripts' => 
        array (
          0 => 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js',
        ),
      ),
      'canvas-gauges' => 
      array (
        'scripts' => 
        array (
          0 => 'https://cdn.rawgit.com/Mikhus/canvas-gauges/gh-pages/download/2.1.2/all/gauge.min.js',
        ),
      ),
      'chartist' => 
      array (
        'scripts' => 
        array (
          0 => 'https://cdnjs.cloudflare.com/ajax/libs/chartist/0.10.1/chartist.min.js',
        ),
        'styles' => 
        array (
          0 => 'https://cdnjs.cloudflare.com/ajax/libs/chartist/0.10.1/chartist.min.css',
        ),
      ),
      'chartjs' => 
      array (
        'scripts' => 
        array (
          0 => 'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js',
        ),
      ),
      'fusioncharts' => 
      array (
        'scripts' => 
        array (
          0 => 'https://static.fusioncharts.com/code/latest/fusioncharts.js',
          1 => 'https://static.fusioncharts.com/code/latest/themes/fusioncharts.theme.fint.js',
        ),
      ),
      'google' => 
      array (
        'scripts' => 
        array (
          0 => 'https://www.google.com/jsapi',
          1 => 'https://www.gstatic.com/charts/loader.js',
          2 => 'google.charts.load(\'current\', {\'packages\':[\'corechart\', \'gauge\', \'geochart\', \'bar\', \'line\']})',
        ),
      ),
      'highcharts' => 
      array (
        'styles' => 
        array (
        ),
        'scripts' => 
        array (
          0 => 'https://cdnjs.cloudflare.com/ajax/libs/highcharts/5.0.7/highcharts.js',
          1 => 'https://cdnjs.cloudflare.com/ajax/libs/highcharts/5.0.7/js/modules/offline-exporting.js',
          2 => 'https://cdnjs.cloudflare.com/ajax/libs/highmaps/5.0.7/js/modules/map.js',
          3 => 'https://cdnjs.cloudflare.com/ajax/libs/highmaps/5.0.7/js/modules/data.js',
          4 => 'https://code.highcharts.com/mapdata/custom/world.js',
        ),
      ),
      'justgage' => 
      array (
        'scripts' => 
        array (
          0 => 'https://cdnjs.cloudflare.com/ajax/libs/raphael/2.2.6/raphael.min.js',
          1 => 'https://cdnjs.cloudflare.com/ajax/libs/justgage/1.2.2/justgage.min.js',
        ),
      ),
      'morris' => 
      array (
        'styles' => 
        array (
          0 => 'https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css',
        ),
        'scripts' => 
        array (
          0 => 'https://cdnjs.cloudflare.com/ajax/libs/raphael/2.2.6/raphael.min.js',
          1 => 'https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js',
        ),
      ),
      'plottablejs' => 
      array (
        'scripts' => 
        array (
          0 => 'https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.5/d3.min.js',
          1 => 'https://cdnjs.cloudflare.com/ajax/libs/plottable.js/2.8.0/plottable.min.js',
        ),
        'styles' => 
        array (
          0 => 'https://cdnjs.cloudflare.com/ajax/libs/plottable.js/2.2.0/plottable.css',
        ),
      ),
      'progressbarjs' => 
      array (
        'scripts' => 
        array (
          0 => 'https://cdnjs.cloudflare.com/ajax/libs/progressbar.js/1.0.1/progressbar.min.js',
        ),
      ),
      'c3' => 
      array (
        'scripts' => 
        array (
          0 => 'https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.5/d3.min.js',
          1 => 'https://cdnjs.cloudflare.com/ajax/libs/c3/0.4.11/c3.min.js',
        ),
        'styles' => 
        array (
          0 => 'https://cdnjs.cloudflare.com/ajax/libs/c3/0.4.11/c3.min.css',
        ),
      ),
    ),
  ),
  'queue' => 
  array (
    'default' => 'database',
    'connections' => 
    array (
      'sync' => 
      array (
        'driver' => 'sync',
      ),
      'database' => 
      array (
        'driver' => 'database',
        'table' => 'jobs',
        'queue' => 'default',
        'retry_after' => 90,
      ),
      'beanstalkd' => 
      array (
        'driver' => 'beanstalkd',
        'host' => 'localhost',
        'queue' => 'default',
        'retry_after' => 90,
      ),
      'sqs' => 
      array (
        'driver' => 'sqs',
        'key' => 'your-public-key',
        'secret' => 'your-secret-key',
        'prefix' => 'https://sqs.us-east-1.amazonaws.com/your-account-id',
        'queue' => 'your-queue-name',
        'region' => 'us-east-1',
      ),
      'redis' => 
      array (
        'driver' => 'redis',
        'connection' => 'default',
        'queue' => 'default',
        'retry_after' => 90,
      ),
    ),
    'failed' => 
    array (
      'database' => 'mysql',
      'table' => 'failed_jobs',
    ),
  ),
  'constants' => 
  array (
    'debug' => false,
    'test' => true,
    'base_url' => 'https://vps.sipper.pro/',
    'base_url_application_user' => 'https://vps.sipper.pro/storage/uploads/application_users_img/',
    'base_url_partner' => 'https://vps.sipper.pro/storage/uploads/partners_img/',
    'base_url_invoice' => 'https://www.sipper.pro/public/uploads/invoices/',
    'base_url_invoice_partners' => 'https://www.sipper.pro/public/uploads/invoices/partners/',
    'base_url_invoice_applicationsUsers' => 'https://www.sipper.pro/public/uploads/invoices/application_users/',
    'general_id_application' => NULL,
    'general_wallet_application' => NULL,
    'mail_main' => 'contact@sipperapp.com',
    'mail_admin' => 'thomas@sipperapp.com',
    'company_name' => 'Sipper',
    'company_address' => '4 Villa Emile Bergerat <br /> 92200 <br /> Neuilly-sur-Seine',
    'company_website' => 'sipperapp.com',
    'company_siret' => '831 269 253 00017',
    'company_legal_form' => 'SAS au capital de 1000€',
    'company_tva_number' => 'FR 45 831269253',
  ),
  'excel' => 
  array (
    'cache' => 
    array (
      'enable' => true,
      'driver' => 'memory',
      'settings' => 
      array (
        'memoryCacheSize' => '32MB',
        'cacheTime' => 600,
      ),
      'memcache' => 
      array (
        'host' => 'localhost',
        'port' => 11211,
      ),
      'dir' => '/var/www/storage/cache',
    ),
    'properties' => 
    array (
      'creator' => 'Maatwebsite',
      'lastModifiedBy' => 'Maatwebsite',
      'title' => 'Spreadsheet',
      'description' => 'Default spreadsheet export',
      'subject' => 'Spreadsheet export',
      'keywords' => 'maatwebsite, excel, export',
      'category' => 'Excel',
      'manager' => 'Maatwebsite',
      'company' => 'Maatwebsite',
    ),
    'sheets' => 
    array (
      'pageSetup' => 
      array (
        'orientation' => 'portrait',
        'paperSize' => '9',
        'scale' => '100',
        'fitToPage' => false,
        'fitToHeight' => true,
        'fitToWidth' => true,
        'columnsToRepeatAtLeft' => 
        array (
          0 => '',
          1 => '',
        ),
        'rowsToRepeatAtTop' => 
        array (
          0 => 0,
          1 => 0,
        ),
        'horizontalCentered' => false,
        'verticalCentered' => false,
        'printArea' => NULL,
        'firstPageNumber' => NULL,
      ),
    ),
    'creator' => 'Maatwebsite',
    'csv' => 
    array (
      'delimiter' => ',',
      'enclosure' => '"',
      'line_ending' => '
',
      'use_bom' => false,
    ),
    'export' => 
    array (
      'autosize' => true,
      'autosize-method' => 'approx',
      'generate_heading_by_indices' => true,
      'merged_cell_alignment' => 'left',
      'calculate' => false,
      'includeCharts' => false,
      'sheets' => 
      array (
        'page_margin' => false,
        'nullValue' => NULL,
        'startCell' => 'A1',
        'strictNullComparison' => false,
      ),
      'store' => 
      array (
        'path' => '/var/www/storage/exports',
        'returnInfo' => false,
      ),
      'pdf' => 
      array (
        'driver' => 'DomPDF',
        'drivers' => 
        array (
          'DomPDF' => 
          array (
            'path' => '/var/www/vendor/dompdf/dompdf/',
          ),
          'tcPDF' => 
          array (
            'path' => '/var/www/vendor/tecnick.com/tcpdf/',
          ),
          'mPDF' => 
          array (
            'path' => '/var/www/vendor/mpdf/mpdf/',
          ),
        ),
      ),
    ),
    'filters' => 
    array (
      'registered' => 
      array (
        'chunk' => 'Maatwebsite\\Excel\\Filters\\ChunkReadFilter',
      ),
      'enabled' => 
      array (
      ),
    ),
    'import' => 
    array (
      'heading' => 'slugged',
      'startRow' => 1,
      'separator' => '_',
      'slug_whitelist' => '._',
      'includeCharts' => false,
      'to_ascii' => true,
      'encoding' => 
      array (
        'input' => 'UTF-8',
        'output' => 'UTF-8',
      ),
      'calculate' => true,
      'ignoreEmpty' => false,
      'force_sheets_collection' => false,
      'dates' => 
      array (
        'enabled' => true,
        'format' => false,
        'columns' => 
        array (
        ),
      ),
      'sheets' => 
      array (
        'test' => 
        array (
          'firstname' => 'A2',
        ),
      ),
    ),
    'views' => 
    array (
      'styles' => 
      array (
        'th' => 
        array (
          'font' => 
          array (
            'bold' => true,
            'size' => 12,
          ),
        ),
        'strong' => 
        array (
          'font' => 
          array (
            'bold' => true,
            'size' => 12,
          ),
        ),
        'b' => 
        array (
          'font' => 
          array (
            'bold' => true,
            'size' => 12,
          ),
        ),
        'i' => 
        array (
          'font' => 
          array (
            'italic' => true,
            'size' => 12,
          ),
        ),
        'h1' => 
        array (
          'font' => 
          array (
            'bold' => true,
            'size' => 24,
          ),
        ),
        'h2' => 
        array (
          'font' => 
          array (
            'bold' => true,
            'size' => 18,
          ),
        ),
        'h3' => 
        array (
          'font' => 
          array (
            'bold' => true,
            'size' => 13.5,
          ),
        ),
        'h4' => 
        array (
          'font' => 
          array (
            'bold' => true,
            'size' => 12,
          ),
        ),
        'h5' => 
        array (
          'font' => 
          array (
            'bold' => true,
            'size' => 10,
          ),
        ),
        'h6' => 
        array (
          'font' => 
          array (
            'bold' => true,
            'size' => 7.5,
          ),
        ),
        'a' => 
        array (
          'font' => 
          array (
            'underline' => true,
            'color' => 
            array (
              'argb' => 'FF0000FF',
            ),
          ),
        ),
        'hr' => 
        array (
          'borders' => 
          array (
            'bottom' => 
            array (
              'style' => 'thin',
              'color' => 
              array (
                0 => 'FF000000',
              ),
            ),
          ),
        ),
      ),
    ),
  ),
  'broadcasting' => 
  array (
    'default' => 'redis',
    'connections' => 
    array (
      'pusher' => 
      array (
        'driver' => 'pusher',
        'key' => NULL,
        'secret' => NULL,
        'app_id' => NULL,
        'options' => 
        array (
        ),
      ),
      'redis' => 
      array (
        'driver' => 'redis',
        'connection' => 'default',
      ),
      'log' => 
      array (
        'driver' => 'log',
      ),
      'null' => 
      array (
        'driver' => 'null',
      ),
    ),
  ),
  'debugbar' => 
  array (
    'enabled' => false,
    'storage' => 
    array (
      'enabled' => true,
      'driver' => 'file',
      'path' => '/var/www/storage/debugbar',
      'connection' => NULL,
      'provider' => '',
    ),
    'include_vendors' => true,
    'capture_ajax' => true,
    'clockwork' => false,
    'collectors' => 
    array (
      'phpinfo' => true,
      'messages' => true,
      'time' => true,
      'memory' => true,
      'exceptions' => true,
      'log' => true,
      'db' => true,
      'views' => true,
      'route' => true,
      'laravel' => false,
      'events' => false,
      'default_request' => false,
      'symfony_request' => true,
      'mail' => true,
      'logs' => false,
      'files' => false,
      'config' => false,
      'auth' => false,
      'gate' => false,
      'session' => true,
    ),
    'options' => 
    array (
      'auth' => 
      array (
        'show_name' => false,
      ),
      'db' => 
      array (
        'with_params' => true,
        'timeline' => false,
        'backtrace' => false,
        'explain' => 
        array (
          'enabled' => false,
          'types' => 
          array (
            0 => 'SELECT',
          ),
        ),
        'hints' => true,
      ),
      'mail' => 
      array (
        'full_log' => false,
      ),
      'views' => 
      array (
        'data' => false,
      ),
      'route' => 
      array (
        'label' => true,
      ),
      'logs' => 
      array (
        'file' => NULL,
      ),
    ),
    'inject' => true,
    'route_prefix' => '_debugbar',
  ),
  'view' => 
  array (
    'paths' => 
    array (
      0 => '/var/www/resources/views',
    ),
    'compiled' => '/var/www/storage/framework/views',
  ),
  'scout' => 
  array (
    'driver' => 'algolia',
    'prefix' => '',
    'queue' => true,
    'algolia' => 
    array (
      'id' => '',
      'secret' => '',
    ),
  ),
  'cors' => 
  array (
    'supportsCredentials' => false,
    'allowedOrigins' => 
    array (
      0 => '*',
      1 => 'https::/vps.partenaire.sipper.pro',
      2 => 'https::/partenaire.sipper.pro',
    ),
    'allowedHeaders' => 
    array (
      0 => '*',
    ),
    'allowedMethods' => 
    array (
      0 => '*',
    ),
    'exposedHeaders' => 
    array (
    ),
    'maxAge' => 0,
  ),
  'push-notification' => 
  array (
    'appNameIOS' => 
    array (
      'environment' => 'development',
      'certificate' => '/path/to/certificate.pem',
      'passPhrase' => 'password',
      'service' => 'apns',
    ),
    'appNameAndroid' => 
    array (
      'environment' => 'production',
      'apiKey' => 'yourAPIKey',
      'service' => 'gcm',
    ),
  ),
  'image' => 
  array (
    'driver' => 'gd',
  ),
  'jwt' => 
  array (
    'secret' => 'x1SFW27AAVuo2QAO7x320Cm5ucM6fPuO',
    'ttl' => 20160,
    'refresh_ttl' => 20160,
    'algo' => 'HS256',
    'user' => 'App\\User',
    'identifier' => 'id',
    'required_claims' => 
    array (
      0 => 'iss',
      1 => 'iat',
      2 => 'exp',
      3 => 'nbf',
      4 => 'sub',
      5 => 'jti',
    ),
    'blacklist_enabled' => true,
    'providers' => 
    array (
      'user' => 'Tymon\\JWTAuth\\Providers\\User\\EloquentUserAdapter',
      'jwt' => 'Tymon\\JWTAuth\\Providers\\JWT\\NamshiAdapter',
      'auth' => 'Tymon\\JWTAuth\\Providers\\Auth\\IlluminateAuthAdapter',
      'storage' => 'Tymon\\JWTAuth\\Providers\\Storage\\IlluminateCacheAdapter',
    ),
  ),
  'filesystems' => 
  array (
    'default' => 'local',
    'cloud' => 's3',
    'disks' => 
    array (
      'local' => 
      array (
        'driver' => 'local',
        'root' => '/var/www/storage/app',
      ),
      'public' => 
      array (
        'driver' => 'local',
        'root' => '/var/www/storage/app/public',
        'url' => '/storage',
        'visibility' => 'public',
      ),
      's3' => 
      array (
        'driver' => 's3',
        'key' => NULL,
        'secret' => NULL,
        'region' => NULL,
        'bucket' => NULL,
      ),
    ),
  ),
  'pdf' => 
  array (
    'mode' => 'utf-8',
    'format' => 'A4',
    'author' => '',
    'subject' => '',
    'keywords' => '',
    'creator' => 'Laravel Pdf',
    'display_mode' => 'fullpage',
    'tempDir' => '/var/www/vendor/niklasravnsborg/laravel-pdf/src/config../temp/',
  ),
  'captcha' => 
  array (
    'secret' => '6Lc7CkUUAAAAAJ1dqcZAVJzK3SkzwColIcW85HSz',
    'sitekey' => '6Lc7CkUUAAAAAPHXR5HQXowMjU5O_7vwx-k48uAo',
    'options' => 
    array (
      'timeout' => 2.0,
    ),
  ),
  'fcm' => 
  array (
    'driver' => 'http',
    'log_enabled' => false,
    'http' => 
    array (
      'server_key' => 'AAAAxgzuH4Y:APA91bEoeaegLlzzCnYjgFH93fpBUi36jaDJgUUjGakIEhbnFoatZTu7D1_UY6z21ZXlBPOpkRI55F8udvM3NnNdFR-qfGtejCizBN17bup7ynuheo7wnHUA5-YbZxhOBwAf5BNd_zK4',
      'sender_id' => '850620456838',
      'server_send_url' => 'https://fcm.googleapis.com/fcm/send',
      'server_group_url' => 'https://android.googleapis.com/gcm/notification',
      'timeout' => 30.0,
    ),
  ),
);
