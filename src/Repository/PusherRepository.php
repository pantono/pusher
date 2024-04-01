<?php

namespace Pantono\Pusher\Repository;

use Pantono\Database\Repository\MysqlRepository;

class PusherRepository extends MysqlRepository
{
    public function getPermissionForType(string $type): ?string
    {
        $select = $this->getDb()->select()->from('pusher_auth_type', ['permission'])
            ->where('type=?', $type);

        $row = $this->getDb()->fetchRow($select);
        if (empty($row)) {
            return null;
        }
        return $row['permission'];
    }
}