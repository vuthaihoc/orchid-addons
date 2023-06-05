<?php


namespace OrchidAddon\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Client\Request;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;
use OrchidAddon\LogViewer;
use Sushi\Sushi;

class Log extends Model
{
    use Sushi;
    use AsSource, Filterable;

    protected array $allowedSorts = [
        'file_name',
        'last_modified',
        'file_size',
    ];

    public function getRows()
    {
        return LogViewer::getFiles(true);
    }

    protected function sushiShouldCache()
    {
        return false;
    }

    public function deleteLogFile($file_name){
        LogViewer::deleteFile($file_name);
    }
}
