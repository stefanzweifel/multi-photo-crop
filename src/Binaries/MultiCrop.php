<?php

namespace Wnx\PhotoCrop\Binaries;

use Exception;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class MultiCrop
{
    /**
     * Absolute Path to Image
     * @var string
     */
    protected $inputFile;

    /**
     * Path to where processed image should be stored
     * @var string
     */
    protected $outputDestination;

    public function __construct(string $inputFile, string $outputDestination)
    {
        $this->inputFile = $inputFile;
        $this->outputDestination = $outputDestination;
    }

    public function fire()
    {
        $process = $this->getProcess();
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }
    }

    /**
     * Return Multicrop Process Instance
     * @return Symfony\Component\Process\Process
     */
    protected function getProcess() :Process
    {
        $fuzzines = 20;
        $prune = 2;

        return new Process(__DIR__ . "/../../bin/multicrop -c 10,10 -d 25 -f $fuzzines -p $prune \"$this->inputFile\" \"{$this->getOutputPath()}\" >> /dev/null 2>&1");
    }

    /**
     * Generate Filename for processed image
     * @return string
     */
    protected function getOutputFilename() :string
    {
        $suffix    = "__processed__" . time();
        $filename  = basename($this->inputFile);
        $extension = pathinfo($this->inputFile)['extension'];

        return str_replace(".$extension", "{$suffix}.$extension", $filename);
    }

    /**
     * Return Output Storage Path
     * @return string
     */
    protected function getOutputPath() :string
    {
        $folder = realpath($this->outputDestination);

        if ($folder === false) {
            throw new Exception('Output Folder not found');
        }

        return $folder . '/' . $this->getOutputFilename();
    }
}