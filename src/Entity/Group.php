<?php


namespace Teebb\CoreBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="Teebb\CoreBundle\Repository\GroupRepository")
 * @ORM\Table(name="groups")
 * @Assert\EnableAutoMapping
 *
 * @author Quan Weiwei <qww.zone@gmail.com>
 */
class Group
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string",name="group_name")
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(type="string",name="group_alias")
     */
    private $groupAlias;

    /**
     * @var array
     * @ORM\Column(type="array", name="group_roles")
     */
    private $roles = [];

    /**
     * @var array
     * @ORM\Column(type="array", name="group_permissions")
     */
    private $permissions = [];

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getGroupAlias(): string
    {
        return $this->groupAlias;
    }

    /**
     * @param string $groupAlias
     */
    public function setGroupAlias(string $groupAlias): void
    {
        $this->groupAlias = $groupAlias;
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
     * @param $role
     * @return $this
     */
    public function removeRole($role)
    {
        if (false !== $key = array_search(strtoupper($role), $this->roles, true)) {
            unset($this->roles[$key]);
            $this->roles = array_values($this->roles);
        }

        return $this;
    }

    /**
     * @param $role
     * @return bool
     */
    public function hasRole($role)
    {
        return in_array(strtoupper($role), $this->roles, true);
    }

    /**
     * @param $role
     * @return $this
     */
    public function addRole($role)
    {
        if (!$this->hasRole($role)) {
            $this->roles[] = strtoupper($role);
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getPermissions(): array
    {
        return $this->permissions;
    }

    /**
     * @param array $permissions
     */
    public function setPermissions(array $permissions): void
    {
        $this->permissions = $permissions;
    }

}