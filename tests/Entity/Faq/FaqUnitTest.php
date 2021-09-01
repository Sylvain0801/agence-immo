<?php

namespace App\Tests\Entity\Faq;

use App\Entity\Faq\CategoryFaq;
use App\Entity\Faq\Faq;
use PHPUnit\Framework\TestCase;

class FaqUnitTest extends TestCase
{
    public function testIsTrue(): void
    {
        $faq = new Faq();

        $faq->setQuestion('question');
        $faq->setAnswer('answer');
        $faq->setEmail('email@demo.fr');

        $category = new CategoryFaq();
        $faq->setCategory($category);

        $this->assertTrue($faq->getQuestion() === 'question');        
        $this->assertTrue($faq->getAnswer() === 'answer');        
        $this->assertTrue($faq->getEmail() === 'email@demo.fr');        
        $this->assertTrue($faq->getCategory() === $category);        

    }

    public function testIsFalse(): void
    {
        $faq = new Faq();

        $faq->setQuestion('question');
        $faq->setAnswer('answer');
        $faq->setEmail('email@demo.fr');

        $category = new CategoryFaq();
        $faq->setCategory($category);

        $this->assertFalse($faq->getQuestion() === 'ques');        
        $this->assertFalse($faq->getAnswer() === 'ans');        
        $this->assertFalse($faq->getEmail() === 'email@d');   
        $this->assertFalse($faq->getCategory() === 'categor');    

    }

    public function testIsEmpty(): void
    {
        $faq = new Faq();

        $this->assertEmpty($faq->getQuestion());        
        $this->assertEmpty($faq->getAnswer());        
        $this->assertEmpty($faq->getEmail());        
        $this->assertEmpty($faq->getCategory());        

    }
}
