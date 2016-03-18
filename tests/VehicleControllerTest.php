<?php

namespace Tests\Acceptance;

use Tests\TestCase;
use App\Entities\Vehicle;

class VehicleControllerTest extends TestCase
{
    public function testView()
    {
        $this->visit('/')->see('vehicle">Ve');
    
        $this->visit('/vehicle')
            ->see('de acesso para esta p', true)
        ;
    }
    
    public function testCreate()
    {
        $this->visit('/vehicle')->see('Novo');
        
        $this->visit('/vehicle/create');
    
        $this->type('IOP-1234', 'number')
            ->type('123', 'initial_miliage')
            ->type('456', 'actual_miliage')
            ->type('90000', 'cost')
            ->type('Descricao', 'description')
            ->press('Enviar')
            ->seePageIs('/vehicle')
        ;
    
        $this->seeInDatabase(
            'vehicles',
            [
                    'number' => 'IOP-1234',
                    'initial_miliage' => '123',
                    'actual_miliage' => '456',
                    'cost' => '90000',
                    'description' => 'Descricao',
            ]
        );
    }
    
    public function testUpdate()
    {
        $this->visit('/vehicle/'.Vehicle::all()->last()['id'].'/edit');
        
        $this->type('IOP-1235', 'number')
            ->type('125', 'initial_miliage')
            ->type('455', 'actual_miliage')
            ->type('90005', 'cost')
            ->type('Descricao2', 'description')
            ->press('Enviar')
            ->seePageIs('/vehicle')
        ;
    
        $this->seeInDatabase(
            'vehicles',
            [
                    'number' => 'IOP-1235',
                    'initial_miliage' => '125',
                    'actual_miliage' => '455',
                    'cost' => '90005',
                    'description' => 'Descricao2',
            ]
        );
    }
    
    public function testDelete()
    {
        $this->seeInDatabase('vehicles', ['id' => 1]);
        $this->visit('/vehicle');
        $idOption = $this->crawler->filterXPath("//a[@name='Excluir']")->eq(0)->attr('name');
        $this->click($idOption);
        $this->seeIsSoftDeletedInDatabase('vehicles', ['id' => 1]);
    }
}