<?php

namespace Wnx\PhotoCrop;

use Carbon\Carbon;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Wnx\PhotoCrop\Binaries\MultiCrop;
use Wnx\PhotoCrop\Managers\DownloadManager;

class PhotoCropCommand extends Command
{
    const PATH_TO_BINARY = __DIR__ . "/../bin/multicrop";

    /**
     * Configue the Command
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('run')
            ->setDescription('Process images with ImageMagick')
            ->setHelp('Fetch images from disk, process them and store split images in a destination folder')
            ->addArgument('images', InputArgument::REQUIRED, 'Path to images which should be processed, supports globs (/path/*.png)')
            ->addArgument('output', InputArgument::REQUIRED, 'Path where output images should be stored')
            ;
    }

    /**
     * Command Logic
     * @param  InputInterface  $input
     * @param  OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->downloadBinaryIfItDoesntExist($input, $output);

        $files = glob($input->getArgument('images'));

        if (count($files) <= 0) {
            $output->writeln('<error>No Files found.</error>');
            return;
        }

        $helper = $this->getHelper('question');
        $question = new ConfirmationQuestion("❓  Found " . count($files) . " images. Start processing them? ", false);

        if (!$helper->ask($input, $output, $question)) {
            $output->writeln('<comment>❌  Abort processing.</comment>');
            return;
        }

        $now = Carbon::now();
        $output->writeln("<info>🕥  Start at {$now->format('Y-m-d H:i:s')} </info>");
        $progress = new ProgressBar($output, count($files));
        $progress->start();

        foreach ($files as $file) {
            $command = new MultiCrop(realpath($file), $input->getArgument('output'));
            $command->fire();
            $progress->advance();
        }

        $progress->finish();
        $end = Carbon::now();

        $output->writeln(' ');
        $output->writeln("<info>🕥  Finished at {$end->format('Y-m-d H:i:s')}</info>");
        $output->writeln("<info>🕥  It took {$now->diffForHumans($end, true)}</info>");
        $output->writeln('<info>✅  Processing completed.</info>');
    }


    protected function downloadBinaryIfItDoesntExist($input, $output)
    {
        $downloadManager = new DownloadManager();

        if (!$downloadManager->doesBinaryExist()) {

            $command = $this->getApplication()->find('download:binary');

            $downloadInput = new ArrayInput([]);
            $command->run($downloadInput, $output);
        }
    }
}
