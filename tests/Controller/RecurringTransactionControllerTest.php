<?php

namespace Kibuzn\Tests\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Kibuzn\Entity\RecurringTransaction;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class RecurringTransactionControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/recurring/transaction/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(RecurringTransaction::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('RecurringTransaction index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'recurring_transaction[amount]' => 'Testing',
            'recurring_transaction[description]' => 'Testing',
            'recurring_transaction[recurrence_interval]' => 'Testing',
            'recurring_transaction[recurrence_value]' => 'Testing',
            'recurring_transaction[recurrence_end_date]' => 'Testing',
            'recurring_transaction[created_at]' => 'Testing',
            'recurring_transaction[updated_at]' => 'Testing',
            'recurring_transaction[deleted_at]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->repository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new RecurringTransaction();
        $fixture->setAmount('My Title');
        $fixture->setDescription('My Title');
        $fixture->setRecurrence_interval('My Title');
        $fixture->setRecurrence_value('My Title');
        $fixture->setRecurrence_end_date('My Title');
        $fixture->setCreated_at('My Title');
        $fixture->setUpdated_at('My Title');
        $fixture->setDeleted_at('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('RecurringTransaction');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new RecurringTransaction();
        $fixture->setAmount('Value');
        $fixture->setDescription('Value');
        $fixture->setRecurrence_interval('Value');
        $fixture->setRecurrence_value('Value');
        $fixture->setRecurrence_end_date('Value');
        $fixture->setCreated_at('Value');
        $fixture->setUpdated_at('Value');
        $fixture->setDeleted_at('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'recurring_transaction[amount]' => 'Something New',
            'recurring_transaction[description]' => 'Something New',
            'recurring_transaction[recurrence_interval]' => 'Something New',
            'recurring_transaction[recurrence_value]' => 'Something New',
            'recurring_transaction[recurrence_end_date]' => 'Something New',
            'recurring_transaction[created_at]' => 'Something New',
            'recurring_transaction[updated_at]' => 'Something New',
            'recurring_transaction[deleted_at]' => 'Something New',
        ]);

        self::assertResponseRedirects('/recurring/transaction/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getAmount());
        self::assertSame('Something New', $fixture[0]->getDescription());
        self::assertSame('Something New', $fixture[0]->getRecurrence_interval());
        self::assertSame('Something New', $fixture[0]->getRecurrence_value());
        self::assertSame('Something New', $fixture[0]->getRecurrence_end_date());
        self::assertSame('Something New', $fixture[0]->getCreated_at());
        self::assertSame('Something New', $fixture[0]->getUpdated_at());
        self::assertSame('Something New', $fixture[0]->getDeleted_at());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new RecurringTransaction();
        $fixture->setAmount('Value');
        $fixture->setDescription('Value');
        $fixture->setRecurrence_interval('Value');
        $fixture->setRecurrence_value('Value');
        $fixture->setRecurrence_end_date('Value');
        $fixture->setCreated_at('Value');
        $fixture->setUpdated_at('Value');
        $fixture->setDeleted_at('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/recurring/transaction/');
        self::assertSame(0, $this->repository->count([]));
    }
}
