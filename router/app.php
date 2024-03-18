<?php

use Megio\Helper\Router;
use Megio\Http\Controller\AppController;
use Megio\Http\Request\Auth as Auth;
use Megio\Http\Request\Collection as Collection;
use Megio\Http\Request\Admin as Admin;
use Megio\Http\Request\Resource as Resource;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return static function (RoutingConfigurator $routes): void {
    // App
    $routes->add(Router::ROUTE_APP, '/app{uri}')
        ->methods(['GET'])
        ->controller([AppController::class, 'app'])
        ->requirements(['uri' => '.*'])
        ->options(['auth' => false]);
    
    // Api overview
    $routes->add(Router::ROUTE_API, '/api')
        ->methods(['GET'])
        ->controller([AppController::class, 'api'])
        ->options(['auth' => false]);
    
    // Auth
    $auth = $routes->collection('megio.auth.')->prefix('/megio/auth');
    
    $auth->add('email', '/email')
        ->methods(['POST'])
        ->controller(Auth\EmailAuthRequest::class)
        ->options(['auth' => false]);
    
    $auth->add('revoke-token', '/revoke-token')
        ->methods(['POST'])
        ->controller(Auth\RevokeTokenRequest::class)
        ->options(['inResources' => false]);
    
    // Admin
    $admin = $routes->collection('megio.admin.')->prefix('/megio/admin');
    
    $admin->add('profile', '/profile')
        ->methods(['POST'])
        ->controller(Admin\ProfileRequest::class)
        ->options(['inResources' => false]);
    
    $admin->add('avatar', '/avatar')
        ->methods(['POST'])
        ->controller(Admin\UploadAvatarRequest::class)
        ->options(['inResources' => false]);
    
    // Collections navbar
    $routes->add(Router::ROUTE_META_NAVBAR, '/megio/collections/navbar')
        ->methods(['POST'])
        ->controller(Collection\NavbarRequest::class);
    
    // Collections
    $collection = $routes->collection(Router::ROUTE_COLLECTION_PREFIX)->prefix('/megio/collections');
    $collection->add('show', '/show')->methods(['POST'])->controller(Collection\ShowRequest::class);
    $collection->add('show-one', '/show-one')->methods(['POST'])->controller(Collection\ShowOneRequest::class);
    $collection->add('create', '/create')->methods(['POST'])->controller(Collection\CreateRequest::class);
    $collection->add('delete', '/delete')->methods(['DELETE'])->controller(Collection\DeleteRequest::class);
    $collection->add('update', '/update')->methods(['PATCH'])->controller(Collection\UpdateRequest::class);
    
    // Forms
    $form = $routes->collection('megio.form.')->prefix('/megio/form');
    $form->add('collection.create', '/collection/create')
        ->methods(['POST'])
        ->controller(Collection\Form\AddFormRequest::class);
    
    $form->add('collection.edit', '/collection/edit')
        ->methods(['PATCH'])
        ->controller(Collection\Form\EditFormRequest::class);
    
    // Resources
    $resources = $routes->collection('megio.resources.')->prefix('/megio/resources');
    
    $resources->add('show', '/show')
        ->methods(['POST'])
        ->controller(Resource\ShowAllRequest::class)
        ->options(['inResources' => false]);
    
    $resources->add('update', '/update')
        ->methods(['POST'])
        ->controller(Resource\UpdateResourceRequest::class)
        ->options(['inResources' => false]);
    
    $resources->add('update.role', '/update-role')
        ->methods(['POST'])
        ->controller(Resource\UpdateRoleRequest::class)
        ->options(['inResources' => false]);
    
    $resources->add('create.role', '/create-role')
        ->methods(['POST'])
        ->controller(Resource\CreateRoleRequest::class)
        ->options(['inResources' => false]);
    
    $resources->add('delete.role', '/delete-role')
        ->methods(['DELETE'])
        ->controller(Resource\DeleteRoleRequest::class)
        ->options(['inResources' => false]);
};