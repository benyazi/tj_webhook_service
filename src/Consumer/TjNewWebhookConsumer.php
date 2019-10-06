<?php
namespace App\Consumer;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqpLib\Message\AMQPMessage;

class TjNewWebhookConsumer implements ConsumerInterface
{
    /**
     * @param AMQPMessage $msg
     * @return void
     */
    public function execute(AMQPMessage $msg)
    {
        echo '==== START ======'.PHP_EOL;
        echo 'New webhook'.PHP_EOL;
        var_dump($msg->getBody());
        echo '===== END ======'.PHP_EOL;
    }
}