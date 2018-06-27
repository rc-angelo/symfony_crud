<?php

namespace App\Controller;

use App\Entity\Todo;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\Constraints\Date;

class TodoController extends Controller
{

    /**
      * @Route("/", name="todo")
      */
    public function index()
    {
        $todos = $this->getDoctrine()->getRepository(Todo::class)->findAll();

        $todo = new Todo();
        $form = $this->createFormBuilder($todo)
            ->setAction($this->generateUrl('todo_add'))
            ->add('name',TextType::class,['attr' => ['class' => 'form-control']])
            ->add('details',TextType::class,['attr' => ['class' => 'form-control']])
            ->add('label', ChoiceType::class, ['choices'  =>[
                'TODO' => 0,
                'In Progress' => 1,
                'DONE' => 2], 'attr' => ['class' => 'form-control']])
            ->add('date',TextType::class,['attr' => ['class' => 'form-control']])
            ->add('save', SubmitType::class, ['label' => 'Create Todo','attr' => ['class'=> 'btn btn-primary']])
            ->getForm();

        return $this->render('todo/index.html.twig', [
            'controller_name' => 'TodoController',
            'todos' => $todos,
            'form' => $form->createView()
        ]);


    }

    /**
     * @Route("/todo/add", name="todo_add")
     */
    public function create(Request $request)
    {
        $data = $request->request->get('form');
        $entityManager = $this->getDoctrine()->getManager();
        $datetime = \DateTime::createFromFormat('Y-m-d', $data['date']);

        $todo = new Todo();
        $todo->setName($data['name']);
        $todo->setDetails($data['details']);
        $todo->setLabel($data['label']);
        $todo->setDate($datetime);

        $entityManager->persist($todo);
        $entityManager->flush();

        $this->addFlash(
            'notice',
            'Successfully Added the record ' . $todo->getName()
        );

        return $this->redirectToRoute('todo');
    }


    /**
     * @Route("/todo/{page}/edit", name="todo_edit")
     */
    public function edit(Request $request,$page)
    {
        $todo = $this->getDoctrine()->getRepository(Todo::class)->find($page);
        $form = $this->createFormBuilder($todo)
            ->add('name',TextType::class,['attr' => ['class' => 'form-control']])
            ->add('details',TextType::class,['attr' => ['class' => 'form-control']])
            ->add('label', ChoiceType::class, ['choices'  =>[
                'TODO' => 0,
                'In Progress' => 1,
                'DONE' => 2], 'attr' => ['class' => 'form-control']])
            ->add('date',TextType::class,['attr' => ['class' => 'form-control']])
            ->add('save', SubmitType::class, ['label' => 'Edit','attr' => ['class'=> 'btn btn-primary']])
            ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $data = $request->request->get('form');
            $datetime = \DateTime::createFromFormat('Y-m-d', $data['date']);

            $entityManager = $this->getDoctrine()->getManager();

            $todo->setName($data['name']);
            $todo->setDetails($data['details']);
            $todo->setLabel($data['label']);
            $todo->setDate($datetime);

            $entityManager->persist($todo);

            $entityManager->flush();

            $this->addFlash(
                'notice',
                'Successfully modified the record ' . $todo->getName()
            );

            return $this->redirectToRoute('todo');
        }

        return $this->render('todo/edit.html.twig',[
            'controller_name' => 'TodoController',
            'name' => $todo->getName(),
            'todo' => $todo,
            'is_edit' => true,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/todo/{id}/delete", name="todo_delete", requirements={"id"="\d+"})
     */
    public function destroy($id)
    {
        $entityManger = $this->getDoctrine()->getManager();

        $entityManger->remove( $this->getDoctrine()->getRepository(Todo::class)->find($id) );

        $entityManger->flush();

        $this->addFlash(
            'notice',
            'Successfully deleted the record '
        );

        return $this->redirectToRoute('todo');
    }



}
