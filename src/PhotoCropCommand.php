<?php

namespace Wnx\PhotoCrop;

use Carbon\Carbon;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Wnx\PhotoCrop\Binaries\MultiCrop;

class PhotoCropCommand extends Command
{
    /**
     * Configue the Command
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('run')
            ->setDescription('Process images')
            ->setHelp('Fetch images from disk, process them and store split images in a destination folder')
            ->addOption('images', 'i', InputOption::VALUE_REQUIRED, 'Path to images which should be processed', null)
            ->addOption('output', 'o', InputOption::VALUE_REQUIRED, 'Path where output images should be stored', null)
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
        $files = glob( $input->getOption('images'));

        if (count($files) <= 0) {
            $output->writeln('<error>No Files found.</error>');
            return;
        }

        $helper = $this->getHelper('question');
        $question = new ConfirmationQuestion("Found " . count($files) . " images. Start processing them? ", false);

        if (!$helper->ask($input, $output, $question)) {
            $output->writeln('<comment>Abort processing.</comment>');
            return;
        }

        $now = Carbon::now();
        $output->writeln("<info>Start at {$now->format('Y-m-d H:i:s')} </info>");
        $progress = new ProgressBar($output, count($files));
        $progress->start();

        foreach($files as $file) {

            $command = new MultiCrop(realpath($file), $input->getOption('output'));
            $command->fire();
            $progress->advance();

        }

        $progress->finish();
        $end = Carbon::now();

        $output->writeln(' ');
        $output->writeln("<info>Finished at {$end->format('Y-m-d H:i:s')}</info>");
        $output->writeln("<info>It took {$now->diffForHumans($now, true)}</info>");
        $output->writeln('<info>✅  Processing completed.</info>');
    }
}