<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

#[ODM\Document(collection: 'contact_messages')]
class ContactLog
{
    #[ODM\Id]
    public ?string $id = null;

    #[ODM\Field(type: 'string')]
    public string $name;

    #[ODM\Field(type: 'string', nullable: true)]
    public ?string $phone = null;

    #[ODM\Field(type: 'string')]
    public string $email;

    #[ODM\Field(type: 'string')]
    public string $message;

    #[ODM\Field(type: 'date')]
    public \DateTimeInterface $createdAt;
}
