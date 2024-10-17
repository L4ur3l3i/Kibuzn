<?php

namespace Kibuzn\Controller\Admin;

use Doctrine\ORM\EntityManagerInterface;
use Kibuzn\Entity\OperationType;
use Kibuzn\Form\OperationTypeType;
use Kibuzn\Repository\OperationTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/operation_type')]
final class OperationTypeController extends AbstractController
{
    #[Route(name: 'admin_operation_type_index', methods: ['GET'])]
    public function index(OperationTypeRepository $operationTypeRepository): Response
    {
        return $this->render('operation_type/index.html.twig', [
            'operation_types' => $operationTypeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'admin_operation_type_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $operationType = new OperationType();
        $form = $this->createForm(OperationTypeType::class, $operationType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $operationType->setAlias($slugger->slug(strtolower($operationType->getName())));
            $entityManager->persist($operationType);
            $entityManager->flush();

            return $this->redirectToRoute('admin_operation_type_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('operation_type/new.html.twig', [
            'operation_type' => $operationType,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_operation_type_show', methods: ['GET'])]
    public function show(OperationType $operationType): Response
    {
        return $this->render('operation_type/show.html.twig', [
            'operation_type' => $operationType,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_operation_type_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, OperationType $operationType, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(OperationTypeType::class, $operationType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('admin_operation_type_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('operation_type/edit.html.twig', [
            'operation_type' => $operationType,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_operation_type_delete', methods: ['POST'])]
    public function delete(Request $request, OperationType $operationType, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$operationType->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($operationType);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_operation_type_index', [], Response::HTTP_SEE_OTHER);
    }
}
