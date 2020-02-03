<?php

namespace Labstag\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class FilesFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        unset($manager);
        $folder = 'public/file';
        if (is_dir($folder)) {
            $this->delTree($folder);
        }
    }

    public function delTree(string $dir): bool
    {
        $files = array_diff((array) scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            if (is_dir($dir.'/'.$file)) {
                $this->delTree($dir.'/'.$file);

                continue;
            }

            unlink($dir.'/'.$file);
        }

        return rmdir($dir);
    }
}
