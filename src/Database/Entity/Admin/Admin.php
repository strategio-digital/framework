<?php
/**
 * Copyright (c) 2023 Strategio Digital s.r.o.
 * @author Jiří Zapletal (https://strategio.dev, jz@strategio.dev)
 */
declare(strict_types=1);

namespace Saas\Database\Entity\Admin;

use Doctrine\ORM\Mapping as ORM;
use Nette\Security\Passwords;
use Saas\Database\Entity\EntityException;
use Saas\Database\Field\TCreatedAt;
use Saas\Database\Field\TUlid;
use Saas\Database\Field\TUpdatedAt;
use Saas\Database\Interface\CrudEntity;
use Saas\Database\Repository\AdminRepository;

#[ORM\Table(name: '`admin`')]
#[ORM\Entity(repositoryClass: AdminRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Admin implements CrudEntity
{
    use TUlid;
    use TCreatedAt;
    use TUpdatedAt;
    
    /** @var string[] */
    public array $visibleFields = ['id', 'createdAt', 'updatedAt', 'email', 'lastLogin'];
    
    #[ORM\Column(length: 64, unique: true, nullable: false)]
    protected string $email;
    
    #[ORM\Column(nullable: false)]
    protected string $password;
    
    #[ORM\Column(nullable: true)]
    protected ?\DateTime $lastLogin = null;
    
    /**
     * @param string $password
     * @return self
     * @throws \Saas\Database\Entity\EntityException
     */
    public function setPassword(string $password): self
    {
        $length = mb_strlen($password);
        
        if ($length < 6 || $length > 32) {
            throw new EntityException("Password length is not in range 6 ... 32 chars, {$length} chars given.");
        }
        
        $this->password = (new Passwords(PASSWORD_ARGON2ID))->hash($password);
        return $this;
    }
}