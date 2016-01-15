<?php
namespace App\Modules\Structures\Classes\Helpers;


use App\Modules\Structures\Models\Structure;
use SORM\DataSource;

class StructureHelper {

    protected $controllersRoot;

    public function __construct($controllersRoot) {
        $this->controllersRoot = $controllersRoot;
    }

    public function getPath(Structure $structure) {
        $path = [];
        /** @var Structure $currentStructure */
        $currentStructure = $structure;
        $path[] = ucfirst($currentStructure->path);
        while ($currentStructure->structure_id != 0) {
            $currentStructure = DataSource::factory(Structure::cls(), $currentStructure->structure_id);
            $path[] = ucfirst($currentStructure->path);
        }

        return implode('\\', array_reverse($path));
    }

    public function preparePath($path) {
        $pathElements = explode('\\', $path);
        array_pop($pathElements);
        $currentDirPath = "{$this->controllersRoot}";
        foreach ($pathElements as $element) {
            $currentDirPath .= ucfirst($element) . DIRECTORY_SEPARATOR;
            if (!file_exists($currentDirPath)) {
                mkdir($currentDirPath);
            }
        }

        return $currentDirPath;
    }

    public function removeProxyController($path) {
        $currentDirPath = $this->preparePath($path);
        $lastPath = basename($path);

        $name = $path == '' ? 'Main' : ucfirst($lastPath);
        $filePath = "{$currentDirPath}Controller{$name}.php";
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    public function createProxyController($path) {
        $currentDirPath = $this->preparePath($path);
        $lastPath = basename($path);
        $name = $path == '' ? 'Main' : ucfirst($lastPath);
        $explodedPath = explode('\\', $path);
        array_pop($explodedPath);
        $namespace = implode('\\', $explodedPath);
        if ($namespace != '') {
            $namespace = "\\{$namespace}";
        }

        $content = '<?php
namespace App\Controllers' . $namespace . ';


use App\Modules\Structures\Classes\MasterController;

class Controller' . $name . ' extends MasterController {}';

        file_put_contents("{$currentDirPath}Controller{$name}.php", $content);
    }

} 