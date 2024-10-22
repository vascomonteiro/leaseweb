<?php

namespace App\Controller;

use LDAP\Result;
use App\Form\FileUploadType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class ServerListController extends AbstractController
{
    #[Route('/server/list', name: 'app_server_list', methods: ['GET'])]
    public function serverList(): Response
    {
        return $this->render('server_list/server_list.html.twig');
    }

    #[Route(['/server/upload', '/'], name: 'file_upload', methods: ['GET', 'POST'])]
    public function serverUploadFile(Request $request): Response
    {
        $form = $this->createForm(FileUploadType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $uploadedFile = $form->get('file')->getData();

            if ($uploadedFile) {
                try {
                    $uploadedFile->move(
                        $this->getParameter('uploads_directory'),
                        'data.xlsx'
                    );
                    $cache = new FilesystemAdapter();
                    $cache->delete('server_list_cache_key');

                    $this->addFlash('success', 'File uploaded successfully!');
                } catch (FileException $e) {
                    $this->addFlash('error', 'Failed to upload the file, check folder permissions');
                }
            }
        }

        return $this->render('server_list/server_file.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
