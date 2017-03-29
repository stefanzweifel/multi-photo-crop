<?php

namespace Wnx\PhotoCrop\Binaries;

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
    protected $output;

    public function __construct($inputFile, $outputDestination)
    {
        $this->inputFile = $inputFile;
        $this->outputDestination = $outputDestination;
    }

    public function fire()
    {
        $fuzzines = 20;
        $prune = 2;

        $output = shell_exec(__DIR__ . "/../../bin/multicrop -c 10,10 -d 25 -f $fuzzines -p $prune \"$this->inputFile\" \"{$this->getOutputPath()}\" >> /dev/null 2>&1");
        // die(var_dump($this->getOutputPath()));
    }

    protected function getOutputFilename()
    {
        $suffix    = "__processed__" . time();
        $filename  = basename($this->inputFile);
        $extension = pathinfo($this->inputFile)['extension'];

        return str_replace(".$extension", "{$suffix}.$extension", $filename);
    }

    protected function getOutputPath()
    {
        return realpath($this->outputDestination) . '/' . $this->getOutputFilename();
    }
}