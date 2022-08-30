<?php

namespace App\WebContent;

use App\Entity\LocalBusiness;
use App\Entity\ImageMediaObject;
use App\Entity\Person;

class Form extends AbstractWebContent
{
    public function saveFormContact($data, $attachments = [])
    {
        if(isset($data['email'])) {

            $message = $this->dataAction->createMessageDemand($data);
            
            $person = $this->manager->getRepository(Person::class)
                ->findOneBy(['email' => $data['email']])
            ;
            if(null === $person) {
                if (isset($data['streetAddress'])){
                    $address = $this->dataAction->createAddressDemand($data);
                    $person = $this->dataAction->createPersonDemand($data);
                    $person->addAddress($address);
                    $this->manager->persist($address);
                }else{
                    $person = $this->dataAction->createPersonDemand($data);
                }
                $this->manager->persist($person);
                $this->manager->flush();

            } else {
                $person = $this->dataAction->hydratePersonDemand($data, $person);
            }
            $message->setSender($person);

            if(isset($data['local_business'])) {
                $localBusiness = $this->manager->getRepository(LocalBusiness::class)
                    ->find($data['local_business'])
                ;
                $message->setLocalBusiness($localBusiness);
                $data['local_business'] = $localBusiness->getName();
            }
            
            if(!empty($attachments)) {
                $pathDirectoryMedia = $this->container->getParameter('path_directory_media');
                foreach($attachments as $attachment) {
                    $media = new ImageMediaObject();
                    $media->setFile($attachment);
                    $this->manager->persist($media);
                    $this->manager->flush();
                    $data['attachments'][] = $pathDirectoryMedia . DIRECTORY_SEPARATOR . $media->getOriginalFilename();
                    $message->addMessageAttachment($media); 
                }
            }
            
            $this->manager->persist($message);
            $this->manager->flush();
        }

        return $data;
    }

    public function dataFieldTranslation($data)
    {   
        $array = [];
        foreach($data as $key=>$item) {
            $index = $this->translator->trans($key, [], 'messages');
            $array[$index] = $item;
        }

        return $array;
    }
}
