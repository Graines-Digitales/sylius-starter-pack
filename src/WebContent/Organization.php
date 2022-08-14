<?php

namespace App\WebContent;


class Organization extends AbstractWebContent
{   
    public function getEmails()
    {
        $configurationProject = $this->container->getParameter('configuration_project');
        $organization = $this->manager->getRepository(\App\Entity\Organization::class)
                ->findOneBy(['slug' => $configurationProject['slug']]);

        return [ ...$this->container->getParameter('developers'), ...[ $organization->getEmail() ] ];
    }
}
