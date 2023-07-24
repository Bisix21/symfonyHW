<?php

namespace App\Entity;

use App\Enum\RolesEnum;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column]
	private ?int $id = null;

	#[ORM\Column(length: 180, unique: true)]
	private ?string $email = null;

	#[ORM\Column]
	private array $roles = [];

	/**
	 * @var string The hashed password
	 */
	#[ORM\Column]
	private ?string $password = null;

	#[ORM\OneToMany(mappedBy: 'userId', targetEntity: CodeUrlPair::class)]
	private Collection $codeUrlPairs;

	public function __construct()
	{
		$this->codeUrlPairs = new ArrayCollection();
	}

	/**
	 * A visual identifier that represents this user.
	 *
	 * @see UserInterface
	 */
	public function getUserIdentifier(): string
	{
		return (string)$this->getId();
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getUserEmail(): string
	{
		return (string)$this->getEmail();
	}

	public function getEmail(): ?string
	{
		return $this->email;
	}

	public function setEmail(string $email): static
	{
		$this->email = $email;

		return $this;
	}

	/**
	 * @see UserInterface
	 */
	public function getRoles(): array
	{
		$roles = $this->roles;
		// guarantee every user at least has ROLE_USER
		$roles[] = RolesEnum::User;

		return array_unique($roles);
	}

	public function setRoles(array $roles): static
	{
		$this->roles = $roles;

		return $this;
	}

	/**
	 * @see PasswordAuthenticatedUserInterface
	 */
	public function getPassword(): string
	{
		return $this->password;
	}

	public function setPassword(string $password): static
	{
		$this->password = $password;

		return $this;
	}

	/**
	 * @see UserInterface
	 */
	public function eraseCredentials(): void
	{
		// If you store any temporary, sensitive data on the user, clear it here
		// $this->plainPassword = null;
	}

	/**
	 * @return Collection<int, CodeUrlPair>
	 */
	public function getCodeUrlPairs(): Collection
	{
		return $this->codeUrlPairs;
	}

	public function addCodeUrlPair(CodeUrlPair $codeUrlPair): static
	{
		if (!$this->codeUrlPairs->contains($codeUrlPair)) {
			$this->codeUrlPairs->add($codeUrlPair);
			$codeUrlPair->setUserId($this);
		}

		return $this;
	}

	public function removeCodeUrlPair(CodeUrlPair $codeUrlPair): static
	{
		if ($this->codeUrlPairs->removeElement($codeUrlPair)) {
			// set the owning side to null (unless already changed)
			if ($codeUrlPair->getUserId() === $this) {
				$codeUrlPair->setUserId(null);
			}
		}

		return $this;
	}
}
