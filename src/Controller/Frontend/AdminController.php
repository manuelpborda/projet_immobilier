<?php

namespace App\Controller\Frontend;

use App\Repository\UserRepository;
use App\Repository\BienRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * Ruta: /admin/dashboard
     * Panel de resumen global: fusionamos estadísticas de usuarios e inmuebles en una sola vista.
     */
    #[Route('/admin/dashboard', name: 'admin_dashboard')]
    public function dashboard(UserRepository $userRepo, BienRepository $bienRepo): Response
    {
        // --- Conteo de usuarios por tipo de rol ---
        $clientes = $userRepo->countByRole('ROLE_CLIENT');
        $propietarios = $userRepo->countByRole('ROLE_PROPRIETAIRE');
        $administradores = $userRepo->countByRole('ROLE_ADMIN');

        // --- Total de inmuebles publicados ---
        $totalInmuebles = $bienRepo->count([]);

        // --- Estadísticas para las tablas del Dashboard ---
        // Utilizamos los métodos que ya tenías creados en tu repositorio
        $estadisticasTipo = $bienRepo->countByType();
        $estadisticasCiudad = $bienRepo->countByCity();

        // Pasamos todos los datos centralizados a la nueva vista del dashboard
        return $this->render('admin/dashboard.html.twig', [
            'totalInmuebles'     => $totalInmuebles,
            'clientes'           => $clientes,
            'propietarios'       => $propietarios,
            'administradores'    => $administradores,
            'estadisticasTipo'   => $estadisticasTipo,
            'estadisticasCiudad' => $estadisticasCiudad,
        ]);
    }

    /**
     * Ruta: /admin/usuarios
     * Página para visualizar todos los usuarios registrados (accesible desde el menú superior).
     */
    #[Route('/admin/usuarios', name: 'admin_usuarios')]
    public function usuarios(UserRepository $userRepo): Response
    {
        $usuarios = $userRepo->findAll(); // Todos los usuarios sin filtro

        return $this->render('admin/usuarios.html.twig', [
            'usuarios' => $usuarios,
        ]);
    }
}