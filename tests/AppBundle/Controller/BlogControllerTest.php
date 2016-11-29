<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tests\AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use AppBundle\Entity\Post;
use AppBundle\Entity\Comment;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;


/**
 * Functional test for the controllers defined inside BlogController.
 *
 * See http://symfony.com/doc/current/book/testing.html#functional-tests
 *
 * Execute the application tests using this command (requires PHPUnit to be installed):
 *
 *     $ cd your-symfony-project/
 *     $ phpunit -c app
 *
 */
class BlogControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/en/blog/');

        $this->assertCount(
            Post::NUM_ITEMS,
            $crawler->filter('article.post'),
            'The homepage displays the right number of posts.'
        );

    }

    public function testTitle()
    {
        // Parse all the articles from the frontpage
        $client = static::createClient();
        $blogCrawler = $client->request('GET', '/en/blog/');
        $links = $blogCrawler->filter('h2 > a');
        for ($i = 0 ; $i < count($links) ; $i++)
        {
            // For each article, compare the title to the first h1 header
            $url =  $links->eq($i)->link()->getUri();
            $articleCrawler = $client->request('GET', $url);
            $h1 = $articleCrawler->filter('h1')->eq(0)->text();
            $title = $client->getRequest()->attributes->get('post')->getTitle();
            $this->assertEquals($title, $h1);
        }
    }

    public function testComments()
    {
        $client = static::createClient();
        $blogCrawler = $client->request('GET', '/en/blog/');
        $links = $blogCrawler->filter('h2 > a');
        for ($i = 0 ; $i < count($links) ; $i++)
        {
            $url =  $links->eq($i)->link()->getUri();
            $articleCrawler = $client->request('GET', $url);
            // Comments or No comments message
            $commentCounter = count($articleCrawler->filterXPath("//body/descendant::div[@class='row post-comment']")) +
                             count($articleCrawler->filterXPath("//body/descendant::div[@class='post-comment']"));
            $this->assertGreaterThan(0, $commentCounter);
        }
    }
}
