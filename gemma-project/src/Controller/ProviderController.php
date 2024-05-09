<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Provider;
use App\Form\ProviderForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

use function PHPSTORM_META\type;

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
    public function addProvider(/*Request $request*/): Response
    {
        $name = $_POST['name'] ?? null;
        $email = $_POST['email'] ?? null;
        $phone = $_POST['phone'] ?? null;
        if (empty($name) || empty($email) || empty($phone)) {
            return $this->redirectToRoute('homePage', ['error' => 'Missing required fields']);
        }
        $activity = isset($_POST['activity']) ? ($_POST['activity'] == 1) : false;
        $type = $_POST['type'] ?? null;

        $id = random_int(1000000, PHP_INT_MAX);
        $em = $this->getDoctrine()->getManager();
        $provider = new Provider();
        $provider->setName($name);
        $provider->setEmail($email);
        $provider->setPhone($phone);
        $provider->setActivity($activity);
        $provider->setId($id);
        $provider->setType($type);

        try {
            $em->persist($provider);
            $em->flush();
            return $this->redirectToRoute('homePage');
        } catch (\Exception $e) {
            // Manejo de excepciones aquí
            // Podrías redireccionar a otra página de error o mostrar un mensaje personalizado
            return $this->redirectToRoute('homePage', ['error' => 'An error occurred']);
        }
    }

    /**
     * @Route("/inactiveProviders", name="inactiveProviders", methods={"GET"})
     */
    public function showInactiveProviders()
    {
        $providers = $this->getDoctrine()->getRepository(Provider::class)->findAll();
        if(empty($providers)){ // Si intentem filtrar pero no hi ha cap inserit, retornarem a la pàgina principal
            return $this->redirectToRoute('homePage');
        }else{ // Sinó mostrarem tots els filtrats
            $providersFiltrats = array();
            foreach($providers as $provider){
                if($provider->getActivity() == 0){
                    array_push($providersFiltrats, $provider);
                }
            }
            return $this->render('/homePage.html.twig', [
                'providers' => $providersFiltrats,]);
        }
    }

    /**
     * @Route("/showProvider/{id}", name="showProvider", methods={"GET"})
     */
    public function showProvider($id): Response
    {
        $provider = $this->getDoctrine()->getRepository(Provider::class)->findOneBy(['id' => $id]);
        return $this->render('/editProvider.html.twig', [
            'provider' => $provider,]);
    }

    /**
     * @Route("/editprovider/{id}", name="editProvider", methods={"POST"})
     */
    public function editProvider($id): Response
    {
        $name = $_POST['name'] ?? null;
        $email = $_POST['email'] ?? null;
        $phone = $_POST['phone'] ?? null;
        if (empty($name) || empty($email) || empty($phone)) {
            return $this->redirectToRoute('homePage', ['error' => 'Missing required fields']);
        }
        
        $type = $_POST['type'] ?? null;
        $em = $this->getDoctrine()->getManager();
        $provider = $this->getDoctrine()->getRepository(Provider::class)->find($id);
        $provider->setName($name);
        $provider->setEmail($email);
        $provider->setPhone($phone);
        $activity = isset($_POST['activity']) ? ($_POST['activity'] == 1) : false;
        $provider->setActivity($activity);
        $provider->setType($type);
        $provider->setUpdatedAt();
        
        try {
            $em->flush($provider);
            return $this->redirectToRoute('homePage');
        } catch (\Exception $e) {
            // Manejo de excepciones aquí
            // Podrías redireccionar a otra página de error o mostrar un mensaje personalizado
            return $this->redirectToRoute('homePage', ['error' => 'An error occurred']);
        }
    }

    /**
     * @Route("/deleteProvider/{id}", name="deleteProvider", methods={"DELETE"})
     */
    public function deleteProvider($id): Response
    {
        // Aquesta funció ens permetrà eliminar un proveidor de la llista
        $provider = $this->getDoctrine()->getRepository(Provider::class)->findOneBy(['id' => $id]);
        $this->em->remove($provider);
        $this->em->flush();
        return $this->redirectToRoute('homePage');
    }

    /**
     * @Route("/activeProviders", name="activeProviders", methods={"GET"})
     */
    public function showActiveProviders()
    {
        $providers = $this->getDoctrine()->getRepository(Provider::class)->findAll();
        if(empty($providers)){ // Si intentem filtrar pero no hi ha cap inserit, retornarem a la pàgina principal
            return $this->redirectToRoute('homePage');
        }else{ // Sinó mostrarem tots els filtrats
            $providersFiltrats = array();
            foreach($providers as $provider){
                if($provider->getActivity() == 1){
                    array_push($providersFiltrats, $provider);
                }
            }
            return $this->render('/homePage.html.twig', [
                'providers' => $providersFiltrats,]);
        }
    }

    /**
     * @Route("/providers/pista", name="pistaProviders", methods={"GET"})
     */
    public function showPistaProviders()
    {
        $providers = $this->getDoctrine()->getRepository(Provider::class)->findAll();
        if(empty($providers)){ // Si intentem filtrar pero no hi ha cap inserit, retornarem a la pàgina principal
            return $this->redirectToRoute('homePage');
        }else{ // Sinó mostrarem tots els filtrats
            $providersFiltrats = array();
            foreach($providers as $provider){
                if($provider->getType() == 'Pista'){
                    array_push($providersFiltrats, $provider);
                }
            }
            return $this->render('/homePage.html.twig', [
                'providers' => $providersFiltrats,]);
        }
    }

    /**
     * @Route("/providers/hotel", name="hotelProviders", methods={"GET"})
     */
    public function showHotelProviders()
    {
        $providers = $this->getDoctrine()->getRepository(Provider::class)->findAll();
        if(empty($providers)){ // Si intentem filtrar pero no hi ha cap inserit, retornarem a la pàgina principal
            return $this->redirectToRoute('homePage');
        }else{ // Sinó mostrarem tots els filtrats
            $providersFiltrats = array();
            foreach($providers as $provider){
                if($provider->getType() == 'Hotel'){
                    array_push($providersFiltrats, $provider);
                }
            }
            return $this->render('/homePage.html.twig', [
                'providers' => $providersFiltrats,]);
        }
    }

    /**
     * @Route("/providers/complemento", name="complementoProviders", methods={"GET"})
     */
    public function showComplementoProviders()
    {
        $providers = $this->getDoctrine()->getRepository(Provider::class)->findAll();
        if(empty($providers)){ // Si intentem filtrar pero no hi ha cap inserit, retornarem a la pàgina principal
            return $this->redirectToRoute('homePage');
        }else{ // Sinó mostrarem tots els filtrats
            $providersFiltrats = array();
            foreach($providers as $provider){
                if($provider->getType() == 'Complemento'){
                    array_push($providersFiltrats, $provider);
                }
            }
            return $this->render('/homePage.html.twig', [
                'providers' => $providersFiltrats,]);
        }
    }

    /**
     * @Route("/createProvider", name="showNewProvider")
     */
    public function showNewProvider(): Response
    {
        return $this->render('/newProvider.html.twig');
    }
}
