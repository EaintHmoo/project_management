<?php

namespace App\Modules\Tenancy\Domain\Enums;

enum Permission: string
{
    case OrganizationUpdate = 'organization.update';
    case OrganizationDelete = 'organization.delete';

    case MemberInvite = 'member.invite';
    case MemberRemove = 'member.remove';
    case MemberRoleUpdate = 'member.role.update';

    case TeamCreate = 'team.create';
    case TeamUpdate = 'team.update';
    case TeamDelete = 'team.delete';

    case ProjectCreate = 'project.create';
    case ProjectUpdate = 'project.update';
    case ProjectDelete = 'project.delete';

    case TaskCreate = 'task.create';
    case TaskUpdate = 'task.update';
    case TaskAssign = 'task.assign';
    case TaskDelete = 'task.delete';

    case MeetingCreate = 'meeting.create';
    case MeetingUpdate = 'meeting.update';
    case MeetingCancel = 'meeting.cancel';
    case MeetingDelete = 'meeting.delete';

    case LabelManage = 'label.manage';
}
