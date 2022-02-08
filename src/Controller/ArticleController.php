<?php
namespace App\Controller;

use App\Service\PlaceholderImageService;
use Doctrine\DBAL\Driver\OCI8\Exception\Error;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/articles', 'articles_')]
class ArticleController extends AbstractController {

    /**
     * List available articles
     * @return Response
     */
    #[Route('/', 'list')]
    public function list(): Response {
        return new Response("<h1>Liste des articles</h1>");
    }

    /**
     * Show a article
     * @param int $articleID
     * @return Response
     */
    #[Route('/show/{articleID<\d+>}', 'show')]
    public function show(int $articleID): Response {
        return new Response("<h1>Affichage de l'article $articleID</h1>");
    }

    /**
     * Add a new article
     * @param PlaceholderImageService $placeholderImage
     * @return Response
     */
    #[Route('/add', 'add')]
    public function add(PlaceholderImageService $placeholderImage): Response {
        try {
            $success = $placeholderImage->getNewImageAndSave(350, 350, 'articlexyz-thumb.png');
        }
        catch (Error $e) {
            $success = false;
        }

        if ($success) {
            return new Response("<div>L'article a été créé avec succès</div>");
        }

        return new Response("<div>Erreur en ajoutant l'article</div>");
    }

    /**
     * Edit a article
     * @param int $articleID
     * @return Response
     */
    #[Route('/edit/{articleID<\d+>}', 'edit')]
    public function edit(int $articleID): Response {
        return new Response("<h1>Edition de l'article $articleID</h1>");
    }

    /**
     * Delete a article
     * @param int $articleID
     * @return Response
     */
    #[Route('/delete/{articleID<[a-zA-Z]+>}', 'delete')]
    public function delete(int $articleID): Response {
        return new Response("<h1>Article $articleID supprimé avec succès</h1>");
    }
}