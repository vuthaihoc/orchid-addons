<?php

namespace OrchidAddon\Screens;

use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class PhpinfoScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'PhpVersionScreen';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::blank([
                Layout::view('orchid_addon::phpinfo', ['phpinfo' => $this->embedded_phpinfo()]),
            ]),
        ];
    }

    function embedded_phpinfo()
    {
        ob_start();
        phpinfo();
        $phpinfo = ob_get_contents();
        ob_end_clean();
        return preg_replace('%^.*<body>(.*)</body>.*$%ms', '$1', $phpinfo);
    }
}
