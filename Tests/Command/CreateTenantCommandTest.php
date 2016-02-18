<?php

namespace Cvele\MultiTenantBundle\Tests\Command;

use Cvele\MultiTenantBundle\Command\CreateTenantCommand;
use Cvele\MultiTenantBundle\Model\Tenant;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ActivateUserCommandTest extends \PHPUnit_Framework_TestCase
{
    public function testExecute()
    {
        $commandTester = $this->createCommandTester($this->getContainer('tenant'));
        $exitCode = $commandTester->execute(array(
            'command' => 'cvele:tenant:create', // BC for SF <2.4 see https://github.com/symfony/symfony/pull/8626
            'name' => 'tenant',
        ), array(
            'decorated' => false,
            'interactive' => false,
        ));

        $this->assertEquals(0, $exitCode, 'Returns 0 in case of success');
        $this->assertRegExp('/Tenant "tenant" has been created./', $commandTester->getDisplay());
    }

    public function testExecuteInteractiveWithQuestionHelper()
    {
        if (!class_exists('Symfony\Component\Console\Helper\QuestionHelper')) {
            $this->markTestSkipped('The question helper not available.');
        }

        $application = new Application();

        $helper = $this->getMock('Symfony\Component\Console\Helper\QuestionHelper', array(
            'ask',
        ));
        $helper->expects($this->at(0))
            ->method('ask')
            ->will($this->returnValue('tenant'));

        $application->getHelperSet()->set($helper, 'question');

        $commandTester = $this->createCommandTester($this->getContainer('tenant'), $application);
        $exitCode = $commandTester->execute(array(), array(
            'decorated' => false,
            'interactive' => true,
        ));

        $this->assertEquals(0, $exitCode, 'Returns 0 in case of success');
        $this->assertRegExp('/Tenant "tenant" has been created./', $commandTester->getDisplay());
    }

    private function createCommandTester(ContainerInterface $container, Application $application = null)
    {
        if (null === $application) {
            $application = new Application();
        }

        $application->setAutoExit(false);

        $command = new CreateTenantCommand();
        $command->setContainer($container);

        $application->add($command);

        return new CommandTester($application->find('cvele:tenant:create'));
    }

    private function getContainer($tenant)
    {
        $container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');

        $manager = $this->getMockBuilder('Cvele\MultiTenantBundle\Model\TenantManager')
            ->disableOriginalConstructor()
            ->getMock();

        $manager
            ->expects($this->once())
            ->method('createTenant')
            ->will($this->returnValue(new Tenant()))
        ;

        $container
            ->expects($this->once())
            ->method('get')
            ->with('multi_tenant.tenant_manager')
            ->will($this->returnValue($manager));

        return $container;
    }
}
