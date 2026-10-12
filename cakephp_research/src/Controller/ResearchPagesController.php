<?php
declare(strict_types=1);

namespace App\Controller;

class ResearchPagesController extends AppController
{
    public function index(): void
    {
        $this->set('message', 'Welkom bij mijn CakePHP onderzoek');
        $this->set('framework', 'CakePHP');
    }
}
