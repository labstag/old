<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\Admin\UserType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Lib\AbstractControllerLib;

/**
 * @Route("/admin/user")
 */
class UserController extends AbstractControllerLib
{
    /**
     * @Route("/", name="adminuser_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();
        $this->paginator($users);
        return $this->render('admin/user/index.html.twig');
    }

    /**
     * @Route("/new", name="adminuser_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('adminuser_index');
        }

        return $this->render(
            'admin/user/new.html.twig',
            [
                'user' => $user,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="adminuser_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute(
                'adminuser_index',
                [
                    'id' => $user->getId(),
                ]
            );
        }

        return $this->render(
            'admin/user/edit.html.twig',
            [
                'user' => $user,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @Route("/{id}", name="adminuser_delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $user): Response
    {
        $token = $request->request->get('_token');
        $id    = $user->getId();
        if ($this->isCsrfTokenValid('delete'.$id, $token)) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('adminuser_index');
    }
}
