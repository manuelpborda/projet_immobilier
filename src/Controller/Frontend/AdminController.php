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
     * Panel de resumen para administradores: muestra conteo de usuarios por rol e inmuebles.
     */
    #[Route('/admin/dashboard', name: 'admin_dashboard')]
    public function dashboard(UserRepository $userRepo, BienRepository $bienRepo): Response
    {
        // --- Total de usuarios registrados en el sistema ---
        $totalUsuarios = $userRepo->count([]); // Conteo total sin filtros

        // --- Conteo de usuarios por tipo de rol ---
        // IMPORTANTE: ahora usamos el método countByRole() que funciona sin JSON_CONTAINS.
        $clientes = $userRepo->countByRole('ROLE_CLIENT');           // Clientes
        $propietarios = $userRepo->countByRole('ROLE_PROPRIETAIRE'); // Propietarios
        $administradores = $userRepo->countByRole('ROLE_ADMIN');     // Administradores

        // --- Total de inmuebles publicados ---
        $totalBienes = $bienRepo->count([]); // Conteo global de propiedades

        // Paso los datos a la vista del panel
        return $this->render('admin/dashboard.html.twig', [
            'totalUsuarios'    => $totalUsuarios,
            'clientes'         => $clientes,
            'propietarios'     => $propietarios,
            'administradores'  => $administradores,
            'totalBienes'      => $totalBienes,
        ]);
    }

    /**
     * Ruta: /admin/usuarios
     * Página para visualizar todos los usuarios registrados (sin edición por ahora).
     */
    #[Route('/admin/usuarios', name: 'admin_usuarios')]
    public function usuarios(UserRepository $userRepo): Response
    {
        $usuarios = $userRepo->findAll(); // Todos los usuarios sin filtro

        return $this->render('admin/usuarios.html.twig', [
            'usuarios' => $usuarios,
        ]);
    }

    /**
     * Ruta: /admin/estadisticas
     * Página para mostrar estadísticas simples sobre inmuebles por tipo y ciudad.
     */
    #[Route('/admin/estadisticas', name: 'admin_estadisticas')]
    public function estadisticas(BienRepository $bienRepo): Response
    {
        $porTipo = $bienRepo->countByType();   // Agrupación por tipo de inmueble
        $porCiudad = $bienRepo->countByCity(); // Agrupación por ciudad

        return $this->render('admin/estadisticas.html.twig', [
            'porTipo' => $porTipo,
            'porCiudad' => $porCiudad,
        ]);
    }
}
