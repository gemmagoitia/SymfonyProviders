<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Provider;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class ProviderController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/", name="homePage", methods={"GET"})
     */
    public function index(): Response
    {
        // A la pantalla principal voldrem mostrar una llista dels proveidors, aixi que cridarem el mètode del Repositori
        $providers = $this->getDoctrine()->getRepository(Provider::class)->findAll();
        return $this->render('/homePage.html.twig', [
            'providers' => $providers,]);
    }

    /**
     * @Route("/newProvider", name="newProvider", methods={"POST"})
     */
    public function addProvider(Request $request): Response
    {
        // Estem a la pagina on insertem les dades d'un nou proveidor per a ser afegit a la llista
        return $this->render('/newProvider.html.twig'); // Retornem la vista
        // Primer hem d'omplir el formulari amb la informació corresponent a cada camp
        $form = $this->createForm(Provider::class);
        $form->handleRequest($request);
        $provider = new Provider();
        // Inicialitzem les dades del proveidor
        $provider->setName($form->get('name')->getData());
        $provider->setEmail($form->get('email')->getData());
        $provider->setPhone($form->get('phone')->getData());
        $provider->setType($form->get('type')->getData());
        $provider->setActivity($form->get('activity')->getData());

        if($form->isSubmitted() && $form->isValid()){
            // Si el formulari s'ha enviat i és vàlid, guardarem les dades a la base de dades
            $this->getDoctrine()->getRepository(Provider::class)->createProvider($provider);
            $this->em->persist($provider);
            $this->em->flush();
            return $this->redirectToRoute('homePage');
        }
    }

    /**
     * @Route("/inactiveProviders", name="inactiveProviders", methods={"GET"})
     */
    public function showInactiveProviders()
    {
        $providers = $this->getDoctrine()->getRepository(Provider::class)->findAllInactive();
        if(empty($providers)){ // Si intentem filtrar pero no hi ha cap inserit, retornarem a la pàgina principal
            return $this->redirectToRoute('homePage');
        }else{ // Sinó mostrarem tots els filtrats
            return $this->render('/homePage.html.twig', [
                'providers' => $providers,]);
        }
    }

    /**
     * @Route("/showProvider/{id}", name="showProvider", methods={"GET"})
     */
    public function showProvider($id): Response
    {
        $provider = $this->getDoctrine()->getRepository(Provider::class)->findProviderById($id);
        return $this->render('/editProvider.html.twig', [
            'provider' => $provider,]);
    }

    /**
     * @Route("/editprovider/{id}", name="editProvider")
     */
    public function editProvider(Request $request, $id): Response
    {
        $provider = $this->getDoctrine()->getRepository(Provider::class)->findProviderById($id);
        $form = $this->createForm(Provider::class);
        $form->handleRequest($request);
        // Actualitzem les dades del proveidor, i si no 'shan modificat tornarem a guardar les que teniem
        $provider->setName($form->get('name')->getData());
        $provider->setEmail($form->get('email')->getData());
        $provider->setPhone($form->get('phone')->getData());
        $provider->setType($form->get('type')->getData());
        $provider->setActivity($form->get('activity')->getData());
        
        if($form->isSubmitted() && $form->isValid()){
            // Si el formulari s'ha enviat i és vàlid, guardarem les dades a la base de dades
        
        return $this->redirectToRoute('homePage');
        }
    }

    /**
     * @Route("/deleteProvider/{id}", name="deleteProvider", methods={"DELETE"})
     */
    public function deleteProvider($id): Response
    {
        // Aquesta funció ens permetrà eliminar un proveidor de la llista
        $provider = $this->getDoctrine()->getRepository(Provider::class)->findProviderById($id);
        $this->getDoctrine()->getRepository(Provider::class)->deleteProvider($provider);
        $this->em->remove($provider);
        $this->em->flush();
        return $this->redirectToRoute('homePage');
    }
}
