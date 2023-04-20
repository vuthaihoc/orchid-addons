# Orchid Addons

## Install

    composer require hocvt/orchid-addons
  
  
Add menu

```php
<?php

// PlatformProvider

            Menu::make(__('Logs'))
                ->icon('bug')
                ->route('platform.logs', ['sort' => '-last_modified'])->title(__('SYSTEM')),

            Menu::make(__('Php Info'))
                ->icon('bug')
                ->route('platform.phpinfo', ['sort' => '-last_modified']),

```


## Screenshots

- Show php info `admin/phpinfo`

![Php info](phpinfo.png)

- Log Viewer `logs`

![Log Viewer](logviewer.png)

