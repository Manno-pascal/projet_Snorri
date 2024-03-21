<?php

namespace App\Tests\tool;

use App\Entity\Tool;
use App\Entity\User;
use App\Enum\CategoryEnum;
use App\Enum\StatusEnum;
use PHPUnit\Framework\TestCase;

class ToolTest extends TestCase
{

    public function testGetDescription()
    {
        $tool = new Tool();
        $description = "GitHub est un service web d'hébergement et de gestion de développement de logiciels, utilisant le logiciel de gestion de versions Git. Ce site est développé en Ruby on Rails et Erlang par Chris Wanstrath, PJ Hyett et Tom Preston-Werner. GitHub propose des comptes professionnels payants, ainsi que des comptes gratuits pour les projets de logiciels libres.";
        $tool->setDescription($description);
        static::assertEquals($description, $tool->getDescription());
    }

    public function testGetTitle()
    {
        $tool = new Tool();
        $tool->setTitle('Github');
        static::assertEquals('Github', $tool->getTitle());
    }

    public function testGetAuthor()
    {
        $tool = new Tool();
        $user = new User();
        $tool->setUserSender($user);
        static::assertEquals($user, $tool->getUserSender());
    }

    public function testGetUrl()
    {
        $tool = new Tool();
        $tool->setUrl('https://github.com/');
        static::assertEquals('https://github.com/', $tool->getUrl());
    }

    public function testGetImage()
    {
        $tool = new Tool();
        $tool->setImage('tools/github.png');
        static::assertEquals('/uploads/image/tools/github.png', $tool->getImage());
    }

    public function testGetCategory()
    {
        $tool = new Tool();
        $tool->setCategory(CategoryEnum::CATEGORY_OTHER->value);
        static::assertEquals(CategoryEnum::CATEGORY_OTHER->value, $tool->getCategory());
    }

    public function testGetStatus()
    {
        $tool = new Tool();
        $tool->setStatus(StatusEnum::STATUS_NEW->value);
        static::assertEquals(StatusEnum::STATUS_NEW->value, $tool->getStatus());
    }
}
