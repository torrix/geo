<?php


use Phinx\Seed\AbstractSeed;

class PostcodeSeeder extends AbstractSeed
{
    const FILE = 'https://api.os.uk/downloads/v1/products/CodePointOpen/downloads?area=GB&format=CSV&redirect';

    public function run()
    {
        echo 'Downloading CodePoint Open data' . PHP_EOL;
        $postcodes = file_get_contents(self::FILE);
        echo 'Saving CodePoint Open data' . PHP_EOL;
        file_put_contents('data/postcodes.zip', $postcodes);
        echo 'Unpacking CodePoint Open data' . PHP_EOL;
        shell_exec('cd data && unzip postcodes.zip');

        $postcodeTable = $this->table('postcodes');
        $postcodeTable->truncate();

        foreach (glob('data/Data/CSV/*.csv') as $filename) {
            $postcodes = [];
            echo 'Parsing ' . basename($filename) . PHP_EOL;
            if (($handle = fopen($filename, 'r')) !== false) {
                while (($data = fgetcsv($handle)) !== false) {
                    $postcodes[] = [
                        'postcode'   => $data[0],
                        'easting'    => $data[2],
                        'northing'   => $data[3],
                    ];
                }
                fclose($handle);
            }

            foreach ($postcodes as $postcode) {
                $postcodeTable = $this->table('postcodes');
                $postcodeTable->insert($postcode)
                              ->saveData();
            }
        }
    }
}
