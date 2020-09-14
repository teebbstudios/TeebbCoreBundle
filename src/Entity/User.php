<?php


namespace Teebb\CoreBundle\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Teebb\CoreBundle\Repository\UserRepository")
 * @Assert\EnableAutoMapping
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
class User extends BaseContent implements UserInterface
{
    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    protected $firstName;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    protected $lastName;

    /**
     * @var string
     * @ORM\Column(type="string", unique=true)
     */
    protected $username;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $usernameCanonical;

    /**
     * @var string
     * @ORM\Column(type="string", unique=true)
     */
    protected $email;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $emailCanonical;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $password;

    /**
     * no need persist
     * @var string|null
     */
    protected $plainPassword;

    /**
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    protected $enabled;

    /**
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    protected $accountNonExpired;

    /**
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    protected $credentialsNonExpired;

    /**
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    protected $accountNonLocked;

    /**
     * @var array
     * @ORM\Column(type="json")
     */
    protected $roles = [];

    /**
     * @ORM\ManyToMany(targetEntity="Teebb\CoreBundle\Entity\Group")
     * @ORM\JoinTable(name="users_groups",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     */
    protected $groups;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    protected $salt;

    /**
     * @var \DateTime|null
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $lastLogin;

    public function __construct()
    {
        $this->enabled = false;
        $this->accountNonExpired = true;
        $this->accountNonLocked = true;
        $this->credentialsNonExpired = true;
        $this->groups = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string|null $firstName
     */
    public function setFirstName(?string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @param string|null $lastName
     */
    public function setLastName(?string $lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getUsernameCanonical(): string
    {
        return $this->usernameCanonical;
    }

    /**
     * @param string $usernameCanonical
     */
    public function setUsernameCanonical(string $usernameCanonical): void
    {
        $this->usernameCanonical = $usernameCanonical;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return string|null
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    /**
     * @param string|null $plainPassword
     */
    public function setPlainPassword(?string $plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     */
    public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }

    /**
     * @return bool
     */
    public function isAccountNonExpired(): bool
    {
        return $this->accountNonExpired;
    }

    /**
     * @param bool $accountNonExpired
     */
    public function setAccountNonExpired(bool $accountNonExpired): void
    {
        $this->accountNonExpired = $accountNonExpired;
    }

    /**
     * @return bool
     */
    public function isCredentialsNonExpired(): bool
    {
        return $this->credentialsNonExpired;
    }

    /**
     * @param bool $credentialsNonExpired
     */
    public function setCredentialsNonExpired(bool $credentialsNonExpired): void
    {
        $this->credentialsNonExpired = $credentialsNonExpired;
    }

    /**
     * @return bool
     */
    public function isAccountNonLocked(): bool
    {
        return $this->accountNonLocked;
    }

    /**
     * @param bool $accountNonLocked
     */
    public function setAccountNonLocked(bool $accountNonLocked): void
    {
        $this->accountNonLocked = $accountNonLocked;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param array $roles
     */
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    /**
     * 用户添加角色
     * @param string $role
     * @return array
     */
    public function addRole(string $role)
    {
        $this->roles[] = $role;
        return array_unique($this->roles);
    }

    /**
     * 移除角色
     * @param string $role
     */
    public function removeRole(string $role)
    {
        unset($role, $this->roles);
    }

    /**
     * @return string|null
     */
    public function getSalt(): ?string
    {
        return $this->salt;
    }

    /**
     * @param string|null $salt
     */
    public function setSalt(?string $salt): void
    {
        $this->salt = $salt;
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
        $this->plainPassword = null;
    }

    /**
     * @return string
     */
    public function getEmailCanonical(): string
    {
        return $this->emailCanonical;
    }

    /**
     * @param string $emailCanonical
     */
    public function setEmailCanonical(string $emailCanonical): void
    {
        $this->emailCanonical = $emailCanonical;
    }

    /**
     * @return \DateTime|null
     */
    public function getLastLogin(): ?\DateTime
    {
        return $this->lastLogin;
    }

    /**
     * @param \DateTime|null $lastLogin
     */
    public function setLastLogin(?\DateTime $lastLogin): void
    {
        $this->lastLogin = $lastLogin;
    }

    /**
     * @return Collection|Group[]
     */
    public function getGroups(): Collection
    {
        return $this->groups;
    }

    /**
     * @param Group $group
     * @return $this
     */
    public function addGroup(Group $group): self
    {
        if (!$this->groups->contains($group)) {
            $this->groups[] = $group;
        }

        return $this;
    }

    /**
     * @param Group $group
     * @return $this
     */
    public function removeGroup(Group $group): self
    {
        if ($this->groups->contains($group)) {
            $this->groups->removeElement($group);
        }

        return $this;
    }

}