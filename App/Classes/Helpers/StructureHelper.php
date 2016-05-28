<?php
namespace App\Classes\Helpers;


use App\Models\Structure;
use SORM\DataSource;

class StructureHelper
{

    protected $controllersRoot;

    public function __construct($controllersRoot)
    {
        $this->controllersRoot = $controllersRoot;
    }

    public function getPath(Structure $oStructure)
    {
        $path = [];
        /** @var Structure $oCurrentStructure */
        $oCurrentStructure = $oStructure;
        $path[] = ucfirst($oCurrentStructure->path);
        while ($oCurrentStructure->structure_id != 0) {
            $oCurrentStructure = DataSource::factory(Structure::cls(), $oCurrentStructure->structure_id);
            $path[] = ucfirst($oCurrentStructure->path);
        }

        return implode('\\', array_reverse($path));
    }

    public function preparePath($path)
    {
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

    public function removeProxyController($path)
    {
        $currentDirPath = $this->preparePath($path);
        $lastPath = basename($path);

        $name = $path == '' ? 'Main' : ucfirst($lastPath);
        $filePath = "{$currentDirPath}Controller{$name}.php";
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    public function createProxyController($path)
    {
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


use App\Classes\StructureModuleController;

class Controller' . $name . ' extends StructureModuleController {}';

        file_put_contents("{$currentDirPath}Controller{$name}.php", $content);
    }

} 