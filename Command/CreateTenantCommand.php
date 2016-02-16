<?php

namespace Cvele\MultiTenantBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * @author Vladimir Cvetić <vladimir@ferdinand.rs>
 */
class CreateTenantCommand extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setName('cvele:tenant:create')
            ->setDescription('Create tenant')
            ->setDefinition(array(
                new InputArgument('name', InputArgument::REQUIRED, 'Name of new tenant'),
            ))
            ->setHelp(<<<EOT
The <info>cvele:tenant:create</info> command creates new tenant:

  <info>php app/console cvele:tenant:create tenant_name</info>
EOT
            );
    }

    /**
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $tenantName = $input->getArgument('name');

        $manager = $this->getContainer()->get('multi_tenant.tenant_manager');
        $tenant = $manager->createTenant();
        $tenant->setName($tenantName);
        $manager->updateTenant($tenant);

        $output->writeln(sprintf('Tenant "%s" has been created.', $tenantName));
    }

    /**
     * @see Command
     */
    protected function interact(InputInterface $input, OutputInterface $output)
    {
        if (!$input->getArgument('name')) {
            $question = new Question('Please choose a tenant name:');
            $question->setValidator(function($name) {
                if (empty($name)) {
                    throw new \Exception('Name can not be empty');
                }

                return $name;
            });
            $answer = $this->getHelper('question')->ask($input, $output, $question);

            $input->setArgument('name', $answer);
        }
    }
}
