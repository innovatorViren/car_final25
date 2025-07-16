<?php

namespace Onzup\Crud\Commands;

use File;
use Illuminate\Console\Command;

class CrudViewCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crud:view
                            {name : The name of the Crud.}
                            {--fields= : The fields name for the form.}
                            {--view-path= : The name of the view path.}
                            {--route-group= : Prefix of the route group.}
                            {--stubtype= : Type of stub.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create views for the Crud.';

    /**
     * View Directory Path.
     *
     * @var string
     */
    protected $viewDirectoryPath;

    /**
     *  Form field types collection.
     *
     * @var array
     */
    protected $typeLookup = [
        'string' => 'text',
        'char' => 'text',
        'varchar' => 'text',
        'text' => 'textarea',
        'mediumtext' => 'textarea',
        'longtext' => 'textarea',
        'json' => 'textarea',
        'jsonb' => 'textarea',
        'binary' => 'textarea',
        'password' => 'password',
        'email' => 'email',
        'number' => 'number',
        'integer' => 'number',
        'bigint' => 'number',
        'mediumint' => 'number',
        'tinyint' => 'number',
        'smallint' => 'number',
        'decimal' => 'number',
        'double' => 'number',
        'float' => 'number',
        'date' => 'date',
        'datetime' => 'datetime-local',
        'dateTime' => 'datetime-local',
        'time' => 'time',
        'boolean' => 'radio',
    ];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if($this->option('stubtype'))
        {
            config(['crudgenerator.default'=>$this->option('stubtype')]);
        }
        $this->viewDirectoryPath = __DIR__ . '/../stubs/' . config('crudgenerator.default').'/';
        $crudName = $this->argument('name');
        $crudNameCap = ucwords($crudName);
        $crudNameSingular = str_singular($crudName);
        $modelName = ucwords($crudNameSingular);
        $routeGroup = ($this->option('route-group')) ? $this->option('route-group') . '/' : $this->option('route-group');

        $viewDirectory = config('view.paths')[0] . '/';
        if ($this->option('view-path')) {
            $userPath = $this->option('view-path');
            $path = $viewDirectory . $userPath . '/' . $crudName . '/';
        } else {
            $path = $viewDirectory . $crudName . '/';
        }

        if (!File::isDirectory($path)) {
            File::makeDirectory($path, 0755, true);
        }

        $fields = $this->option('fields');
        $fieldsArray = explode(',', $fields);
        $formFields = array();

        $columnArray = [];

        if ($fields) {
            $x = 0;
            foreach ($fieldsArray as $item) {
                $itemArray = explode(':', $item);
                $formFields[$x]['name'] = trim($itemArray[0]);
                $columnArray[$x] = trim($itemArray[0]);
                //split size
                $temp = explode('(',trim($itemArray[1]));
                $formFields[$x]['type'] = trim($temp[0]);
                // $formFields[$x]['type'] = trim($itemArray[1]);
                $formFields[$x]['required'] = (isset($itemArray[2]) && (trim($itemArray[2]) == 'req' || trim($itemArray[2]) == 'required')) ? true : false;
                $formFields[$x]['hide'] = (isset($itemArray[2]) && (trim($itemArray[2]) == 'hide')) ? true : false;
                //relates logic
                if(isset($itemArray[3]))
                {
                    $relates_array = explode('(',$itemArray[3]);
                    $formFields[$x]['relates'] = (isset($relates_array[0]) && (trim($relates_array[0]) == 'relates')) ? true : false;
                    $formFields[$x]['relates_model'] = isset($relates_array[1]) ? str_replace(')','',trim($relates_array[1])) : '';
                }

                $x++;
            }
        }
        $formFieldsHtml = '';
        foreach ($formFields as $fky=>$item) {
            if(isset($item['relates']) && $item['relates'] == true)
            {
                // dump($item); // select html
            }
            else if ($item['hide'] == false) {
                $formFieldsHtml .= $this->createField($item,$fky);
            }
        }

        // Form fields and label
        $formHeadingHtml = '';
        $formBodyHtml = '';
        $formBodyHtmlForShowView = '';

        $i = 0;
        foreach ($formFields as $key => $value) {
            if ($i == 3) {
                break;
            }

            $field = $value['name'];
            $label = ucwords(str_replace('_', ' ', $field));
            $formHeadingHtml .= '<th>' . $label . '</th>';

            if ($i == 0) {
                $formBodyHtml .= '<td><a href="{{ url(\'%%routeGroup%%%%crudName%%\', $item->id) }}">{{ $item->' . $field . ' }}</a></td>';
            } else {
                $formBodyHtml .= '<td>{{ $item->' . $field . ' }}</td>';
            }

            if($this->option('stubtype') == 'laravel')
                $formBodyHtmlForShowView .= '<td> {{ $%%crudNameSingular%%->' . $field . ' }} </td>';
            else if($this->option('stubtype') == 'vue')
                $formBodyHtmlForShowView .= '<td> @{{ ' . $field . ' }} </td>';

            $i++;
        }

        // For index.blade.php file
        $indexFile = $this->viewDirectoryPath . 'index.blade.stub';
        $newIndexFile = $path . 'index.blade.php';
        if (!File::copy($indexFile, $newIndexFile)) {
            echo "failed to copy $indexFile...\n";
        } else {
            File::put($newIndexFile, str_replace('%%formHeadingHtml%%', $formHeadingHtml, File::get($newIndexFile)));
            File::put($newIndexFile, str_replace('%%formBodyHtml%%', $formBodyHtml, File::get($newIndexFile)));
            File::put($newIndexFile, str_replace('%%crudName%%', $crudName, File::get($newIndexFile)));
            File::put($newIndexFile, str_replace('%%crudNameCap%%', $crudNameCap, File::get($newIndexFile)));
            File::put($newIndexFile, str_replace('%%modelName%%', $modelName, File::get($newIndexFile)));
            File::put($newIndexFile, str_replace('%%routeGroup%%', $routeGroup, File::get($newIndexFile)));
        }

        if($this->option('stubtype') == 'vue'){
            File::put($newIndexFile, str_replace('%%include%%', $crudName, File::get($newIndexFile)));
        }

        //For form.blade.php file
        $formFile = $this->viewDirectoryPath . 'form.blade.stub';
        $newFormFile = $path . 'form.blade.php';
        if (!File::copy($formFile, $newFormFile)) {
            echo "failed to copy $formFile...\n";
        } else {
            File::put($newFormFile, str_replace('%%formFieldsHtml%%', $formFieldsHtml, File::get($newFormFile)));
            File::put($newFormFile, str_replace('%%crudNameSingular%%', $crudNameSingular, File::get($newFormFile)));
        }

        //Include form path
        if($this->option('view-path'))
        {
            $include = $this->option('view-path').'.'.$crudName.'.form';
        }
        else
        {
            $include = $crudName.'.form';
        }


        // For create.blade.php file
        $createFile = $this->viewDirectoryPath . 'create.blade.stub';
        $newCreateFile = $path . 'create.blade.php';
        if (!File::copy($createFile, $newCreateFile)) {
            echo "failed to copy $createFile...\n";
        } else {
            File::put($newCreateFile, str_replace('%%crudName%%', $crudName, File::get($newCreateFile)));
            File::put($newCreateFile, str_replace('%%modelName%%', $modelName, File::get($newCreateFile)));
            File::put($newCreateFile, str_replace('%%routeGroup%%', $routeGroup, File::get($newCreateFile)));
            File::put($newCreateFile, str_replace('%%include%%', $include, File::get($newCreateFile)));
            File::put($newCreateFile, str_replace('%%crudNameSingular%%', $crudNameSingular, File::get($newCreateFile)));
        }

        // For edit.blade.php file
        $editFile = $this->viewDirectoryPath . 'edit.blade.stub';
        $newEditFile = $path . 'edit.blade.php';
        if (!File::copy($editFile, $newEditFile)) {
            echo "failed to copy $editFile...\n";
        } else {
            File::put($newEditFile, str_replace('%%crudName%%', $crudName, File::get($newEditFile)));
            File::put($newEditFile, str_replace('%%crudNameSingular%%', $crudNameSingular, File::get($newEditFile)));
            File::put($newEditFile, str_replace('%%modelName%%', $modelName, File::get($newEditFile)));
            File::put($newEditFile, str_replace('%%routeGroup%%', $routeGroup, File::get($newEditFile)));

            if($this->option('stubtype') == 'vue')
            {
                $set_edit_data = $this->setVariables($columnArray);
                File::put($newEditFile, str_replace('%%set_edit_data%%', $set_edit_data, File::get($newEditFile)));
            } else {
                File::put($newEditFile, str_replace('%%include%%', $include, File::get($newEditFile)));
            }

        }

        // For show.blade.php file
        $showFile = $this->viewDirectoryPath . 'show.blade.stub';
        $newShowFile = $path . 'show.blade.php';
        if (!File::copy($showFile, $newShowFile)) {
            echo "failed to copy $showFile...\n";
        } else {
            File::put($newShowFile, str_replace('%%formHeadingHtml%%', $formHeadingHtml, File::get($newShowFile)));
            File::put($newShowFile, str_replace('%%modelName%%', $modelName, File::get($newShowFile)));
            File::put($newShowFile, str_replace('%%formBodyHtml%%', $formBodyHtmlForShowView, File::get($newShowFile)));
            if($this->option('stubtype') == 'laravel'){
                File::put($newShowFile, str_replace('%%crudNameSingular%%', $crudNameSingular, File::get($newShowFile)));
            }
            if($this->option('stubtype') == 'vue'){
                File::put($newShowFile, str_replace('%%routeGroup%%', $routeGroup, File::get($newShowFile)));
                File::put($newShowFile, str_replace('%%crudName%%', $crudName, File::get($newShowFile)));
                File::put($newShowFile, str_replace('%%set_data%%', $this->setVariables($columnArray), File::get($newShowFile)));
            }
        }

        if($this->option('stubtype') == 'vue'){
            //For mixin.blade.php
            $Mixin = $this->viewDirectoryPath . 'mixin.blade.stub';
            $newMixin = $path . 'mixin.blade.php';
            if (!File::copy($Mixin, $newMixin)) {
                echo "failed to copy $Mixin...\n";
            } else {
                $variables = $this->getJSVariables($columnArray);
                $variables_init = $this->getJSVariablesInit($columnArray);
                File::put($newMixin, str_replace('%%variables%%', $variables, File::get($newMixin)));
                File::put($newMixin, str_replace('%%variables_init%%', $variables_init, File::get($newMixin)));
            }

            //For listing.blade.php
            $Listing = $this->viewDirectoryPath . 'listing.blade.stub';
            $newListing = $path . 'listing.blade.php';
            if (!File::copy($Listing, $newListing)) {
                echo "failed to copy $Listing...\n";
            } else {
                File::put($newListing, str_replace('%%modelName%%', $modelName, File::get($newListing)));
                File::put($newListing, str_replace('%%routeGroup%%', $routeGroup, File::get($newListing)));
                File::put($newListing, str_replace('%%crudName%%', $crudName, File::get($newListing)));
                $listing = $this->getListingFields($columnArray);
                File::put($newListing, str_replace('%%tableColumns%%', $listing->tableColumns, File::get($newListing)));
                File::put($newListing, str_replace('%%searchColumns%%', $listing->search, File::get($newListing)));
                File::put($newListing, str_replace('%%multipleFilter%%', $listing->multipleFilter, File::get($newListing)));
                File::put($newListing, str_replace('%%MultiSearchFields%%', $listing->MultiSearchFields, File::get($newListing)));

            }

            //For routes.blade.php
            $Route = $this->viewDirectoryPath . 'routes.blade.stub';
            $newRoute = $path . 'routes.blade.php';
            if (!File::copy($Route, $newRoute)) {
                echo "failed to copy $Route...\n";
            }
        }



        // For layouts/master.blade.php file
        // $layoutsDirPath = base_path('resources/views/layouts/');
        // if (!File::isDirectory($layoutsDirPath)) {
        //     File::makeDirectory($layoutsDirPath);
        // }

        // $layoutsFile = $this->viewDirectoryPath . 'master.blade.stub';
        // $newLayoutsFile = $layoutsDirPath . 'master.blade.php';

        // if (!File::exists($newLayoutsFile)) {
        //     if (!File::copy($layoutsFile, $newLayoutsFile)) {
        //         echo "failed to copy $layoutsFile...\n";
        //     }
        // }

        $this->info('View created successfully.');
    }

    /**
     * Form field wrapper.
     *
     * @param  string  $item
     * @param  string  $field
     *
     * @return void
     */
    protected function wrapField($item, $field)
    {
        $required = ($item['required'] === true) ? '<span class="has-stik">*</span>' : "";
        if($this->option('stubtype') == 'vue')
        {
            $formGroup =
            <<<EOD
            <div :class="{'form-group':true,'has-error':errors.%1\$s}">
                {!! Form::label('%1\$s', '%2\$s: ', ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    %3\$s
                    <p class="help-block text-danger" v-if="errors.%1\$s">@{{ errors.%1\$s}}</p>
                </div>
            </div>\n
EOD;
        }
        else
        {
        $formGroup =
            <<<EOD
          <div class="col-lg-12">
            <div class="form-group {{ \$errors->has('%1\$s') ? 'has-error' : ''}}">
                {!! Html::decode(Form::label('%1\$s', '%2\$s:$required ', ['class' => 'col-sm-3 control-label'])) !!}
                <div class="col-sm-6">
                    %3\$s
                    {!! \$errors->first('%1\$s', '<p class="text-danger">:message</p>') !!}
                </div>
            </div>
          </div>\n
EOD;
        }

        return sprintf($formGroup, $item['name'], ucwords(strtolower(str_replace('_', ' ', $item['name']))), $field);
    }

    /**
     * Form field generator.
     *
     * @param  string  $item
     *
     * @return string
     */
    protected function createField($item,$key=null)
    {
        switch ($this->typeLookup[$item['type']]) {
            case 'password':
                return $this->createPasswordField($item);
                break;
            case 'datetime-local':
            case 'time':
                return $this->createInputField($item);
                break;
            case 'radio':
                return $this->createRadioField($item);
                break;
            default: // text
                return $this->createFormField($item,$key);
        }
    }

    /**
     * Create a specific field using the form helper.
     *
     * @param  string  $item
     *
     * @return string
     */
    protected function createFormField($item,$key=null)
    {
        $required = ($item['required'] === true) ? "" : "";

        $customclass = '';
        $model='';
        if($this->option('stubtype') == 'vue')
        {
            $model = ',"v-model" => "'.$item["name"].'"';
        }
        if(in_array($this->typeLookup[$item['type']], ['number'])){
            $this->typeLookup[$item['type']] = "text";
        }
        if ($this->typeLookup[$item['type']] == "date" || $this->typeLookup[$item['type']] == "datetime" || $this->typeLookup[$item['type']] == "dateTime") {
            $this->typeLookup[$item['type']] = "text";
            $customclass = "datepicker";
        }
        if(!is_null($key) && $key=='0'){
            $required.=",'autofocus'=>'autofocus'";
        }
        if($this->typeLookup[$item['type']] == "textarea"){
            $required.=",'rows' => '3'";
        }
        return $this->wrapField(
            $item,
            "{!! Form::" . $this->typeLookup[$item['type']] . "('" . $item['name'] . "', null, ['class' => 'form-control $customclass' $required $model]) !!}"
        );
    }

    /**
     * Create a password field using the form helper.
     *
     * @param  string  $item
     *
     * @return string
     */
    protected function createPasswordField($item)
    {
        $required = ($item['required'] === true) ? "" : "";
        $model='';
        if($this->option('stubtype') == 'vue')
        {
            $model = ',"v-model" => "'.$item["name"].'"';
        }

        return $this->wrapField(
            $item,
            "{!! Form::password('" . $item['name'] . "', ['class' => 'form-control'$required $model]) !!}"
        );
    }

    /**
     * Create a generic input field using the form helper.
     *
     * @param  string  $item
     *
     * @return string
     */
    protected function createInputField($item)
    {
        $required = ($item['required'] === true) ? "" : "";
        $model='';
        if($this->option('stubtype') == 'vue')
        {
            $model = ',"v-model" => "'.$item["name"].'"';
        }

        return $this->wrapField(
            $item,
            "{!! Form::input('" . $this->typeLookup[$item['type']] . "', '" . $item['name'] . "', null, ['class' => 'form-control'$required $model]) !!}"
        );
    }

    /**
     * Create a yes/no radio button group using the form helper.
     *
     * @param  string  $item
     *
     * @return string
     */
    protected function createRadioField($item)
    {
        $field =
            <<<EOD
            <div class="checkbox">
                <label>{!! Form::radio('%1\$s', '1') !!} Yes</label>
            </div>
            <div class="checkbox">
                <label>{!! Form::radio('%1\$s', '0', true) !!} No</label>
            </div>
EOD;

        return $this->wrapField($item, sprintf($field, $item['name']));
    }

    public function getJSVariables($var)
    {
        $js='';
        foreach ($var as $key => $value) {
            $js .= "$value:'',";
        }
        return $js;
    }

    public function getJSVariablesInit($var)
    {
        $js='';
        foreach ($var as $key => $value) {
            $js .= "$value:this.$value,";
        }
        return $js;
    }

    public function setVariables($var)
    {
        $js='';
        foreach ($var as $key => $value) {
            $js .= "this.$value = data.$value;";
        }
        return $js;
    }

    public function getListingFields($arr)
    {
        $listing =new \stdClass;
        $listing->tableColumns='';
        $listing->search='';
        $listing->multipleFilter='';
        $listing->MultiSearchFields='';

        foreach ($arr as $key => $value) {
            $listing->tableColumns .= "{ name: '$value',sortField: '$value' },";
            $listing->search .= "$value:{ op:'like',value:'',column:'$value' },";
            $listing->MultiSearchFields.="<div class='col-sm-2'>
                <input v-model='search.$value.value' class='form-control'  placeholder='$value'>
                </div>\n";
            if($key > 0)
            {
                $listing->multipleFilter .= "+'&filters['+this.search.$value.column+'][value]='+this.search.$value.value+'&filters['+this.search.$value.column+'][type]='+this.search.$value.op";
            }
            else
            {
                $listing->multipleFilter .= "'filters['+this.search.$value.column+'][value]='+this.search.$value.value+'&filters['+this.search.$value.column+'][type]='+this.search.$value.op";
            }

        }
        return $listing;
    }

}
