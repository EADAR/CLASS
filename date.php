<?php

namespace App\Classes;

class date
{

    private $NomsJours = [
            "Dimanche",
            "Lundi",
            "Mardi",
            "Mercredi",
            "Jeudi",
            "Vendredi",
            "Samedi"
    ];

    private $NomsMois = [
            "Décembre",
            "Janvier",
            "Février",
            "Mars",
            "Avril",
            "Mai",
            "Juin",
            "Juillet",
            "Août",
            "Septembre",
            "Octobre",
            "Novembre"
    ];

    public function timestampToFullNumericDate($data)
    {
        return date('d/m/Y', $data) . ' ' . date('H:i:s', $data);
    }

    public function timestampToNumericDate($data)
    {
        return date('d/m/Y', $data);
    }

    public function timestampToHour($data)
    {
        return date('H:i:s', $data);
    }

    public function timestampToFullTextDate($data)
    {
        return $this->NomsJours[date("w", $data)] . " " . date("j", $data) . " " . $this->NomsMois[date("n", $data)] . " " . date("Y", $data);
    }

    public function timestampToTextDate($data)
    {
        return date("j", $data) . " " . $this->NomsMois[date("n", $data)] . " " . date("Y", $data);
    }

}