<?php

namespace OrchidAddon\Screens\Log;

use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use OrchidAddon\LogViewer;

class LogPreviewScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(string $file_name): iterable
    {
        LogViewer::setFile($file_name);
        $logs = LogViewer::all();

        if (count($logs) <= 0) {
            abort(404, "log file doesn't exist");
        }

        return [
            'logs' => $logs,
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'LogPreviewScreen';
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::view('orchid_addon::log_item')
        ];
    }
}
