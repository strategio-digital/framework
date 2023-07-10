<?php
/**
 * Copyright (c) 2022 Strategio Digital s.r.o.
 * @author Jiří Zapletal (https://strategio.digital, jz@strategio.digital)
 */
declare(strict_types=1);

namespace Saas\Security\JWT;

use Saas\Database\Entity\Admin;
use Saas\Database\Entity\Auth\Role;
use Saas\Database\Entity\Auth\Token;
use Saas\Database\Interface\IAuthenticable;

class ClaimsFormatter
{
    /**
     * @param \Saas\Database\Interface\IAuthenticable $user
     * @return array<string, mixed>
     */
    public function format(IAuthenticable $user, Token $token): array
    {
        $resources = [];
        
        if (!$user instanceof Admin) {
            foreach ($user->getRoles() as $role) {
                foreach ($role->getResources() as $resource) {
                    if (!in_array($resource->getName(), $resources)) {
                        $resources[] = $resource->getName();
                    }
                }
            }
        }
        
        return [
            'bearer_token_id' => $token->getId(),
            'user' => [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'roles' => array_map(fn(Role $role) => $role->getName(), $user->getRoles()->toArray()),
                'resources' => $resources
            ]
        ];
    }
}