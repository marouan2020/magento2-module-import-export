<?php
/**
 * MageTunisia Software.
 *
 * @category  MageTunisia
 * @package   MageTunisia_ImportExport
 * @author    Ben Mansour Marouan marouan.ben.mansour@gmail.com
 * @copyright Copyright (c) MageTunisia Software Private Limited (http://marouan-ben-mansour.com)
 * @license   http://marouan-ben-mansour.com/license.html
 */

declare(strict_types=1);

namespace MageTunisia\ImportExport\Console\Command;

use Symfony\Component\Console\Command\Command,
    MageTunisia\ImportExport\Console\Command\AbstractImport,
    Symfony\Component\Console\Input\InputArgument,
    Symfony\Component\Console\Input\InputOption,
    Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Output\OutputInterface,
    Magento\Framework\App\ObjectManager;


/**
 * class ImportProduct
 * @package MageTunisia\ImportExport\Console\Command
 */
class Import extends AbstractImport
{
    const NAME_ARGUMENT = "name";
    const NAME_OPTION = "option";

    private $listAllowedArguments = ["products", "categories"];

    /**
     * {@inheritdoc}
     */
    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ) {
        $argumentName = $input->getArgument(self::NAME_ARGUMENT);
        $option = $input->getOption(self::NAME_OPTION);
        if (!in_array($argumentName, $this->listAllowedArguments)) {
            $output->writeln("<error>Provided name {$argumentName} is not allowed!</error>");
            exit;
        }
        $state = ObjectManager::getInstance()->get('\Magento\Framework\App\State');
        if (!$state->isAreaCodeEmulated() || empty($state->getAreaCode())) {
            $state->setAreaCode(\Magento\Framework\App\Area::AREA_ADMINHTML);
        }
        $output->writeln("<info>Start process...</info>");
        $output->writeln("<info>Convert csv to array data....</info>");
        $data = $this->getDataFromCsv();
        if (empty($data)) {
            $output->writeln("<error>No data found in your file!</error>");
            exit;
        }
        $output->writeln("<info>the data is validated and it is ready to import</info>");
        $output->writeln("<info>Start import...</info>");
        unset($data[0]);
        foreach ($data as $line) {
            $status = $this->import($line, $argumentName);
            $output->writeln($status);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName("magetunisia_importexport:import");
        $this->setDescription("Import data from csv files to Magento Database, The allowed arguments: products, categories.");
        $this->setDefinition([
            new InputArgument(self::NAME_ARGUMENT, InputArgument::OPTIONAL, "Name"),
            new InputOption(self::NAME_OPTION, "-a", InputOption::VALUE_NONE, "Option functionality")
        ]);
        parent::configure();
    }
}