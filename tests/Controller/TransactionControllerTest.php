<?php

namespace Kibuzn\Tests\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Kibuzn\Entity\Transaction;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class TransactionControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/transaction/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(Transaction::class);

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
        self::assertPageTitleContains('Transaction index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'transaction[transaction_date]' => 'Testing',
            'transaction[amount]' => 'Testing',
            'transaction[type]' => 'Testing',
            'transaction[description]' => 'Testing',
            'transaction[recurrent]' => 'Testing',
            'transaction[recurrence_number]' => 'Testing',
            'transaction[created_at]' => 'Testing',
            'transaction[updated_at]' => 'Testing',
            'transaction[deleted_at]' => 'Testing',
            'transaction[recurring_transaction_id]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->repository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Transaction();
        $fixture->setTransaction_date('My Title');
        $fixture->setAmount('My Title');
        $fixture->setType('My Title');
        $fixture->setDescription('My Title');
        $fixture->setRecurrent('My Title');
        $fixture->setRecurrence_number('My Title');
        $fixture->setCreated_at('My Title');
        $fixture->setUpdated_at('My Title');
        $fixture->setDeleted_at('My Title');
        $fixture->setRecurring_transaction_id('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Transaction');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Transaction();
        $fixture->setTransaction_date('Value');
        $fixture->setAmount('Value');
        $fixture->setType('Value');
        $fixture->setDescription('Value');
        $fixture->setRecurrent('Value');
        $fixture->setRecurrence_number('Value');
        $fixture->setCreated_at('Value');
        $fixture->setUpdated_at('Value');
        $fixture->setDeleted_at('Value');
        $fixture->setRecurring_transaction_id('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'transaction[transaction_date]' => 'Something New',
            'transaction[amount]' => 'Something New',
            'transaction[type]' => 'Something New',
            'transaction[description]' => 'Something New',
            'transaction[recurrent]' => 'Something New',
            'transaction[recurrence_number]' => 'Something New',
            'transaction[created_at]' => 'Something New',
            'transaction[updated_at]' => 'Something New',
            'transaction[deleted_at]' => 'Something New',
            'transaction[recurring_transaction_id]' => 'Something New',
        ]);

        self::assertResponseRedirects('/transaction/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getTransaction_date());
        self::assertSame('Something New', $fixture[0]->getAmount());
        self::assertSame('Something New', $fixture[0]->getType());
        self::assertSame('Something New', $fixture[0]->getDescription());
        self::assertSame('Something New', $fixture[0]->getRecurrent());
        self::assertSame('Something New', $fixture[0]->getRecurrence_number());
        self::assertSame('Something New', $fixture[0]->getCreated_at());
        self::assertSame('Something New', $fixture[0]->getUpdated_at());
        self::assertSame('Something New', $fixture[0]->getDeleted_at());
        self::assertSame('Something New', $fixture[0]->getRecurring_transaction_id());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Transaction();
        $fixture->setTransaction_date('Value');
        $fixture->setAmount('Value');
        $fixture->setType('Value');
        $fixture->setDescription('Value');
        $fixture->setRecurrent('Value');
        $fixture->setRecurrence_number('Value');
        $fixture->setCreated_at('Value');
        $fixture->setUpdated_at('Value');
        $fixture->setDeleted_at('Value');
        $fixture->setRecurring_transaction_id('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/transaction/');
        self::assertSame(0, $this->repository->count([]));
    }
}
