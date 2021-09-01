<?php

namespace App\Tests\Entity\Message;

use App\Entity\Message\Message;
use App\Entity\Message\UserHasMessageRead;
use App\Entity\User\Tenant;
use PHPUnit\Framework\TestCase;

class MessageUnitTest extends TestCase
{
    public function testIsTrue(): void
    {
        $message = new Message();
        $message->setSubject('subject');
        $message->setContent('content');

        $sender = new Tenant();
        $message->setSender($sender);

        $recipient = new UserHasMessageRead();
        $message->addRecipient($recipient);

        $this->assertTrue($message->getSubject() === 'subject');        
        $this->assertTrue($message->getContent() === 'content');        
        $this->assertTrue($message->getSender() === $sender);        
        $this->assertTrue($message->getRecipients()[0] === $recipient);        

    }

    public function testIsFalse(): void
    {
        $message = new Message();
        $message->setSubject('subject');
        $message->setContent('content');

        $sender = new Tenant();
        $message->setSender($sender);

        $recipient = new UserHasMessageRead();
        $message->addRecipient($recipient);

        $this->assertFalse($message->getSubject() === 'subj');        
        $this->assertFalse($message->getContent() === 'cont');        
        $this->assertFalse($message->getSender() === '$sender');        
        $this->assertFalse($message->getRecipients()[0] === '$recipient');       

    }

    public function testIsEmpty(): void
    {
        $message = new Message();

        $this->assertEmpty($message->getSubject());        
        $this->assertEmpty($message->getContent());        
        $this->assertEmpty($message->getSender());        
        $this->assertEmpty($message->getRecipients()[0]);      

    }
}
