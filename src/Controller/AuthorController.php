<?php

namespace App\Controller;
use App\Form\ZeiriminmaxType;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
    private $authors = array(
        array(
            'id' => 1, 'picture' => '/images/Victor-Hugo.jpg',
            'username' => ' Victor Hugo', 'email' => 'victor.hugo@gmail.com ', 'nb_books' => 100
        ),
        array(
            'id' => 2, 'picture' => '/images/william-shakespeare.jpg',
            'username' => ' William Shakespeare', 'email' => ' william.shakespeare@gmail.com', 'nb_books' => 200
        ),
        array(
            'id' => 3, 'picture' => '/images/Taha_Hussein.jpg',
            'username' => ' Taha Hussein', 'email' => 'taha.hussein@gmail.com', 'nb_books' => 300
        ),
    );

    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }

    
    #[Route('/showauthor/{name}', name: 'show_author')]
    public function showAuthor($name)
    {
        $author = $authorRepository->findAll();
       $form = $this->createForm(ZeiriminmaxType::class);
       if ($form ->isSubmitted()){
        $min =$form->get('min')->getData();
           $max =$form->get('max')->getData();
       
      $author=$authorRepository->minmax($min, $max);
       }
        return $this->render('author/show.html.twig', [
            'name' => $name,
        ]);
    }

    
    #[Route('/author/list', name: 'list_author')]
    public function list()
    {
        return $this->render('author/list.html.twig', [
            'authors' => $this->authors,
        ]);
    }

    #[Route('/author/details/{id}', name: 'author_details')]
    public function authorDetails($id)
    {
        $author = null;
       
        foreach ($this->authors as $authorData) {
            if ($authorData['id'] == $id) {
                $author = $authorData;
            };
        };
        return $this->render('author/showAuthor.html.twig', [
            'author' => $author,
            'id' => $id
        ]);
    }

    #[Route('/author/addStatic', name: 'add_author')]
    public function addAuthorStatic(ManagerRegistry $manager): Response
    {
        $em = $manager->getManager();

        $author = new Author();

        $author->setUsername("William Shakespeare");
        $author->setEmail("william.shakespeare@gmail.com");;

        $em->persist($author); 
        $em->flush();  

        return new Response("Author added successfully");
    }

    #[Route('/addAuthor', name: 'add_author')]
    public function addAuthor(ManagerRegistry $manager, Request $request): Response
    {
        $em = $manager->getManager();

        $author = new Author();

        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em->persist($author);
            $em->flush();

            return $this->redirectToRoute('list_authorDB');
        }
        return $this->renderForm('author/addAuthor.html.twig', ['form' => $form]);
    }

    #[Route('/listAuthor', name: 'list_authorDB')]
    public function listAuthor(AuthorRepository $authorepository): Response
    {
        return $this->render('author/listAuthor.html.twig', [
            'authors' => $authorepository->findAll(),
        ]);
    }

    #[Route('/author/edit/{id}', name: 'author_edit')]
    public function editAuthor(Request $request, ManagerRegistry $manager, $id, AuthorRepository $authorepository): Response
    {
        $em = $manager->getManager();

        $author  = $authorepository->find($id);
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $em->persist($author);
            $em->flush();
            return $this->redirectToRoute('list_authorDB');
        }
        return $this->renderForm('author/editAuthor.html.twig', [
            'author' => $author,
            'form' => $form,
        ]);
    }

    #[Route('/author/delete/{id}', name: 'author_delete')]
    public function deleteAuthor($id, ManagerRegistry $manager, AuthorRepository $authorepository): Response
    {
        $em = $manager->getManager();
        $author = $authorepository->find($id);
        if ($author->getNb_books() == 0) {
            $em->remove($author);
            $em->flush();
        } else {
            return  $this->render('author/errorDelete.html.twig');
        }
        return $this->redirectToRoute('list_authorDB');
    }

    
    #[Route('/author/list/OrderByEmail', name: 'app_author_list_ordered', methods: ['GET'])]
    public function listAuthorByEmail(AuthorRepository $authorRepository): Response
    {
        return $this->render('author/orderedList.html.twig', [
            'authors' => $authorRepository->showAllAuthorsOrderByEmail(),
        ]);
    }

    
    #[Route('/author/RechercheDQL', name: 'author_Search')]
    function RechercheDQL(AuthorRepository $repo, Request $request)
    {
        $min = $request->get('min');
        $max = $request->get('max');
        $author = $repo->SearchAuthorDQL($min, $max);
        return $this->render('author/listAuthor.html.twig', [
            'authors' => $author,
        ]);
    }

    
    #[Route('/author/DeleteDQL', name: 'author_DeleteDQL')]
    function DeleteDQL(AuthorRepository $repo)
    {
        $repo->DeleteAuthor();
        return $this->redirectToRoute('list_authorDB');
    }
}
