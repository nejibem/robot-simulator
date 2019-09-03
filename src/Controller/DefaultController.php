<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\RobotSimulator;

class DefaultController
{

    /**
    * @Route("/")
    */
    public function index(Request $request)
    {
        $commands = $request->request->get('commands');
        $report = (new RobotSimulator())
                    ->runCommands(explode("\r\n", $commands))
                    ->report();

        return new Response(
            '<html>
             <style>
             .main { width: 500px; margin: 20px auto; }
               .form, .report { padding: 20px; border: 1px solid #AAA; background: #EEE; } 
</style>
             <body>
             <div class="main">
             <h1>Robot Simulator</h1>
             <h2>Commands</h2>
             <div class="form">
                <form action="/" method="post"><textarea name="commands" cols="50" rows="10">'. $commands .'</textarea>
                <input type="submit" value="submit">
                </form></div>
                <h2>Report</h2>
             <div class="report">'. $report .'</div>
             </div>
             </body>
             </html>'
        );
    }
}