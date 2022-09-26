<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['username'], message: 'There is already an account with this username')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 12, unique: true)]
    private ?string $username = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(nullable: true)]
    private ?int $R = null;

    #[ORM\Column(nullable: true)]
    private ?int $P = null;

    #[ORM\Column(nullable: true)]
    private ?int $S = null;

    #[ORM\Column(nullable: true)]
    private ?int $Win = null;

    #[ORM\Column(nullable: true)]
    private ?int $Lose = null;

    #[ORM\Column(nullable: true)]
    private ?int $Eq = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
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

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getR(): ?int
    {
        return $this->R;
    }

    public function setR(?int $R): self
    {
        $this->R = $R;

        return $this;
    }

    public function getP(): ?int
    {
        return $this->P;
    }

    public function setP(?int $P): self
    {
        $this->P = $P;

        return $this;
    }

    public function getS(): ?int
    {
        return $this->S;
    }

    public function setS(?int $S): self
    {
        $this->S = $S;

        return $this;
    }

    public function getWin(): ?int
    {
        return $this->Win;
    }

    public function setWin(?int $Win): self
    {
        $this->Win = $Win;

        return $this;
    }

    public function getLose(): ?int
    {
        return $this->Lose;
    }

    public function setLose(?int $Lose): self
    {
        $this->Lose = $Lose;

        return $this;
    }

    public function getEq(): ?int
    {
        return $this->Eq;
    }

    public function setEq(?int $Eq): self
    {
        $this->Eq = $Eq;

        return $this;
    }
}
