<?php

namespace Sven\ArtisanView\CRUD;

use Exception;
use Sven\ArtisanView\BlockBuilder;
use Sven\ArtisanView\Config;
use Sven\ArtisanView\PathHelper;

class Generate
{
    /**
     * @var Config
     */
    private static $config;

    /**
     * @var ViewType
     */
    private static $viewType;

    /**
     * @param $view string
     * @param $type ViewType
     * @param Config $config
     * @param array $blocks
     * @param string | null $ui
     * @return void
     * @throws Exception
     */
    public static function viewType(string $view, ViewType $type, Config $config, array $blocks, string|null $ui): void
    {
        self::$config = $config;
        self::$viewType = $type;
        if (empty($ui)) {
            $ui = 'bootstrap';
        }

        $contents = BlockBuilder::build($blocks);

        switch ($type) {
            case ViewType::INDEX:
                self::index($view, $contents, $ui);
                break;
            case ViewType::CREATE:
                self::create($view, $contents, $ui);
                break;
            case ViewType::EDIT:
                self::edit($view, $contents, $ui);
                break;
            case ViewType::SHOW:
                self::show($view, $contents, $ui);
                break;
            case ViewType::DELETE:
                self::delete($view, $contents, $ui);
                break;
            default:
                throw new Exception('Invalid view type');
        }
    }

    /**
     * @param $view string
     * @param string $contents
     * @param string $ui
     * @return void
     * @throws Exception
     */
    private static function index(string $view, string $contents, string $ui): void
    {
        // Make path
        if (empty($path = self::makePath($view))) {
            return;
        }

        // Get model dynamically
        $modelClass = 'App\\Models\\' . ucfirst(self::$config->getGenerate());
        $model = new $modelClass;

        // Make contents based on UI
        switch ($ui) {
            case 'bootstrap':
                $contents = self::indexBootstrap($model, $contents);
                break;
            default:
                throw new Exception('Invalid UI');
        }


        // Write to file
        file_put_contents($path, $contents);
    }

    /**
     * @param string $name
     * @return string|null
     */
    private static function makePath(string $name): string|null
    {
        $path = PathHelper::normalizePath(self::$config->getPath() . DIRECTORY_SEPARATOR . $name);
        PathHelper::createIntermediateFolders($path);

        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }

        // If path is folder
        if (!is_file($path)) {
            $path .= DIRECTORY_SEPARATOR . self::$viewType->value . '.blade.php';
        }

        if (file_exists($path)) {
            print("SKIPPING {$path} : already exists\n");
            return null;
        }

        return $path;
    }

    private static function indexBootstrap($model, string $contents): string
    {
        $contents .= "@section('content')\n";

        // Make form header
        $collection = strtolower(self::$config->getGenerate());
        $ucCollection = ucfirst($collection);

        $contents .= <<<EOT
<div class="container">

    @foreach(\${$collection}s as \${$collection})
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">


                <div class="card-header">{{ __('$ucCollection') }}</div>


                <div class="card-body">

EOT;

        // Get model attributes
        foreach ($model->getFillable() as $field) {
            if (in_array($field, $model->getHidden())) {
                continue;
            }
            $ucField = ucfirst($field);

            $contents .= <<<EOT
                        <div class="row mb-3">
                            <label for="{$field}" class="col-md-4 col-form-label text-md-end">{{ __('{$ucField}') }}</label>

                            <div class="col-md-6">
                                <span id="{$field}" class="form-control">{{ old('{$field}', \${$collection}->{$field}) }}</span>
                            </div>
                        </div>


EOT;
        }

        $button = ucfirst(self::$viewType->value);

        $contents .= <<< EOT
                </div>
           </div>
      </div>
  </div>
  <br>
                @endforeach
</div>
@endsection
EOT;

        return $contents;
    }

    /**
     * @param $view string
     * @param string $contents
     * @param string $ui
     * @return void
     * @throws Exception
     */
    private static function create(string $view, string $contents, string $ui): void
    {
        // Make path
        if (empty($path = self::makePath($view))) {
            return;
        }

        // Get model dynamically
        $modelClass = 'App\\Models\\' . ucfirst(self::$config->getGenerate());
        $model = new $modelClass;

        // Make contents based on UI
        switch ($ui) {
            case 'bootstrap':
                $contents = self::createBootstrap($model, $contents);
                break;
            default:
                throw new Exception('Invalid UI');
        }


        // Write to file
        file_put_contents($path, $contents);
    }

    /**
     * @param $model
     * @param string $contents
     * @return string
     */
    private static function createBootstrap($model, string $contents): string
    {
        $contents .= "@section('content')\n";

        // Make form header
        $route = self::getRoute();

        $contents .= <<<EOT
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Create') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('{$route}') }}">
                    @csrf

EOT;

        // Get model attributes
        foreach ($model->getFillable() as $field) {
            $ucField = ucfirst($field);
            $type = $field;
            $contents .= <<<EOT
                        <div class="row mb-3">
                            <label for="{$field}" class="col-md-4 col-form-label text-md-end">{{ __('{$ucField}') }}</label>

                            <div class="col-md-6">
                                <input id="{$field}" type="{$type}" class="form-control @error('{$field}') is-invalid @enderror" name="{$field}" required autocomplete="{$field}" autofocus>
                            </div>
                        </div>

EOT;
        }

        $button = ucfirst(self::$viewType->value);

        $contents .= <<< EOT
                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-success form-control">
                                    {{ __('{$button}') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
EOT;


        return $contents;
    }

    /**
     * @return string
     */
    private static function getRoute(): string
    {
        return strtolower(self::$config->getGenerate()) . '.' . strtolower(self::$viewType->value);
    }

    /**
     * @param $view string
     * @param string $contents
     * @param string $ui
     * @return void
     * @throws Exception
     */
    private static function edit(string $view, string $contents, string $ui): void
    {
        // Make path
        if (empty($path = self::makePath($view))) {
            return;
        }

        // Get model dynamically
        $modelClass = 'App\\Models\\' . ucfirst(self::$config->getGenerate());
        $model = new $modelClass;

        // Make contents based on UI
        switch ($ui) {
            case 'bootstrap':
                $contents = self::editBootstrap($model, $contents);
                break;
            default:
                throw new Exception('Invalid UI');
        }


        // Write to file
        file_put_contents($path, $contents);
    }

    /**
     * @param $model
     * @param string $contents
     * @return string
     */
    private static function editBootstrap($model, string $contents): string
    {
        $contents .= "@section('content')\n";

        // Make form header
        $route = self::getRoute();
        $object = strtolower(self::$config->getGenerate());

        $contents .= <<<EOT
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Edit') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('{$route}') }}">
                    @csrf

EOT;

        // Get model attributes
        foreach ($model->getFillable() as $field) {
            $ucField = ucfirst($field);
            $type = $field;
            if (in_array($field, $model->getHidden())) {
                $val = '';
            } else {
                $val = "\${$object}->{$field}";
            }
            $contents .= <<<EOT
                        <div class="row mb-3">
                            <label for="{$field}" class="col-md-4 col-form-label text-md-end">{{ __('{$ucField}') }}</label>

                            <div class="col-md-6">
                                <input id="{$field}" type="{$type}" class="form-control @error('{$field}') is-invalid @enderror" name="{$field}" value="{{ old('{$field}', {$val}) }}" required autocomplete="{$field}" autofocus>
                            </div>
                        </div>

EOT;
        }

        $button = ucfirst(self::$viewType->value);

        $contents .= <<< EOT
                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary form-control">
                                    {{ __('{$button}') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
EOT;


        return $contents;
    }

    /**
     * @param $view string
     * @param string $contents
     * @param string $ui
     * @return void
     * @throws Exception
     */
    private static function show(string $view, string $contents, string $ui): void
    {
        // Make path
        if (empty($path = self::makePath($view))) {
            return;
        }

        // Get model dynamically
        $modelClass = 'App\\Models\\' . ucfirst(self::$config->getGenerate());
        $model = new $modelClass;

        // Make contents based on UI
        switch ($ui) {
            case 'bootstrap':
                $contents = self::showBootstrap($model, $contents);
                break;
            default:
                throw new Exception('Invalid UI');
        }


        // Write to file
        file_put_contents($path, $contents);
    }

    /**
     * @param $model
     * @param string $contents
     * @return string
     */
    private static function showBootstrap($model, string $contents): string
    {
        $contents .= "@section('content')\n";

        // Make form header
        $contents .= <<<EOT
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Show') }}</div>

                <div class="card-body">

EOT;

        // Get model attributes
        foreach ($model->getFillable() as $field) {
            if (in_array($field, $model->getHidden())) {
                continue;
            }
            $ucField = ucfirst($field);

            $contents .= <<<EOT
                        <div class="row mb-3">
                            <label for="{$field}" class="col-md-4 col-form-label text-md-end">{{ __('{$ucField}') }}</label>

                            <div class="col-md-6">
                                <span id="{$field}" class="form-control">{{ old('{$field}', \${$field}) }}</span>
                            </div>
                        </div>

EOT;
        }

        $button = ucfirst(self::$viewType->value);

        $contents .= <<< EOT
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
EOT;


        return $contents;
    }

    /**
     * @param $view string
     * @param string $contents
     * @param string $ui
     * @return void
     * @throws Exception
     */
    private static function delete(string $view, string $contents, string $ui): void
    {
        // Make path
        if (empty($path = self::makePath($view))) {
            return;
        }

        // Get model dynamically
        $modelClass = 'App\\Models\\' . ucfirst(self::$config->getGenerate());
        $model = new $modelClass;

        // Make contents based on UI
        switch ($ui) {
            case 'bootstrap':
                $contents = self::deleteBootstrap($model, $contents);
                break;
            default:
                throw new Exception('Invalid UI');
        }


        // Write to file
        file_put_contents($path, $contents);
    }

    /**
     * @param $model
     * @param string $contents
     * @return string
     */
    private static function deleteBootstrap($model, string $contents): string
    {
        $contents .= "@section('content')\n";

        // Make form header
        $route = self::getRoute();

        $contents .= <<<EOT
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Delete') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('{$route}') }}">
                    @csrf

EOT;

        // Get model attributes
        foreach ($model->getFillable() as $field) {
            if (in_array($field, $model->getHidden())) {
                continue;
            }
            $ucField = ucfirst($field);

            $contents .= <<<EOT
                        <div class="row mb-3">
                            <label for="{$field}" class="col-md-4 col-form-label text-md-end">{{ __('{$ucField}') }}</label>

                            <div class="col-md-6">
                                <span id="{$field}" class="form-control">{{ old('{$field}', \${$field}) }}</span>
                            </div>
                        </div>

EOT;
        }

        $button = ucfirst(self::$viewType->value);

        $contents .= <<< EOT
                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-danger form-control">
                                    {{ __('{$button}') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
EOT;


        return $contents;
    }
}
