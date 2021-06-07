<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PostRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

use \App\Models\User;

/**
 * Class PostCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class PostCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        CRUD::setModel(\App\Models\Post::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/post');
        CRUD::setEntityNameStrings('post', 'posts');
    }

    public function setupShowOperation()
    {
        CRUD::addColumn('id');
        CRUD::setFromDb();
        CRUD::addColumn('locked');
        CRUD::modifyColumn('content', [
            'type' => 'markdown'
        ]);
    }

    protected function setupListOperation()
    {
        CRUD::addColumn('id');
        CRUD::setFromDb();
        CRUD::removeColumn('content');
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(PostRequest::class);
        CRUD::setFromDb();
        CRUD::modifyField('status', [
            'type' => 'enum',
        ]);
        CRUD::removeField('rating');
        CRUD::removeField('author');
    }

    protected function setupUpdateOperation()
    {
        CRUD::addField([
            'name' => 'status',
            'label' => 'Status',
            'type' => 'enum'
        ]);
        CRUD::addField('locked');
        CRUD::addField('categories'); // 
    }
}
