<?php
use App\Repository\InputsSaveRepository;

class InputsService{
    
    private InputsSaveRepository $inputsSaveRepository;

    public function __construct
    (
        InputsSaveRepository $inputsSaveRepository
    )
    {
        $this->inputsSaveRepository = $inputsSaveRepository;
    }


    public function findAll(): array
    {
        return $this->inputsSaveRepository->findAll();
    }
}