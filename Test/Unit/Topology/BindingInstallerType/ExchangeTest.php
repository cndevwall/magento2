<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Framework\Amqp\Test\Unit\Topology\BindingInstallerType;

use Magento\Framework\Amqp\Topology\BindingInstallerType\Exchange;
use PhpAmqpLib\Channel\AMQPChannel;
use Magento\Framework\MessageQueue\Topology\Config\ExchangeConfigItem\BindingInterface;

class ExchangeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Exchange
     */
    private $model;

    protected function setUp()
    {
        $this->model = new Exchange();
    }

    public function testInstall()
    {
        $channel = $this->getMock(AMQPChannel::class, [], [], '', false, false);
        $binding = $this->getMock(BindingInterface::class);
        $binding->expects($this->once())->method('getDestination')->willReturn('queue01');
        $binding->expects($this->once())->method('getTopic')->willReturn('topic01');
        $binding->expects($this->once())->method('getArguments')->willReturn(['some' => 'value']);

        $channel->expects($this->once())
            ->method('exchange_bind')
            ->with(
                'queue01',
                'magento',
                'topic01',
                false,
                ['some' => ['S', 'value']],
                null
            );
        $this->model->install($channel, $binding, 'magento');
    }
}
