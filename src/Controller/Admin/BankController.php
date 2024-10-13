<?php

namespace Kibuzn\Controller\Admin;

use Doctrine\ORM\EntityManagerInterface;
use finfo;
use Kibuzn\Entity\Bank;
use Kibuzn\Form\BankType;
use Kibuzn\Repository\BankRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/bank')]
final class BankController extends AbstractController
{
    #[Route(name: 'admin_bank_index', methods: ['GET'])]
    public function index(BankRepository $bankRepository): Response
    {
        return $this->render('bank/index.html.twig', [
            'banks' => $bankRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'admin_bank_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $bank = new Bank();
        $form = $this->createForm(BankType::class, $bank);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $url = $bank->getUrl();

            if (!preg_match('#^https?://#i', $url)) {
                $url = 'https://' . $url;
            }

            // extract url base
            $urlParts = parse_url($url);
            $host = $urlParts['host'];

            $bank->setUrl('https://' . $host);

            // extract title
            $brand = explode('.', $host);
            // remove www or app
            $brand = array_filter($brand, function ($part) {
                return !in_array($part, ['www', 'app']);
            });
            $brand = ucfirst(current($brand));

            $bank->setBrand($brand);

            // Get the logo
            $href = 'https://img.logo.dev/' . $host . '?token=pk_F3YaoCbHSGKEIWZf0p6jGA';

            // Download the image file
            $fileContent = file_get_contents($href);

            if ($fileContent !== false && strlen($fileContent) > 0) {
                $path = '../assets/images/banks/';
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }

                // Determine the file extension based on the MIME type
                $finfo = new finfo(FILEINFO_MIME_TYPE);
                $mimeType = $finfo->buffer($fileContent);

                $extension = '';
                switch ($mimeType) {
                    case 'image/jpeg':
                        $extension = 'jpeg';
                        break;
                    case 'image/jpg':
                        $extension = 'jpg';
                        break;
                    case 'image/png':
                        $extension = 'png';
                        break;
                    case 'image/gif':
                        $extension = 'gif';
                        break;
                    case 'image/webp':
                        $extension = 'webp';
                        break;
                    case 'image/svg+xml':
                        $extension = 'svg';
                        break;
                    default:
                        throw new \Exception("Unsupported image type: " . $mimeType);
                }

                // Generate a unique filename
                $filename = $path . uniqid() . '.' . $extension;

                // Save the file content to the specified filename
                if (file_put_contents($filename, $fileContent) !== false) {
                    $bank->setLogo(str_replace('../assets/', '', $filename));
                }
            }

            $entityManager->persist($bank);
            $entityManager->flush();

            return $this->redirectToRoute('admin_bank_edit', ['id' => $bank->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('bank/new.html.twig', [
            'bank' => $bank,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_bank_show', methods: ['GET'])]
    public function show(Bank $bank): Response
    {
        return $this->render('bank/show.html.twig', [
            'bank' => $bank,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_bank_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Bank $bank, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BankType::class, $bank, ['is_edit' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle the uploaded logo if any
            $uploadedFile = $form->get('logo')->getData(); // This returns an UploadedFile object
        
            if ($uploadedFile !== null) {
                $path = '../assets/images/banks/';
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
        
                // Get the original file extension
                $extension = $uploadedFile->guessExtension();  // Automatically guesses the extension based on the file MIME type
        
                if ($extension === null) {
                    // If the extension can't be guessed, we can fall back on the client-provided extension
                    $extension = $uploadedFile->getClientOriginalExtension();
                }
        
                // Generate a unique filename
                $filename = uniqid() . '.' . $extension;
        
                // Move the uploaded file to the designated path
                $uploadedFile->move($path, $filename);
        
                // Store the file path in the Bank entity (adjusting the path to match your application structure)
                $bank->setLogo(str_replace('../assets/', '', $path . $filename));
            }
        
            $entityManager->flush();
        
            return $this->redirectToRoute('admin_bank_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('bank/edit.html.twig', [
            'bank' => $bank,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_bank_delete', methods: ['POST'])]
    public function delete(Request $request, Bank $bank, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$bank->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($bank);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_bank_index', [], Response::HTTP_SEE_OTHER);
    }
}
