<?php

namespace App\Data\Action;

use App\Entity\Message;
use Symfony\Component\String\Slugger\SluggerInterface;


class MessageAction
{
    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }
    
    public function create($data = [])
    {
        $message = new Message();
        if(isset($data['slug-product'])) {
            $message->setAdditionalText($data['slug-product']);   
        }
        $message->setSubject($data['subject']);
        $message->setText($data['text']);
        $message->setOrigin($data['origin']);
        $message->setDateSent(new \DateTime('now'));

        return $message;
    }
}
