<?php

namespace App\Entity\Comment;

use MabeEnum\Enum;

class State extends Enum
{
    public const SUBMITTED = 'Submitted';
    public const SPAM = 'Spam';
    public const PUBLISHED = 'Published';
}
