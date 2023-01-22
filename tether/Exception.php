<?php

namespace Tether;

use JetBrains\PhpStorm\NoReturn;

class Exception
{
    public function __construct(
        protected View $view,
        protected Config $config
    ) {}
    
    
    #[NoReturn] public function handle(\Exception|\ParseError|\Error $exception)
    {
        $trace = $exception->getTrace();
        
        $this->addInitialTraceToTraceList($trace, $exception);
        
        $trace = $this->appendFileContentsToTraces($trace);
        
        if (! $this->config->getByDotNotation('environment.debug')) {
            echo $this->view->make('errors.500');
        } else {
            echo $this->view->make('errors.exception', [
                'exception' => $exception,
                'trace' => $trace
            ]);
        }
        
        die();
    }
    
    public function addInitialTraceToTraceList(&$traces, $exception): void
    {
        array_unshift($traces, ['file' => $exception->getFile(), 'line' => $exception->getLine()]);
    }
    
    public function appendFileContentsToTraces($traces): array
    {
        return array_map(function ($trace) {
            if(! array_key_exists('file', $trace)) return $trace;

            $lines = file($trace['file']);

            $trace['lines'] = array_map(
                fn($line) => $this->padFileContentLine($line), 
                $this->limitFileContents($lines, $trace)
            );
            
            $trace['short_file'] = str_replace(basedir(), '', $trace['file']);

            return $trace;
        }, $traces);
    }
    
    public function padFileContentLine($line): string
    {
        if (preg_match('/<\?php/', $line)) {
            return '  ';
        }

        if (! str_starts_with('  ', $line)) {
            return '  ' . $line;
        }

        return $line;
    }
    
    public function limitFileContents($lines, $trace): array
    {
        return array_splice($lines, count($lines) > 11 ? (max($trace['line'] - 10, 0)) : 0, 35);
    }
}