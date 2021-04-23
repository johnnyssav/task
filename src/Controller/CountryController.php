<?php

namespace App\Controller;

use App\Entity\Country;
use App\Form\CountryType;
use App\Repository\CountryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/country")
 */
class CountryController extends AbstractApiController
{
    
    public function index(CountryRepository $countryRepository): Response
    {
        
        $countries = $this->getDoctrine()->getRepository(Country::class)->findAll();

        return $this->respond($countries);
    }

    
    public function new(Request $request): Response
    {
        $country = new Country();
        $form = $this->buildForm(CountryType::class, $country);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($country);
            $entityManager->flush();

            return $this->respond($country);
            
        }

        return $this->respond($form, Response::HTTP_BAD_REQUEST);
    }

    /**
     * @Route("/{cname}", name="country_show", methods={"GET"})
     */
    public function show(Country $country): Response
    {
        return $this->respond($country);
    }

    // /**
    //  * @Route("/{cname}/edit", name="country_edit", methods={"GET","POST"})
    //  */
    // public function edit(Request $request, Country $country): Response
    // {
    //     $form = $this->createForm(CountryType::class, $country);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $this->getDoctrine()->getManager()->flush();

    //         return $this->redirectToRoute('country_index');
    //     }

    //     return $this->render('country/edit.html.twig', [
    //         'country' => $country,
    //         'form' => $form->createView(),
    //     ]);
    // }

    
    public function delete(Request $request, Country $country): Response
    {
        if ($this->isCsrfTokenValid('delete'.$country->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($country);
            $entityManager->flush();
        }

        return $this->respond('Country deleted');
    }
}
