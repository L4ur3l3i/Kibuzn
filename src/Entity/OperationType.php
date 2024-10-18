<?php

namespace Kibuzn\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Kibuzn\Repository\OperationTypeRepository;

#[ORM\Entity(repositoryClass: OperationTypeRepository::class)]
class OperationType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column(length: 50)]
    private ?string $alias = null;

    /**
     * @var Collection<int, Transaction>
     */
    #[ORM\OneToMany(targetEntity: Transaction::class, mappedBy: 'type')]
    private Collection $transactions;

    /**
     * @var Collection<int, RecurringTransaction>
     */
    #[ORM\OneToMany(targetEntity: RecurringTransaction::class, mappedBy: 'Type')]
    private Collection $recurringTransactions;

    public function __construct()
    {
        $this->transactions = new ArrayCollection();
        $this->recurringTransactions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getAlias(): ?string
    {
        return $this->alias;
    }

    public function setAlias(string $alias): static
    {
        $this->alias = $alias;

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
            $transaction->setType($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): static
    {
        if ($this->transactions->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getType() === $this) {
                $transaction->setType(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, RecurringTransaction>
     */
    public function getRecurringTransactions(): Collection
    {
        return $this->recurringTransactions;
    }

    public function addRecurringTransaction(RecurringTransaction $recurringTransaction): static
    {
        if (!$this->recurringTransactions->contains($recurringTransaction)) {
            $this->recurringTransactions->add($recurringTransaction);
            $recurringTransaction->setType($this);
        }

        return $this;
    }

    public function removeRecurringTransaction(RecurringTransaction $recurringTransaction): static
    {
        if ($this->recurringTransactions->removeElement($recurringTransaction)) {
            // set the owning side to null (unless already changed)
            if ($recurringTransaction->getType() === $this) {
                $recurringTransaction->setType(null);
            }
        }

        return $this;
    }
}
