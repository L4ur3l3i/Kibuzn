<?php

namespace Kibuzn\Entity;

use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Kibuzn\Repository\TransactionRepository;

#[ORM\Entity(repositoryClass: TransactionRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Transaction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?DateTimeInterface $transaction_date = null;

    #[ORM\Column]
    private ?float $amount = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column]
    private ?DateTimeImmutable $created_at = null;

    #[ORM\Column]
    private ?DateTimeImmutable $updated_at = null;

    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $deleted_at = null;

    // Renamed recurring_transaction_id to recurringTransaction
    #[ORM\ManyToOne(inversedBy: 'transactions')]
    private ?RecurringTransaction $recurringTransaction = null;

    #[ORM\ManyToOne(inversedBy: 'transactions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Account $account = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?DateTimeInterface $value_date = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $statement_description = null;

    #[ORM\ManyToOne(inversedBy: 'transactions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?OperationType $type = null;

    #[ORM\Column(nullable: true)]
    private ?int $iteration_number = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTransactionDate(): ?DateTimeInterface
    {
        return $this->transaction_date;
    }

    public function setTransactionDate(DateTimeInterface $transaction_date): static
    {
        $this->transaction_date = $transaction_date;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->created_at;
    }

    #[ORM\PrePersist]
    public function setCreatedAt(): void
    {
        $now = new DateTimeImmutable();
        $this->created_at = $now;
        $this->updated_at = $now;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updated_at;
    }

    #[ORM\PreUpdate]
    public function setUpdatedAt(): void
    {
        $this->updated_at = new DateTimeImmutable();
    }

    public function getDeletedAt(): ?DateTimeImmutable
    {
        return $this->deleted_at;
    }

    public function setDeletedAt(?DateTimeImmutable $deleted_at): static
    {
        $this->deleted_at = $deleted_at;

        return $this;
    }

    // Updated method name to reflect the relationship object
    public function getRecurringTransaction(): ?RecurringTransaction
    {
        return $this->recurringTransaction;
    }

    // Updated method name to reflect the relationship object
    public function setRecurringTransaction(?RecurringTransaction $recurringTransaction): static
    {
        $this->recurringTransaction = $recurringTransaction;

        return $this;
    }

    public function getAccount(): ?Account
    {
        return $this->account;
    }

    public function setAccount(?Account $account): static
    {
        $this->account = $account;

        return $this;
    }

    public function getValueDate(): ?DateTimeInterface
    {
        return $this->value_date;
    }

    public function setValueDate(?DateTimeInterface $value_date): static
    {
        $this->value_date = $value_date;

        return $this;
    }

    public function getStatementDescription(): ?string
    {
        return $this->statement_description;
    }

    public function setStatementDescription(?string $statement_description): static
    {
        $this->statement_description = $statement_description;

        return $this;
    }

    public function getType(): ?OperationType
    {
        return $this->type;
    }

    public function setType(?OperationType $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getIterationNumber(): ?int
    {
        return $this->iteration_number;
    }

    public function setIterationNumber(?int $iteration_number): static
    {
        $this->iteration_number = $iteration_number;

        return $this;
    }
}
