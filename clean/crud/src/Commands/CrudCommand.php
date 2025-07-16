<?php

namespace Onzup\Crud\Commands;

use File;
use Illuminate\Console\Command;

class CrudCommand extends Command {

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crud:generate
                            {name : The name of the Crud.}
                            {--fields= : Fields name for the form & model.}
                            {--route=yes : Include Crud route to routes.php? yes|no.}
                            {--pk=id : The name of the primary key.}
                            {--view-path= : The name of the view path.}
                            {--namespace= : Namespace of the controller.}
                            {--route-group= : Prefix of the route group.}
                            {--stubtype= :  Type of stub }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Crud including controller, model, views & migrations.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {

        $name = $this->argument('name');
        $modelName = str_singular($name);
        $migrationName = str_plural(strtolower($name));
        $tableName = $migrationName;
        $viewName = strtolower($name);
        $DataTableName = $name;

        $stubType = $this->option('stubtype');

        $routeGroup = $this->option('route-group');
        $routeName = ($routeGroup) ? $routeGroup . '/' . strtolower($name) : strtolower($name);

        $controllerNamespace = ($this->option('namespace')) ? $this->option('namespace') . '\\' : '';

        $fields = $this->option('fields');
        $primaryKey = $this->option('pk');
        $viewPath = $this->option('view-path');

        $fieldsArray = explode(',', $fields);
        $requiredFields = '';
        $requiredFieldsStr = '';

        foreach ($fieldsArray as $item) {
            $fillableArray[] = preg_replace("/(.*?):(.*)/", "$1", trim($item));

            $itemArray = explode(':', $item);
            $currentField = trim($itemArray[0]);
            $requiredFieldsStr .= (isset($itemArray[2]) && (trim($itemArray[2]) == 'req' || trim($itemArray[2]) == 'required')) ? "'$currentField' => 'required', " : '';
        }
        $fillableArray[] = 'created_by';
        $fillableArray[] = 'updated_by';
        $commaSeparetedString = implode("', '", $fillableArray);
        $fillable = "['" . $commaSeparetedString . "']";

        $requiredFields = ($requiredFieldsStr != '') ? "[" . $requiredFieldsStr . "]" : '';
        config(['crudgenerator.default' => $stubType]);

        $this->call('crud:controller', ['name' => $controllerNamespace . $name . 'Controller', '--crud-name' => $name, '--model-name' => $modelName, '--view-path' => $viewPath, '--required-fields' => $requiredFields, '--route-group' => $routeGroup, '--datatable-name' => $DataTableName . 'Datatable', '--stubtype' => $stubType, '--fields' => $fields]);

        $this->call('crud:model', ['name' => "Models\\" . $modelName, '--fillable' => $fillable, '--table' => $tableName, '--stubtype' => $stubType]);

        $this->call('crud:migration', ['name' => $migrationName, '--schema' => $fields, '--pk' => $primaryKey, '--stubtype' => $stubType]);

        $this->call('crud:view', ['name' => $viewName, '--fields' => $fields, '--view-path' => $viewPath, '--route-group' => $routeGroup, '--stubtype' => $stubType]);
        if ($stubType == 'laravel') {
            $this->call('crud:datatable', ['name' => $DataTableName . 'Datatable', '--crud-name' => $name, '--model-name' => $modelName, '--schema' => $fields, '--route-name' => $routeName]);
        }

        // Updating the Http/routes.php file
        $routeFile = app_path('Http/routes.php');
        if (\App::VERSION() >= '5.2') {
            $routeFile = base_path('routes/web.php');
        }
        if (file_exists($routeFile) && (strtolower($this->option('route')) === 'yes')) {
            $controller = ($controllerNamespace != '') ? $controllerNamespace . '\\' . $name . 'Controller' : $name . 'Controller';

            if (\App::VERSION() >= '5.2') {
                $isAdded = File::append($routeFile, "\nRoute::group(['middleware' => ['web']], function () {"
                                . "\n\tRoute::resource('" . $routeName . "', '" . $controller . "');"
                                . "\n});"
                );
            } else {
                $isAdded = File::append($routeFile, "\nRoute::resource('" . $routeName . "', '" . $controller . "');");
            }

            if ($isAdded) {
                $this->info('Crud/Resource route added to ' . $routeFile);
            } else {
                $this->info('Unable to add the route to ' . $routeFile);
            }
        }
        $permissionFile = base_path('onzup/services/Permission.php');
        $permission_contain = File::get($permissionFile);
        $permission_txt = "
        '{$routeName}' => [
            '{$routeName}.create',
            '{$routeName}.view',
            '{$routeName}.update',
            '{$routeName}.delete',
        ],
            /* don't remove this */";
        File::put($permissionFile, str_replace("/* don't remove this */", $permission_txt, $permission_contain));

        $menuFile = base_path('resources/themes/limitless/partials/header.blade.php');
        $menu_contain = File::get($menuFile);
        $menu_txt = '
                    @if(Sentinel::getUser()->hasAnyAccess(["'.$routeName.'.view","users.superadmin"]))
                        <li class="dropdown">
                            <a href="{{ route("'.$routeName.'.index") }}">
                                <i class="fa fa-bar-chart position-left"></i> '.$modelName.'
                            </a>
                        </li>
                    @endif
                    {{--auto menu here--}}';
        File::put($menuFile, str_replace("{{--auto menu here--}}", $menu_txt, $menu_contain));
    }

}
