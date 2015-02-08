<?php namespace AdamWathan\EloquentOAuth\Installation;

use Carbon\Carbon;
use Illuminate\Filesystem\Filesystem;

class MigrationPublisher
{
    protected $files;
    protected $existing = [];

    public function __construct(Filesystem $files)
    {
        $this->files = $files;
    }

    public function publish($source, $destination)
    {
        $add = 0;
        $published = [];

        foreach ($this->getFreshMigrations($source, $destination) as $file) {
            $add++;
            $newName = $this->getNewMigrationName($file, $add);
            $this->files->copy($file, $newName = $destination.'/'.$newName);
            $published[] = $newName;
        }

        return $published;
    }

    protected function getFreshMigrations($source, $destination)
    {
        return array_filter($this->getPackageMigrations($source), function ($file) use ($destination) {
            return ! $this->migrationExists($file, $destination);
        });
    }

    public function migrationExists($migration, $destination)
    {
        $existing = $this->getExistingMigrationNames($destination);

        return in_array(substr(basename($migration), 18), $existing);
    }

    public function getExistingMigrationNames($destination)
    {
        if (isset($this->existing[$destination])) {
            return $this->existing[$destination];
        }

        return $this->existing[$destination] = array_map(function ($file) {
            return substr(basename($file), 18);
        }, $this->files->files($destination));
    }

    protected function getPackageMigrations($source)
    {
        $files = array_filter($this->files->files($source), function ($file) {
            return ! starts_with($file, '.');
        });

        sort($files);
        return $files;
    }

    protected function getNewMigrationName($file, $add)
    {
        return Carbon::now()->addSeconds($add)->format('Y_m_d_His').substr(basename($file), 17);
    }
}
