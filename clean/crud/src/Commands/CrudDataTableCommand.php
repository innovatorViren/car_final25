<?php

namespace Onzup\Crud\Commands;

use Illuminate\Console\GeneratorCommand;

class CrudDataTableCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crud:datatable
                            {name : The name of the datatable.}
                            {--crud-name= : The name of the Crud.}
                            {--model-name= : The name of the Model.}
                            {--route-name= : The name of the Route.}
                            {--schema= : The name of the schema.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new DATA Table.';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'DataTable';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/../stubs/' . config('crudgenerator.default') . '/datatable.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string $rootNamespace
     *
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\DataTables';
    }

    public function getschema()
    {
        $schema = $this->option('schema');
        $fields = explode(',', $schema);

        $data = array();

        if ($schema) {
            $x = 0;
            foreach ($fields as $field) {
                $fieldArray = explode(':', $field);
                $data[$x]['name'] = trim($fieldArray[0]);
                $data[$x]['type'] = trim($fieldArray[1]);
                $data[$x]['display'] = (isset($fieldArray[2]) && (trim($fieldArray[2]) == 'hide') ? "hide" : "");
                (isset($fieldArray[3]) ? $data[$x]['size'] = trim($fieldArray[3]) : '');
                $x++;
            }
        }

        $schemaFields = '';
        foreach ($data as $item) {
            if (isset($item['display'])) {
                if ($item['display'] != "hide") {
                    $schemaFields .= "\t\t\t'" . $item['name'] . "',\n";
                }
            } else {
                $schemaFields .= "\t\t\t'" . $item['name'] . "',\n";
            }

        }

        return $schemaFields;
    }

    /**
     * Build the model class with the given name.
     *
     * @param  string  $name
     *
     * @return string
     */
    protected function buildClass($name)
    {
        $stub = $this->files->get($this->getStub());

        $crudName = strtolower($this->option('crud-name'));
        $crudNameSingular = str_singular($crudName);
        $modelName = $this->option('model-name');
        $routeName = $this->option('route-name');

        return $this->replaceNamespace($stub, $name)
            ->replaceCrudName($stub, $crudName)
            ->replaceCrudNameSingular($stub, $crudNameSingular)
            ->replaceModelName($stub, $modelName)
            ->replaceRouteName($stub, $routeName)
            ->replaceSchemaColumns($stub, $this->getschema())
            ->replaceClass($stub, $name);
    }

    /**
     * Replace the crudName for the given stub.
     *
     * @param  string  $stub
     * @param  string  $crudName
     *
     * @return $this
     */
    protected function replaceCrudName(&$stub, $crudName)
    {
        $stub = str_replace(
            '{{crudName}}', $crudName, $stub
        );

        return $this;
    }

    /**
     * Replace the crudNameSingular for the given stub.
     *
     * @param  string  $stub
     * @param  string  $crudNameSingular
     *
     * @return $this
     */
    protected function replaceCrudNameSingular(&$stub, $crudNameSingular)
    {
        $stub = str_replace(
            '{{crudNameSingular}}', $crudNameSingular, $stub
        );

        return $this;
    }

    /**
     * Replace the modelName for the given stub.
     *
     * @param  string  $stub
     * @param  string  $modelName
     *
     * @return $this
     */
    protected function replaceModelName(&$stub, $modelName)
    {
        $stub = str_replace(
            '{{modelName}}', $modelName, $stub
        );

        return $this;
    }

    protected function replaceRouteName(&$stub, $routeName)
    {
        $stub = str_replace(
            '{{routename}}', $routeName, $stub
        );

        return $this;
    }
    /**
     * Replace the routeGroup for the given stub.
     *
     * @param  string  $stub
     * @param  string  $routeGroup
     *
     * @return $this
     */
    protected function replaceRouteGroup(&$stub, $routeGroup)
    {
        $stub = str_replace(
            '{{routeGroup}}', $routeGroup, $stub
        );

        return $this;
    }

    protected function replaceSchemaColumns(&$stub, $schemacolumns)
    {
        $stub = str_replace(
            '{{schemacolumns}}', $schemacolumns, $stub
        );

        return $this;
    }

}
