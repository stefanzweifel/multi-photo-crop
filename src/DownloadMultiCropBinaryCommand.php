<?php

namespace Wnx\PhotoCrop;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Wnx\PhotoCrop\Managers\DownloadManager;

class DownloadMultiCropBinaryCommand extends Command
{
    /**
     * Configue the Command
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('download:binary')
            ->setDescription('Download MultiCrop binary from authors website')
            ;
    }

    /**
     * Command Logic
     * @param  InputInterface  $input
     * @param  OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $downloadManager = new DownloadManager();

        if (!$downloadManager->doesBinaryExist()) {
            $helper = $this->getHelper('question');
            $question = new ConfirmationQuestion("❓  The `multicrop` binary file is missing. Should it be downloaded? [y/n]", false);

            if ($helper->ask($input, $output, $question)) {
                $output->writeln('<info>🕥  Download `multicrop` binary ...</info>');
                $downloadManager->download();
                $output->writeln('<info>✅  Download complete.</info>');
            } else {
                $output->writeln('<comment>❌  Abort download and further execution.</comment>');
                return;
            }
        } else {
            $output->writeln('<info>❕  The Binary already exists. Nothing happend.</info>');
        }

        return 0;
    }
}
