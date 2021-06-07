<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\UserRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

class UserCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        CRUD::setModel(\App\Models\User::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/user');
        CRUD::setEntityNameStrings('user', 'users');
    }

    protected function setupShowOperation() {
        CRUD::column('id');
        CRUD::column('login');
        CRUD::column('name');
        CRUD::column('email');
        CRUD::column('role');
        CRUD::column('rating');
        CRUD::column('password');
        CRUD::addColumn([
            'name' => 'avatar',
            'label' => 'Avatar',
            'type' => 'image',
            'width' => '150px',
            'height' => '150px'
        ]);
    }

    protected function setupListOperation()
    {
        CRUD::column('id');
        CRUD::column('login');
        CRUD::column('name');
        CRUD::column('email');
        CRUD::column('role');
        CRUD::column('rating');
    }

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
}
