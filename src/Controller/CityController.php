<?php

namespace App\Controller;

use App\Entity\City;
use App\Form\CityType;
use App\Repository\CityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/city")
 */
class CityController extends AbstractApiController
{
    /**
     * @Route("/", name="city_index", methods={"GET"})
     */
    public function index(CityRepository $cityRepository): Response
    {
        $cities = $this->getDoctrine()->getRepository(City::class)->findAll();

        return $this->respond($cities);
    }

    /**
     * @Route("/new", name="city_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $city = new City();
        $form = $this->buildForm(CityType::class, $city);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($city);
            $entityManager->flush();

            return $this->respond($city);
        }

        return $this->respond($form, Response::HTTP_BAD_REQUEST);
        
    }

    /**
     * @Route("/{id}", name="city_show", methods={"GET"})
     */
    public function show(City $city): Response
    {
        return $this->respond($city);
    }

    // /**
    //  * @Route("/{id}/edit", name="city_edit", methods={"GET","POST"})
    //  */
    // public function edit(Request $request, City $city): Response
    // {
    //     $form = $this->createForm(CityType::class, $city);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $this->getDoctrine()->getManager()->flush();

    //         return $this->redirectToRoute('city_index');
    //     }

    //     return $this->render('city/edit.html.twig', [
    //         'city' => $city,
    //         'form' => $form->createView(),
    //     ]);
    // }

    /**
     * @Route("/{id}", name="city_delete", methods={"POST"})
     */
    public function delete(Request $request, City $city): Response
    {
        if ($this->isCsrfTokenValid('delete'.$city->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($city);
            $entityManager->flush();
        }

        return $this->respond('City deleted');
    }
}
