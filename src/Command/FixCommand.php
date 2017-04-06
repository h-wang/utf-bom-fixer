<?php

namespace Hongliang\UtfBomFixer\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Hongliang\UtfBomFixer\UtfBomFixer;

class FixCommand extends Command
{
    protected function configure()
    {
        $this
        ->setName('fix')
        ->setDescription('Fix The UTF Bom')
        ->addArgument(
            'dir',
            InputArgument::REQUIRED,
            'The directory to scan files or the path of a file'
        )
        ->addOption(
            'extension',
            null,
            InputOption::VALUE_REQUIRED,
            'file extension'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $fileExtension = $input->getOption('extension');
        $dir = $input->getArgument('dir');
        $fixed = 0;

        $o = new SymfonyStyle($input, $output);
        $o->title('UTF BOM Fixer: Start to fix ...');
        $o->section("\xF0\x9F\x93\x81 : [<info>".$dir."</>]");
        if (is_dir($dir) || is_file($dir)) {
            $fixer = new UtfBomFixer($dir, $fileExtension);
            $files = $fixer->scanFiles();
            foreach ($files as $file) {
                $res = $fixer->fixFile($file);
                switch ($res['success']) {
                    case 1:
                        $o->text('<comment>'.$res['message'].'</>');
                        ++$fixed;
                        break;
                    case -1:
                        $o->text('<error>'.$res['message'].'</>');
                        break;
                    case 0:
                        $o->text('<info>'.$res['message'].'</>');
                        break;
                }
            }
            $o->success("\xF0\x9F\x93\xA3  ".count($files)." files scanned, ".$fixed." fixed. \xF0\x9F\x8E\x89");
        } else {
            $o->error("\xE2\x9D\x8C  ".$dir." is not a valid directory path or file path. \xF0\x9F\x98\x85");
        }
    }
}
