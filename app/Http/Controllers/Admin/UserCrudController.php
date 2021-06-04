<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\UserRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class UserCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class UserCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\User::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/user');
        CRUD::setEntityNameStrings('user', 'users');
    }

    protected function setupShowOperation() {
        CRUD::column('login');
        CRUD::column('name');
        CRUD::column('password');
        CRUD::column([
            'name' => 'avatar',
            'label' => 'Avatar',
            'type' => 'image',
            'width' => '150px',
            'height' => '150px'
        ]);
        CRUD::column('email');
        CRUD::column('role');
    }


    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('id');
        CRUD::column('login');
        CRUD::column('name');
        CRUD::column('email');
        CRUD::column('role');
        CRUD::column('rating');
        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']);
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(UserRequest::class);

        CRUD::field('login');
        CRUD::field('password');



        CRUD::addField([
            'label' => 'Password confirmation',
            'type' => 'password',
            'name' => 'password_confirm'
        ]);

        CRUD::addField([
            'name' => 'avatar',
            'label' => 'Avatar',
            'type' => 'image',
            'crop' => true,
            'aspect_ratio' => 1,
        ]);

        CRUD::addField([
            'name' => 'email',
            'label' => 'Email Address',
            'type' => 'email'
        ]);

        CRUD::addField([
            'name' => 'role',
            'label' => 'Role',
            'type' => 'enum'
        ]);

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number']));
         */
    }

    protected function setupUpdateOperation()
    {
        CRUD::field('login');
        CRUD::field('name');
        CRUD::addField([
            'name' => 'email',
            'label' => 'Email Address',
            'type' => 'email'
        ]);
        CRUD::addField([
            'name' => 'role',
            'label' => 'Role',
            'type' => 'enum'
        ]);
        CRUD::addField([
            'name' => 'avatar',
            'label' => 'Avatar',
            'type' => 'image',
            'crop' => true,
            'aspect_ratio' => 1,
        ]);
    }



//     /**
//      * Define what happens when the Update operation is loaded.
//      *
//      * @see https://backpackforlaravel.com/docs/crud-operation-update
//      * @return void
//      */
//     protected function setupUpdateOperation()
//     {
//         $this->setupCreateOperation();
//     }
}
