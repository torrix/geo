<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class Postcodes extends AbstractMigration
{
    public function change(): void
    {
        $this->table('postcodes')
             ->addColumn('postcode', 'string', ['length' => 32])
             ->addColumn('easting', 'string', ['length' => 32])
             ->addColumn('northing', 'string', ['length' => 32])
             ->addTimestamps()
             ->create();
    }
}
