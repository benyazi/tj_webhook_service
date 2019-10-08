<?php
namespace App\Consumer;

use App\Service\TjUserService;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;
use PhpAmqpLib\Message\AMQPMessage;

class TjUpdateUserInfoConsumer implements ConsumerInterface
{
    private $tjUserService;

    public function __construct(TjUserService $tjUserService)
    {
        $this->tjUserService = $tjUserService;
    }
    /**
     * @param AMQPMessage $msg
     * @return void
     */
    public function execute(AMQPMessage $msg)
    {
        echo '==== START UPDATE USER ===='.PHP_EOL;
        $body = $msg->getBody();
        $data = unserialize($body);
        $userId = $data['userId'];
        $this->tjUserService->updateUserInfo($userId);
        echo '==== END UPDATE USER ===='.PHP_EOL;
    }
}