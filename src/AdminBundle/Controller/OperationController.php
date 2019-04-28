<?php

namespace App\AdminBundle\Controller;

use App\AdminBundle\Form\OperationType;
use App\AdminBundle\Entity\Operation;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/operation")
 * Class OperationController
 * @package App\Controller
 */
class OperationController extends AbstractController
{
    /**
     * @Route("/", name="app_admin_operation_list")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request)
    {
        $operations = $this->getDoctrine()
            ->getRepository(Operation::class)
            ->findAll();

        return $this->render('operation/list.html.twig', array(
            'operations' => $operations
        ));
    }


    /**
     * @Route("/new", methods = {"GET","POST"}, name="app_admin_operation_new")
     * @Route("/edit/{id}", name="app_admin_operation_edit")
     * @ParamConverter("obj", class="AdminBundle:Operation")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function new(Request $request, $obj = null)//permet egalement l'edition
    {
        if ($obj === null) {
            $obj = new Operation();
        }

        $form = $this->createForm(\App\AdminBundle\Form\OperationType::class, $obj);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $obj = $form->getData();
            $obj->setOperationAuthor($this->getUser());

            $this->getDoctrine()->getManager()->persist($obj);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('app_admin_operation_list');
        }

        $arr = array(
            'form' => $form->createView()
        );

        return $this->render('operation/new.html.twig', $arr);
    }

//    /**
//     * @Route("/edit/{operation}")
//     * @param Request $request
//     */
//    public function edit(Request $request, Operation $operation)
//    {
//        $form = $this->createForm(OperationType::class, $operation);
//        $form->add('submit', SubmitType::class, [
//            'label' => 'Modifier',
//            'attr' => ['class' => 'btn btn-default pull-right'],
//        ]);
//
//        $form->handleRequest($request);
//        if ($form->isSubmitted() && $form->isValid()) {
//            $em = $this->getDoctrine()->getManager();
//            $em->persist($operation);
//            $em->flush();
//            return $this->redirectToRoute('operation_list');
//        }
//
//        return $this->render('operation/edit.html.twig', array('form' => $form->createView()));
//    }

    /**
     * @Route("/delete/{id}", name="app_admin_operation_delete")
     * @ParamConverter("obj", class="AdminBundle:Operation")
     */
    public function delete(Request $request, $obj)
    {
        $this->getDoctrine()->getManager()->remove($obj);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('app_admin_operation_list');
    }


}
