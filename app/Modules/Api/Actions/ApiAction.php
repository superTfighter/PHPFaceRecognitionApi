<?php

namespace App\Modules\Api\Actions;

use App\Traits\CoreTrait;

use Slim\Http\Request;
use Slim\Http\Response;

class ApiAction
{
    use CoreTrait;

    public function test($request, $response, $args)
    {
        echo "Working!";
    }


    public function train($request, $response, $args)
    {
        $pythonFolder = $this->settings['settings']['pythonPath'];
        $dataFolder = $this->settings['settings']['userDataFolder'];

        $pythonScript = $pythonFolder . "train.py";

        $command = escapeshellcmd("python3 ". $pythonScript ." ". $dataFolder);

        $output = shell_exec($command);

        return $response->withJson(['status' => 'success' , 'message' => $output]);
    }

    public function recognize($request, $response, $args)
    {
        $pythonFolder = $this->settings['settings']['pythonPath'];
        $dataFolder = $this->settings['settings']['userDataFolder'];

        $tempFolder = $this->settings['settings']['userDataFolder'] . '/temp';

         // Create data folder if not exist
        if (!is_dir($tempFolder)) {
            mkdir($tempFolder, 0777, true);
        }

        $base64image = $request->getParsedBody()['image'];

        $image = $this->storeImage($base64image,$tempFolder);

        $pythonScript = $pythonFolder . "recognize.py";

        $command = escapeshellcmd("python3 ". $pythonScript ." ". $dataFolder . " " . $image) ;

        $output = shell_exec($command);

        $this->logger->info($output);

        return $response->withJson(['message' => $output]);
    }

    public function store($request, $response, $args)
    {
        $userName = $args['username'];
        
        $base64image = $request->getParsedBody()['image'];

        $dataFolder = $this->settings['settings']['userDataFolder'] . '/' . $userName;

        // Create data folder if not exist
        if (!is_dir($dataFolder)) {
            mkdir($dataFolder, 0777, true);
        }

        $image = $this->storeImage($base64image,$dataFolder);

        return $response->withJson(['status' => "success", "code" => 200, "message" => "Sikeres hozzáadás!"]);
    }

    private function storeImage($base64image,$folder)
    {
        $image = base64_decode($base64image);
        
        $output_file = $folder . '/' . rand() . '.png';

        file_put_contents($output_file,$image);

        return $output_file;
    }
}
