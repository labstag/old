<?php

namespace Labstag\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class FilesFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $folder = 'public/file';
        if (is_dir($folder)) {
            $this->delTree($folder);
        }
    }

    public function delTree($dir)
    {
        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            (is_dir("{$dir}/{$file}")) ? $this->delTree("{$dir}/{$file}") : unlink("{$dir}/{$file}");
        }

        return rmdir($dir);
    }
}
