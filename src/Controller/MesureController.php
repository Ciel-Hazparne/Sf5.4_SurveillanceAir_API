<?php

namespace App\Controller;

use App\Entity\CO2;
use App\Entity\COV;
use App\Entity\Hygrometrie;
use App\Entity\MesureDate;
use App\Entity\Temperature;
use App\Form\MesureType;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MesureController extends AbstractController
{
    /**
     * @Route("/mesures", name="mesures")
     * @return Response
     */

    public function home(Request $request)
    {
        $MesureDate = new MesureDate();
        $form = $this->createForm(MesureType::class, $MesureDate);
        $form->handleRequest($request);


        $CO2var = $this->getDoctrine()->getRepository(CO2::class)->findAll();
        $Hygrometrievar = $this->getDoctrine()->getRepository(Hygrometrie::class)->findAll();
        $Temperaturevar = $this->getDoctrine()->getRepository(Temperature::class)->findAll();
        $COVvar = $this->getDoctrine()->getRepository(COV::class)->findAll();

        //Formulaire de Recherche entre 2 dates

        if ($request->query->has('Submit')) {
            // Valeurs du Formulaire
            $min = new DateTime($request->query->get('DateMin'));
            $max = new DateTime($request->query->get('DateMax'));
        } else if ($request->query->has('DeleteSpe')) {
            // Valeurs du Formulaire
            $min = new DateTime($request->query->get('DateMin'));
            $max = new DateTime($request->query->get('DateMax'));

            // DELETE Global TEMPERATURE
            foreach ($Temperaturevar as $mesure) {
                if ($mesure->getDate() > $min && $mesure->getDate() < $max) {
                    $this->deleteGlobalTspe($mesure->getId());
                }
            }

            // DELETE Global CO2
            foreach ($CO2var as $mesure) {
                if ($mesure->getDate() > $min && $mesure->getDate() < $max) {
                    $this->deleteGlobalCspe($mesure->getId());
                }
            }

            // DELETE Global HYGROMETRIE
            foreach ($Hygrometrievar as $mesure) {
                if ($mesure->getDate() > $min && $mesure->getDate() < $max) {
                    $this->deleteGlobalHYspe($mesure->getId());
                }
            }

            // DELETE Global HUMIDITE
            foreach ($COVvar as $mesure) {
                if ($mesure->getDate() > $min && $mesure->getDate() < $max) {
                    $this->deleteGlobalCOVspe($mesure->getId());
                }
            }
        } else {
            // Valeurs par défaut
            $min = new DateTime("2020-03-02");
            $max = new DateTime("2025-03-01");
        }

        // AFFICHAGE CO2
        $CO2 = array();
        foreach ($CO2var as $mesure) {
            if ($mesure->getDate() > $min && $mesure->getDate() < $max) {
                $CO2variable["id"] = $mesure->getId();
                $CO2variable["data"] = $mesure->getData();
                $CO2variable["date"] = $mesure->getDate()->format("d/m/Y \à H:i");
                array_push($CO2, $CO2variable);
            }
        }
        $CO2 = array_reverse($CO2);

        // AFFICHAGE HYGROMÉTRIE
        $Hygrometrie = array();
        foreach ($Hygrometrievar as $mesure) {
            if ($mesure->getDate() > $min && $mesure->getDate() < $max) {
                $Hygrometrievariable["id"] = $mesure->getId();
                $Hygrometrievariable["data"] = $mesure->getData();
                $Hygrometrievariable["date"] = $mesure->getDate()->format("d/m/Y \à H:i");
                array_push($Hygrometrie, $Hygrometrievariable);
            }
        }
        $Hygrometrie = array_reverse($Hygrometrie);

        // AFFICHAGE TEMPÉRATURE
        $Temperature = array();
        foreach ($Temperaturevar as $mesure) {
            if ($mesure->getDate() > $min && $mesure->getDate() < $max) {
                $Temperaturevariable["id"] = $mesure->getId();
                $Temperaturevariable["data"] = $mesure->getData();
                $Temperaturevariable["date"] = $mesure->getDate()->format("d/m/Y \à H:i");
                array_push($Temperature, $Temperaturevariable);
            }
        }
        $Temperature = array_reverse($Temperature);

        // AFFICHAGE COV
        $COV = array();
        foreach ($COVvar as $mesure) {
            if ($mesure->getDate() > $min && $mesure->getDate() < $max) {
                $COVvariable["id"] = $mesure->getId();
                $COVvariable["data"] = $mesure->getData();
                $COVvariable["date"] = $mesure->getDate()->format("d/m/Y \à H:i");
                array_push($COV, $COVvariable);
            }
        }
        $COV = array_reverse($COV);

        return $this->render('mesures/mesures.html.twig', ['form' => $form->createView(), 'current_menu' => 'mesures', 'CO2' => $CO2, 'Hygrometrie' => $Hygrometrie, 'Temperature' => $Temperature, 'COV' => $COV
        ]);
    }




    //DELETE CO2

    /**
     * @Route("/mesures/co2/delete/{id}",name="mesures_delete_CO2", methods="DELETE")
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function deleteCO2(Request $request, $id): RedirectResponse
    {
        $mesures = $this->getDoctrine()->getRepository(CO2::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($mesures);
        $entityManager->flush();

        return $this->redirectToRoute('mesures');
    }

    //DELETE COV

    /**
     * @Route("/mesures/cov/delete/{id}",name="mesures_delete_COV", methods="DELETE")
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function deleteCOV(Request $request, $id): RedirectResponse
    {
        $mesures = $this->getDoctrine()->getRepository(COV::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($mesures);
        $entityManager->flush();

        return $this->redirectToRoute('mesures');
    }

    //DELETE HYGROMÉTRIE

    /**
     * @Route("/mesures/hygrometrie/delete/{id}",name="mesures_delete_hygrometrie", methods="DELETE")
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function deleteHygrometrie(Request $request, $id): RedirectResponse
    {
        $mesures = $this->getDoctrine()->getRepository(Hygrometrie::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($mesures);
        $entityManager->flush();

        return $this->redirectToRoute('mesures');
    }

    //DELETE TEMPÉRATURE

    /**
     * @Route("/mesures/temperature/delete/{id}",name="mesures_delete_temperature", methods="DELETE")
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function deleteTemperature(Request $request, $id): RedirectResponse
    {
        $mesures = $this->getDoctrine()->getRepository(Temperature::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($mesures);
        $entityManager->flush();

        return $this->redirectToRoute('mesures');
    }

    public function deleteGlobalTspe($id)
    {
        $mesures = $this->getDoctrine()->getRepository(Temperature::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($mesures);
        $entityManager->flush();

        return 0;
    }

    public function deleteGlobalCspe($id)
    {
        $mesures = $this->getDoctrine()->getRepository(CO2::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($mesures);
        $entityManager->flush();

        return 0;
    }

    public function deleteGlobalHYspe($id)
    {
        $mesures = $this->getDoctrine()->getRepository(Hygrometrie::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($mesures);
        $entityManager->flush();

        return 0;
    }

    public function deleteGlobalCOVspe($id)
    {
        $mesures = $this->getDoctrine()->getRepository(COV::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($mesures);
        $entityManager->flush();

        return 0;
    }

}