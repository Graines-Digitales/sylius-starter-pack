<?php

namespace App\WebContent;


class User extends AbstractWebContent
{   
    public function defineAuthor($entity)
    {
        if (null == $entity->getId() && $this->user) {
            if (is_callable([$entity, 'setUserCreated'])) {
                $entity->setUserCreated($this->user);
            }
        }

        if (is_callable([$entity, 'setUserLastModified']) && $this->user) {
            $entity->setUserLastModified($this->user);
        }

        return $entity;
    }

    public function getRoles()
    {
        $choices = [
            'Accès administration' => 'ROLE_ADMINISTRATION_ACCESS', // Obligatoire pour un accès au BO
        ];

        if (in_array('ROLE_DEV', $this->user->getRoles())) {
            $choices += [
                'Dev' => 'ROLE_DEV', 
            ];
        }


        return $choices;
    }

}
