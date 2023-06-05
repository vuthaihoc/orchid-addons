<?php

namespace OrchidAddon\Screens\Log;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;
use OrchidAddon\Models\Log;

class LogListScreen extends Screen
{
    /**
     * Query data.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'logs' => Log::filters()->paginate(),
        ];
    }

    /**
     * Display header name.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Logs';
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
            Layout::table('logs', [
                TD::make('file_name', 'File Name')
                    ->sort(),
                TD::make('last_modified', 'Last Modified')
                    ->render(fn(Log $log) => \Carbon\Carbon::createFromTimeStamp($log->last_modified))
                    ->sort(),
                TD::make('file_size', 'File Size')
                    ->render(fn(Log $log) => round((int)$log->file_size/1048576, 2).' MB')
                    ->sort(),
                TD::make(__('Actions'))
                    ->align(TD::ALIGN_CENTER)
                    ->width('100px')
                    ->render(function (Log $log) {
                        return DropDown::make()
                            ->icon('three-dots-vertical')
                            ->list([
                                Link::make(__('Preview'))
                                    ->route('platform.logs.preview', $log->file_name)
                                    ->icon('eye'),

                                Button::make(__('Delete'))
                                    ->icon('trash')
                                    ->confirm("Do you want to delete ?")
                                    ->method('remove', [
                                        'file_name' => $log->file_name,
                                    ]),
                                Link::make(__('Download'))
                                    ->icon('cloud-download')
                                    ->route('platform.logs.download', $log->file_name)
                            ]);
                    }),
            ])
        ];
    }

    public function remove(Request $request)
    {
        $file_name = $request->get('file_name');
        $log = Log::where('file_name', $file_name)->first();
        $log->deleteLogFile($file_name);
        Toast::info(__('Log was removed'));
    }
}
