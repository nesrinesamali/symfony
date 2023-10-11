<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController{
public $authors = array(


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

#[Route('/author/{n}', name: 'app_show')]
public function showAuthor($n){
  return $this->render('author/show.html.twig',['name'=>$n]);
}

#[Route('/list',name: 'list')]
public function list(){
    $authors = array(
        array('id' => 1, 'picture' => 'images/victor-hugo.jpg','username' => 'Victor Hugo', 'email' =>
            'victor.hugo@gmail.com ', 'nb_books' => 100),
        array('id' => 2, 'picture' => 'images/william-shakespeare.jpg','username' => ' William Shakespeare', 'email' =>
            ' william.shakespeare@gmail.com', 'nb_books' => 200 ),
        array('id' => 3, 'picture' => 'images/Taha_Hussein.jpg','username' => 'Taha Hussein', 'email' =>
            'taha.hussein@gmail.com', 'nb_books' => 300),
    );
return $this->render('author/list.html.twig',['authors'=>$authors]);
}
#[Route('/show/{id}',name: 'show')]
public function auhtorDetails ($id)
{
    $author = null;
    // Parcourez le tableau pour trouver l'auteur correspondant à l'ID
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
#[Route('/AjoutStatique', name: 'author_ajoutStatique')]
public function ajoutStatique(EntityManagerInterface $entityManager): Response
{
    
    $author1 = new Author();
    $author1->setUsername("Molière");
    $author1->setEmail("Molière@gmail.com"); 

    $entityManager->persist($author1);
    $entityManager->flush();

    return $this->redirectToRoute('list_author');
}
#[Route('/Ajout', name: 'author_ajout')]

public function  Ajout (Request  $request)
{
    $author=new Author();
    $form =$this->CreateForm(AuthorType::class,$author);
    $form->add('Save',SubmitType::class);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid())
    {
        $em=$this->getDoctrine()->getManager();
        $em->persist($author);
        $em->flush();
        return $this->redirectToRoute('list_author');
    }
    return $this->render('author/ajout.html.twig',['form'=>$form->createView()]);

}
#[Route('/edit/{id}', name: 'author_edit')]
public function modifier(AuthorRepository $repository, $id, Request $request)
{
    $author = $repository->find($id);
    $form = $this->createForm(AuthorType::class, $author);
    $form->add('Edit', SubmitType::class);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        $em = $this->getDoctrine()->getManager();
        $em->flush(); 
        return $this->redirectToRoute("list_author");
    }

    return $this->render('author/modifier.html.twig', [
        'form' => $form->createView(),
    ]);
}


#[Route('/delete/{id}', name: 'author_delete')]
public function deleteAuthor(Request $request, $id, ManagerRegistry $manager): Response
{
$em = $manager->getManager();
$authorRepository = $em->getRepository(Author::class);

$author = $authorRepository->find($id);


if ($author !== null) {
  
    $em->remove($author);
    $em->flush();

    $list = $authorRepository->findAll();

    return $this->render('author/listAuthor.html.twig', ['authors' => $list]);
} else {
   
    return new Response('Auteur non trouvé', Response::HTTP_NOT_FOUND);
}
}




}
