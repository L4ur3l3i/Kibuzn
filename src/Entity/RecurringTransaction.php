<?php

namespace Kibuzn\Entity;

use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Kibuzn\Repository\RecurringTransactionRepository;

#[ORM\Entity(repositoryClass: RecurringTransactionRepository::class)]
#[ORM\HasLifecycleCallbacks]
class RecurringTransaction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

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

    /**
     * @var Collection<int, Transaction>
     */
    #[ORM\OneToMany(targetEntity: Transaction::class, mappedBy: 'recurring_transaction_id')]
    private Collection $transactions;

    #[ORM\ManyToOne(inversedBy: 'recurringTransactions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Account $account = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?DateTimeInterface $start_date = null;

    #[ORM\Column(nullable: true)]
    private ?int $iterations = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?DateTimeInterface $end_date = null;

    #[ORM\Column(length: 20)]
    private ?string $interval_type = null;

    #[ORM\Column]
    private ?int $interval_value = null;

    #[ORM\ManyToOne(inversedBy: 'recurringTransactions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?OperationType $Type = null;

    public function __construct()
    {
        $this->transactions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCreatedAt(): ?DateTimeImmutable
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

    public function getUpdatedAt(): ?DateTimeImmutable
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

    /**
     * @return Collection<int, Transaction>
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    public function addTransaction(Transaction $transaction): static
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions->add($transaction);
            $transaction->setRecurringTransaction($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): static
    {
        if ($this->transactions->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getRecurringTransaction() === $this) {
                $transaction->setRecurringTransaction(null);
            }
        }

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

    public function getStartDate(): ?DateTimeInterface
    {
        return $this->start_date;
    }

    public function setStartDate(DateTimeInterface $start_date): static
    {
        $this->start_date = $start_date;

        return $this;
    }

    public function getIterations(): ?int
    {
        return $this->iterations;
    }

    public function setIterations(?int $iterations): static
    {
        $this->iterations = $iterations;

        return $this;
    }

    public function getEndDate(): ?DateTimeInterface
    {
        return $this->end_date;
    }

    public function setEndDate(?DateTimeInterface $end_date): static
    {
        $this->end_date = $end_date;

        return $this;
    }

    public function getIntervalType(): ?string
    {
        return $this->interval_type;
    }

    public function setIntervalType(string $interval_type): static
    {
        $this->interval_type = $interval_type;

        return $this;
    }

    public function getIntervalValue(): ?int
    {
        return $this->interval_value;
    }

    public function setIntervalValue(int $interval_value): static
    {
        $this->interval_value = $interval_value;

        return $this;
    }

    public function getType(): ?OperationType
    {
        return $this->Type;
    }

    public function setType(?OperationType $Type): static
    {
        $this->Type = $Type;

        return $this;
    }
}
