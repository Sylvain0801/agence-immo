<?php

namespace App\Tests\Entity\Faq;

use App\Entity\Faq\CategoryFaq;
use App\Entity\Faq\Faq;
use PHPUnit\Framework\TestCase;

class CategoryFaqUnitTest extends TestCase
{
    public function testIsTrue(): void
    {
        $category = new CategoryFaq();
        $category->setName('category');

        $faq = new Faq();
        $category->addFaq($faq);

        $this->assertTrue($category->getName() === 'category');        
        $this->assertTrue($category->getFaqs()[0] === $faq);        

    }

    public function testIsFalse(): void
    {
        $category = new CategoryFaq();
        $category->setName('category');

        $faq = new Faq();
        $category->addFaq($faq);

        $this->assertFalse($category->getName() === 'categ');
        $this->assertFalse($category->getFaqs()[0] === '$faq');         

    }

    public function testIsEmpty(): void
    {
        $category = new CategoryFaq();

        $this->assertEmpty($category->getName());        
        $this->assertEmpty($category->getSlug());        
        $this->assertEmpty($category->getFaqs());        

    }
}
