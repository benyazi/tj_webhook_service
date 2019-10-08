<?php
namespace App\Consumer;

use App\Service\TjCommentService;
use App\Service\TjUserService;
use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use OldSound\RabbitMqBundle\RabbitMq\ProducerInterface;
use PhpAmqpLib\Message\AMQPMessage;

class TjNewWebhookConsumer implements ConsumerInterface
{
    private $tjUserService;
    private $tjCommentService;
    private $tjUpdateUserProducer;

    public function __construct(TjUserService $tjUserService, TjCommentService $tjCommentService, ProducerInterface $tjUpdateUserProducer)
    {
        $this->tjUserService = $tjUserService;
        $this->tjCommentService = $tjCommentService;
        $this->tjUpdateUserProducer = $tjUpdateUserProducer;
    }
    /**
     * @param AMQPMessage $msg
     * @return void
     */
    public function execute(AMQPMessage $msg)
    {
        echo '==== START ======'.PHP_EOL;
        $body = $msg->getBody();
        $json = unserialize($body);
        $data = json_decode($json, true);
        $data = $data['data'];
        if(isset($data['creator'])) {
            $tjUser = $this->tjUserService->checkOrCreateUserByData($data['creator']);
            if($this->tjUserService->isUserNeedUpdateInfo($tjUser)) {
                $this->tjUpdateUserProducer->publish(serialize(['userId' => $tjUser->getId()]));
            } else {
                echo 'USER NEEDNT UPDATE'.PHP_EOL;
            }
//            var_dump($data);
            $this->tjCommentService->checkOrCreateByData($data);
        } else {
            echo 'CREATOR DONT FOUND'.PHP_EOL;
        }
        echo '===== END ======'.PHP_EOL;
    }
}