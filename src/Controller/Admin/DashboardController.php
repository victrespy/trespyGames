<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use App\Controller\Admin\UserCrudController;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function index(): Response
    {
        // Al entrar en /admin, redirigimos directamente a la gestión de usuarios
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(UserCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            // Título con HTML para usar tus colores
            ->setTitle('<span style="color: #670A15; font-weight: 900; text-transform: uppercase;">trespy</span>Games')
            ->setFaviconPath('favicon.svg')
            // No permite que los usuarios cambien a modo claro/oscuro para no romper tu estética
            ->disableDarkMode();
    }

    public function configureAssets(): Assets
    {
        return Assets::new()
            // Usamos addHtmlContentToHead en lugar del anterior
            ->addHtmlContentToHead('<style>
                :root {
                    --accent-color: #670A15;
                    --body-bg: #F8EEDF;
                    --sidebar-bg: #000000;
                    --text-color: #000000;
                }
                body { background-color: var(--body-bg) !important; color: var(--text-color) !important; }
                .main-sidebar { background-color: var(--sidebar-bg) !important; }
                .main-header { background-color: var(--sidebar-bg) !important; border-bottom: 2px solid var(--accent-color) !important; }
                .sidebar-menu .menu-item.active > .menu-link { color: var(--accent-color) !important; }
                .content-header-title { font-weight: 900; text-transform: uppercase; }
                .btn-primary { background-color: var(--accent-color) !important; border-color: var(--accent-color) !important; border-radius: 0 !important; font-weight: bold; }
                .card { border: 2px solid var(--accent-color) !important; border-radius: 0 !important; background-color: rgba(255,255,255,0.5) !important; }
            </style>');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToRoute('Volver a la Web', 'fas fa-arrow-left', 'app_home');

        yield MenuItem::section('Gestión General');

        // En lugar de linkToCrud, usamos linkToUrl o linkToRoute
        // Esto apunta directamente al índice del CRUD de Usuarios
        yield MenuItem::linkToUrl('Usuarios', 'fas fa-users', $this->container->get(AdminUrlGenerator::class)
            ->setController(UserCrudController::class)
            ->generateUrl()
        );
    }
}
