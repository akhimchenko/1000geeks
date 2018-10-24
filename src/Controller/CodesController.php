<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use App\Entity\Code;
use App\Utils\CodeUtils;
use App\Utils\XlsUtils;

class CodesController extends AbstractController
{
    /**
     * @Route("/codes", name="codes")
     */
    public function index()
    {
        return $this->render('codes/index.html.twig', [
            'controller_name' => 'CodesController',
        ]);
    }

    /**
     * @Route("/generate", name="generate", methods={"POST"})
     * @param Request $request
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function generate(Request $request)
    {
        //! TODO: catch MethodNotAllowedHttpException when not POST
        $nb = $request->request->get('nb') ?? 1;

        $codes = CodeUtils::generateCodes(
            $this->getDoctrine()->getRepository(Code::class),
            $this->getDoctrine()->getManager(),
            $nb
        );

        if ($request->request->get('export') != null) {
            switch ($request->request->get('export')) {
                case 'xls':
                    try {
                        $file = XlsUtils::writeFile($codes);
                    } catch (\Exception $e) {
                        $response = new Response();
                        $response->setContent('Internal error: ' . $e->getMessage());
                        $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
                        $response->send();
                    }
                    $response = new BinaryFileResponse($file);
                    $response->headers->set('Content-Type', 'application/vnd.ms-excel');
                    $response->headers->set('Content-Length', filesize($file));
                    // Doesn't work
//                    $response->setContentDisposition(
//                        ResponseHeaderBag::DISPOSITION_ATTACHMENT,
//                        'codes.xls'
//                    );
                    header('Content-Disposition: attachment; filename="codes.xls"');
                    $response->sendContent();
                    exit;
                default:
                    $response = new Response();
                    $response->setContent('Unknown export format');
                    $response->setStatusCode(Response::HTTP_BAD_REQUEST);
                    $response->send();
                    break;
            }
        } else {
            return $this->json(['Codes' => $codes]);
        }
    }
}
