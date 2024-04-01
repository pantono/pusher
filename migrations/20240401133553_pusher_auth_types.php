<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class PusherAuthTypes extends AbstractMigration
{
    public function change(): void
    {
        $this->table('pusher_auth_type')
            ->addColumn('type', 'string')
            ->addColumn('permission', 'string')
            ->create();
    }
}
