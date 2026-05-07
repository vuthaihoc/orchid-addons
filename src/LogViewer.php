<?php

namespace OrchidAddon;

use Illuminate\Support\Facades\File;

class LogViewer
{
    private static string $file;

    /**
     * Map debug levels to Bootstrap classes.
     *
     * @var array
     */
    private static array $levels_classes = [
        'debug'     => 'info',
        'info'      => 'info',
        'notice'    => 'info',
        'warning'   => 'warning',
        'error'     => 'danger',
        'critical'  => 'danger',
        'alert'     => 'danger',
        'emergency' => 'danger',
        'processed' => 'info',
    ];

    /**
     * Map debug levels to icon classes.
     *
     * @var array
     */
    private static array $levels_imgs = [
        'debug'     => 'info',
        'info'      => 'info',
        'notice'    => 'info',
        'warning'   => 'warning',
        'error'     => 'warning',
        'critical'  => 'warning',
        'alert'     => 'warning',
        'emergency' => 'warning',
        'processed' => 'info',
    ];

    /**
     * Log levels that are used.
     *
     * @var array
     */
    private static array $log_levels = [
        'emergency',
        'alert',
        'critical',
        'error',
        'warning',
        'notice',
        'info',
        'debug',
        'processed',
    ];

    /**
     * Arbitrary max file size.
     */
    const MAX_FILE_SIZE = 2097152;
    const MAX_FILE_SIZE_TO_READ = 2097152;


    /**
     * @param string $file
     *
     * @throws \Exception
     */
    public static function setFile(string $file)
    {
        $file = static::pathToLogFile($file);

        if (app('files')->exists($file)) {
            static::$file = $file;
        }
    }

    /**
     * @param string $file
     * @return string
     * @throws \Exception
     */
    public static function pathToLogFile(string $file)
    {
        $logsPath = storage_path('logs');

        if (app('files')->exists($file)) {
            return $file;
        }
    
        $file = $logsPath . '/' . $file;
    
        $realLogsPath = realpath($logsPath);
        $realFilePath = realpath($file);
    
        // Ensure the requested file is inside the logs directory
        if (!$realFilePath || !str_starts_with($realFilePath, $realLogsPath)) {
            throw new \Exception('No such log file');
        }

        return $file;
    }

    /**
     * @return string
     */
    public static function getFileName()
    {
        return basename(static::$file);
    }

    /**
     *
     * @return array
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public static function all()
    {
        $log = [];

        if (!static::$file) {
            $log_file = static::getFiles();
            if (!count($log_file)) {
                return [];
            }
            static::$file = $log_file[0];
        }

        if (app('files')->size(static::$file) > static::MAX_FILE_SIZE) {
            $file = file_get_contents(static::$file, false,null, -self::MAX_FILE_SIZE_TO_READ);
        } else {
            $file = app('files')->get(static::$file);
        }

        $pattern = '/\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\].*/';

        preg_match_all($pattern, $file, $headings);

        if (!is_array($headings)) {
            return $log;
        }

        $stack_trace = preg_split($pattern, $file);

        if ($stack_trace[0] < 1) {
            array_shift($stack_trace);
        }

        foreach ($headings as $h) {
            for ($i = 0, $j = count($h); $i < $j; $i++) {
                foreach (static::$log_levels as $level) {
                    if (strpos(strtolower($h[$i]), '.'.$level) || strpos(strtolower($h[$i]), $level.':')) {
                        $pattern = '/^\[(?P<date>(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}))\](?:.*?(?P<context>(\w+))\.|.*?)'.$level.': (?P<text>.*?)(?P<in_file> in .*?:[0-9]+)?$/i';
                        preg_match($pattern, $h[$i], $current);
                        if (!isset($current['text'])) {
                            continue;
                        }

                        $log[] = [
                            'context'     => $current['context'],
                            'level'       => $level,
                            'level_class' => static::$levels_classes[$level],
                            'level_img'   => static::$levels_imgs[$level],
                            'date'        => $current['date'],
                            'text'        => $current['text'],
                            'in_file'     => $current['in_file'] ?? null,
                            'stack'       => preg_replace("/^\n*/", '', $stack_trace[$i]),
                        ];
                    }
                }
            }
        }

        return array_reverse($log);
    }


    /**
     * @param bool $basename
     * @param string $file_name
     * @return array|false
     */
    public static function getFiles(bool $basename = false, string $file_name = '')
    {
        $log_path = config('logging.viewer.path', storage_path('logs'));
        $files = [];
        $rii = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($log_path)
        );

        foreach ($rii as $file) {
    
            if ($file->isDir()) {
                continue;
            }

            if ($file->getExtension() !== 'log') {
                continue;
            }

            if ($file_name && !str_contains($file->getFilename(), $file_name)) {
                continue;
            }

            $files[] = $file->getPathname();
        }

        rsort($files);

        if ($basename) {
            foreach ($files as $k => $file) {
                $files[$k] = [
                    'id'            => $k,
                    'file_name'     => str_replace($log_path . '/', '', $file),
                    'file_size'     => filesize($file),
                    'last_modified' => filemtime($file),
                ];
            }
        }
    
        return array_values($files);
    }


    /**
     * @param string $file_name
     */
    public static function deleteFile($file_name){
        $fileLogPath = self::pathToLogFile($file_name);
        File::delete($fileLogPath);
    }
}
