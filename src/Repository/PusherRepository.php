<?php

namespace Pantono\Pusher\Repository;

use Pantono\Database\Repository\DefaultRepository;

class PusherRepository extends DefaultRepository
{
    public function getPermissionForType(string $type): ?string
    {
        $select = $this->getDb()->select('p.permission')->from('pusher_auth_type', 'p')
            ->whereParam('type=?', $type);

        $row = $this->getDb()->fetchRow($select);
        if (empty($row)) {
            return null;
        }
        return $row['permission'];
    }
}
