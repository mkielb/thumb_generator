<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use App\Entity\ThumbGenerator as ThumbGeneratorEntity;
use App\Form\ThumbGeneratorType;
use App\Service\ThumbGenerator\ThumbGenerator;
use App\Service\ThumbUploader\ThumbUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class ThumbGeneratorController
 * @package App\Controller
 */
class ThumbGeneratorController extends AbstractController
{
    /**
     * @Route("/", name="app_thumb_generate")
     * @param Request $request
     * @param ThumbUploader $thumbUploader
     * @return Response
     */
    public function generate(Request $request, ThumbUploader $thumbUploader)
    {
        $thumbGeneratorEntity = new ThumbGeneratorEntity();
        $form = $this->createForm(ThumbGeneratorType::class, $thumbGeneratorEntity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $thumbUploader->upload(
                    $thumbGeneratorEntity->getSaveLocation(),
                    (new ThumbGenerator(
                        $thumbGeneratorEntity->getImage()
                    ))->create()
                );
                $this->addFlash('success', 'Miniaturka prawidłowo wygenerowana i wysłana.');
            } catch (Throwable $e) {
                $this->addFlash('danger', $e->getMessage());
            }
        }

        return $this->render('thumb_generator/generate.html.twig', ['form' => $form->createView()]);
    }
}
