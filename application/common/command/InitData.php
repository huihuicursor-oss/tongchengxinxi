<?php

namespace app\common\command;

use app\common\service\InstallerService;
use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\console\input\Option;

class InitData extends Command
{
    protected function configure()
    {
        $this->setName('app:init')
            ->setDescription('Initialize elderly care demo data')
            ->addOption('refresh', null, Option::VALUE_NONE, 'Rebuild sqlite database file');
    }

    protected function execute(Input $input, Output $output)
    {
        InstallerService::bootstrap((bool) $input->getOption('refresh'));
        $output->writeln('<info>SQLite 数据库已初始化：' . InstallerService::dbFile() . '</info>');
    }
}
