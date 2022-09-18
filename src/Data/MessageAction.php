<?php

namespace App\Data;

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
        $message->setSubject($data['subject']);
        $message->setText($data['text']);
        $message->setOrigin($data['origin']);
        $message->setDateSent(new \DateTime('now'));

        return $message;
    }
}
