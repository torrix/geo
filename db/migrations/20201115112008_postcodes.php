<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class Postcodes extends AbstractMigration
{
    public function change(): void
    {
        $this->table('postcodes')
             ->addColumn('postcode', 'string', ['length' => 32])
             ->addColumn('quality', 'integer', ['length' => 32])
             ->addColumn('latitude', 'string', ['length' => 32])
             ->addColumn('longitude', 'string', ['length' => 32])
             ->addColumn('country', 'string', ['length' => 32])
             ->addColumn('nhs_region', 'string', ['length' => 32])
             ->addColumn('nhs_h_a', 'string', ['length' => 32])
             ->addColumn('county', 'string', ['length' => 32])
             ->addColumn('district', 'string', ['length' => 32])
             ->addColumn('ward', 'string', ['length' => 32])
             ->addTimestamps()
             ->create();
    }
}
