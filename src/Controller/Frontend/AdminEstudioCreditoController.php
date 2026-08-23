<?php

namespace App\Controller\Frontend;

use App\Entity\EstudioCliente;
use App\Repository\EstudioClienteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Controlador protegido para que el Administrador gestione los estudios de crédito.
 */
#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
class AdminEstudioCreditoController extends AbstractController
{
    /**
     * Lista todas las solicitudes recibidas, de la más nueva a la más antigua.
     */
    #[Route('/estudios-credito', name: 'admin_estudios_index', methods: ['GET'])]
    public function index(EstudioClienteRepository $repository): Response
    {
        $estudios = $repository->findBy([], ['fechaSolicitud' => 'DESC']);

        return $this->render('admin/estudio_credito/index.html.twig', [
            'estudios' => $estudios,
        ]);
    }

    /**
     * Exporta todos los registros a un archivo CSV compatible con Excel.
     */
    #[Route('/estudios-credito/exportar', name: 'admin_estudios_export', methods: ['GET'])]
    public function export(EstudioClienteRepository $repository): Response
    {
        $estudios = $repository->findBy([], ['fechaSolicitud' => 'DESC']);
        
        $output = fopen('php://temp', 'w');
        
        // Agregar BOM para que Excel en Windows lea correctamente las tildes y la letra Ñ (UTF-8)
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // Cabeceras de las columnas en Excel
        fputcsv($output, ['Fecha Solicitud', 'Cédula', 'Nombres y Apellidos', 'Celular', 'Correo', 'Profesión', 'Empresa', 'Cargo', 'Contrato', 'Ingresos Fijos', 'Ingresos Variables', 'Patrimonio Total', 'Deudas Financieras', 'Valor Inmueble', 'Monto Solicitado', 'Línea Crédito'], ';');
        
        // Llenado de los datos fila por fila
        foreach ($estudios as $e) {
            fputcsv($output, [
                $e->getFechaSolicitud()->format('d/m/Y H:i'),
                $e->getCedula(),
                $e->getNombresApellidos(),
                $e->getCelular(),
                $e->getCorreo(),
                $e->getProfesion() ?? 'N/A',
                $e->getEmpresaDondeLabora() ?? 'N/A',
                $e->getCargo() ?? 'N/A',
                $e->getTipoContrato() ?? 'N/A',
                $e->getIngresosFijos() ?? '0',
                $e->getIngresosVariables() ?? '0',
                $e->getPatrimonioTotal() ?? '0',
                $e->getDeudasFinancieras() ?? '0',
                $e->getValorInmueble() ?? '0',
                $e->getValorSolicitado() ?? '0',
                $e->getLineaCredito() ?? 'N/A'
            ], ';');
        }
        
        rewind($output);
        $csvContent = stream_get_contents($output);
        fclose($output);
        
        $response = new Response($csvContent);
        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="Estudios_Viabilidad_Consoria.csv"');
        
        return $response;
    }

    /**
     * Muestra los detalles completos de una solicitud específica.
     */
    #[Route('/estudios-credito/{id}', name: 'admin_estudios_show', methods: ['GET'])]
    public function show(EstudioCliente $estudio): Response
    {
        return $this->render('admin/estudio_credito/show.html.twig', [
            'estudio' => $estudio,
        ]);
    }

    /**
     * Acción segura para eliminar una solicitud de estudio de crédito.
     */
    #[Route('/estudios-credito/eliminar/{id}', name: 'admin_estudios_delete', methods: ['POST'])]
    public function delete(Request $request, EstudioCliente $estudio, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$estudio->getId(), $request->request->get('_token'))) {
            $em->remove($estudio);
            $em->flush();
            $this->addFlash('success', 'El estudio de crédito ha sido eliminado correctamente del sistema.');
        } else {
            $this->addFlash('error', 'Token de seguridad inválido. No se pudo eliminar.');
        }

        return $this->redirectToRoute('admin_estudios_index');
    }
}