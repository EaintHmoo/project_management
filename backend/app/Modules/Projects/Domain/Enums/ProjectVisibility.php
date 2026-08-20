<?php

namespace App\Modules\Projects\Domain\Enums;

enum ProjectVisibility: string
{
    case Organization = 'organization';
    case Team = 'team';
    case Private = 'private';
}
