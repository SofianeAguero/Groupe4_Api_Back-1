<?php

namespace App\AdminBundle\Controller;

use App\AdminBundle\Form\LoginType;
use App\AdminBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\AdminBundle\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Reponse;


/**
 * @Route("/user")
 * Class UserController
 * @package App\Controller
 */
class UserController extends AbstractController
{
    /**
     * @Route("/login", methods = {"GET"}, name ="user_authentication")
     * @param Request $request
     * @param AuthenticationUtils $authenticationUtils
     * @param TokenStorageInterface $storage
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function login(Request $request, AuthenticationUtils $authenticationUtils, TokenStorageInterface $storage)
    {
        $user = $storage->getToken()->getUser();
        if ($user instanceof UserInterface) {
            return $this->redirectToRoute('user_new');
        }
        $form = $this->createForm(LoginType::class, null, ['action' => $this->generateUrl('user_login_check')]);
        $form->handleRequest($request);
        return $this->render('user/login.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/check", methods = {"POST"}, name="user_login_check")
     * @throws AuthenticationErrorException
     */
    public function checkLogin()
    {
        throw new AuthenticationErrorException('Connection failed.');
    }

    /**
     * @Route("/new", name="user_new", methods={"GET","POST"})
     */
    public function new(Request $request)
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($request->isMethod('POST')) {
            $this->newApi($request);

        }

        /* if ($form->isSubmitted() && $form->isValid())
         {

             $task = $form->getData();

             $entityManager = $this->getDoctrine()->getManager();
             $entityManager->persist($task);
             $entityManager->flush();

         }*/

        return $this->render('team/new.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("", name="user_post", methods={"POST"})
     */
    public function newApi(Request $request)
    {
        /*SerializeInterface $serializer  -^ */
        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($task);
            $entityManager->flush();
        }

        /*$json = $serializer->Serialize(
    		$user,
    		'JSON',
    		['Groups'=>["light"]]
    	);

    	$response = new response();
    	$response->setContent($json);
    	$response->headers->set('Content-type', 'application/JSON');*/

    }


    /**
     * @Route ("/edit/{user}")
     * @param Request $request
     */
    public function edit(Request $request, User $user)
    {
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('user_list');
        }

        return $this->render('team/edit.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("", name="user_edit", methods={"PUT"})
     */
    public function editApi(Request $request)
    {
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('list.html.twig');
        }

        return $this->render('User/new.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/delete/{id}", name="user_delete", methods={"GET","POST"})
     */
    public function delete(Request $request)
    {

    }

    /**
     * @Route("", name="user_delete", methods={"DELETE"})
     */
    public function deleteApi(Request $request)
    {

    }


    /**
     * @Route("/list", name="user_list")
     */
    public function list()
    {
        return $this->render('User/list.html.twig');
    }

    /**
     * @Route("/team", name="team_list")
     */
    public function teamList()
    {
        return $this->render('team/list.html.twig');
    }

}
