<?php

namespace App\Tests\Entity\Message;

use App\Entity\Message\Message;
use App\Entity\Message\UserHasMessageRead;
use App\Entity\User\Agent;
use PHPUnit\Framework\TestCase;

class UserHasMessageReadUnitTest extends TestCase
{
    public function testIsTrue(): void
    {
        $msgRead = new UserHasMessageRead();
        $message = new Message();
        $msgRead->setMessage($message);
        $recipient = new Agent();
        $msgRead->setRecipient($recipient);

        $this->assertTrue($msgRead->getMessage() === $message);        
        $this->assertTrue($msgRead->getRecipient() === $recipient);
        $this->assertTrue($msgRead->getIsRead() === false);      

    }

    public function testIsFalse(): void
    {
        $msgRead = new UserHasMessageRead();
        $message = new Message();
        $msgRead->setMessage($message);
        $recipient = new Agent();
        $msgRead->setRecipient($recipient);

        $this->assertFalse($msgRead->getMessage() === '$message');        
        $this->assertFalse($msgRead->getRecipient() === '$recipient');
        $this->assertFalse($msgRead->getIsRead());          

    }

    public function testIsEmpty(): void
    {
        $msgRead = new UserHasMessageRead();

        $this->assertEmpty($msgRead->getMessage());        
        $this->assertEmpty($msgRead->getRecipient());
        $this->assertEmpty($msgRead->getIsRead());         

    }
}
