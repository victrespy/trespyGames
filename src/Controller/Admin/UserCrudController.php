<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('username', 'Apodo'),

            // Configuración de Roles
            ChoiceField::new('roles', 'Rango / Permisos')
                ->setChoices([
                    'Jugador Estándar' => 'ROLE_USER',
                    'Administrador' => 'ROLE_ADMIN',
                ])
                ->allowMultipleChoices() // Importante: los roles son un array
                ->renderAsBadges()       // Los muestra como etiquetas de colores
                ->setRequired(true),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Jugador')
            ->setEntityLabelInPlural('Jugadores')
            ->setSearchFields(['username']);
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            // Esto asegura que en las páginas de EDITAR y CREAR aparezca el botón de volver
            ->add(Crud::PAGE_EDIT, Action::INDEX)
            ->add(Crud::PAGE_NEW, Action::INDEX)

            // Opcional: Cambiarle el nombre a "Cancelar" para que sea más claro
            ->update(Crud::PAGE_EDIT, Action::INDEX, function (Action $action) {
                return $action->setLabel('Cancelar y Volver')->setIcon('fa fa-times');
            })
            ->update(Crud::PAGE_NEW, Action::INDEX, function (Action $action) {
                return $action->setLabel('Cancelar')->setIcon('fa fa-ban');
            });
    }
}
