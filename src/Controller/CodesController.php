<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Code;
use App\Utils\CodeUtils;

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
     */
    public function generate(Request $request)
    {
        //! TODO: catch MethodNotAllowedHttpException
        $nb = $request->request->get('nb') ?? 1;

        $codes = CodeUtils::generateCodes(
            $this->getDoctrine()->getRepository(Code::class),
            $this->getDoctrine()->getManager(),
            $nb
        );

//        for ($i = 1; $i <= $nb; $i++) {
//            $code = new Code();
//            $codeString = CodeUtils::generateCode($this->getDoctrine()->getRepository(Code::class));
//            $code->setCode($codeString);
//            $codes[] = $codeString;
//            $entityManager->persist($code);
//            $entityManager->flush();
//        }

        return $this->json(['Codes' => $codes]);
    }
}
